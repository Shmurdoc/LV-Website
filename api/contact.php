<?php
/**
 * Public API: Contact submission — inserts into contact_submissions
 * Frontend reads via POST only; admin reads via get_unread_submissions_count() / /admin/contact
 * CSRF + honeypot + rate-limit + validation. No phantom — row is actually read by admin.
 *
 * Fields: name*, email*, phone, arrival_date, departure_date, guests, message* (notes)
 * Optional arrival/departure are validated as dates when present.
 */

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../includes/functions.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// CSRF (Ask First boundary per security-and-hardening)
if (!csrf_verify()) {
    http_response_code(403);
    echo json_encode(['error' => 'Invalid CSRF token. Refresh and try again.']);
    exit;
}

// Honeypot (security-and-hardening: abuse case)
$honeypot = trim($_POST['website'] ?? $_POST['hp'] ?? '');
if ($honeypot !== '') {
    http_response_code(400);
    echo json_encode(['error' => 'Spam detected.']);
    exit;
}

// Rate limit: 3/min per IP (simple in-memory via session)
$ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
$_SESSION['contact_rate'] = $_SESSION['contact_rate'] ?? [];
$now = time();
$_SESSION['contact_rate'][$ip] = array_filter($_SESSION['contact_rate'][$ip] ?? [], fn($t) => $now - $t < 60);
if (count($_SESSION['contact_rate'][$ip]) >= 3) {
    http_response_code(429);
    echo json_encode(['error' => 'Too many requests. Try again in a minute.']);
    exit;
}

// Field extraction
$name     = trim($_POST['name'] ?? '');
$email    = trim($_POST['email'] ?? '');
$phone    = trim($_POST['phone'] ?? '');
$notes    = trim($_POST['message'] ?? $_POST['notes'] ?? $_POST['comment'] ?? '');

$rawArrival   = trim($_POST['arrival'] ?? '');
$rawDeparture = trim($_POST['departure'] ?? '');
$rawGuests    = trim($_POST['guests'] ?? '');

// Validation at boundary
$errors = [];
if ($name === '' || mb_strlen($name) < 2 || mb_strlen($name) > 255)
    $errors['name'] = 'Name 2-255 chars required.';
if (!filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 255)
    $errors['email'] = 'Valid email required.';

// Arrival/departure: optional but both must be valid dates if present
$arrivalDate   = null;
$departureDate = null;
if ($rawArrival !== '') {
    $arrivalDate = DateTime::createFromFormat('Y-m-d', $rawArrival);
    if (!$arrivalDate || $arrivalDate->format('Y-m-d') !== $rawArrival)
        $errors['arrival'] = 'Invalid arrival date.';
}
if ($rawDeparture !== '') {
    $departureDate = DateTime::createFromFormat('Y-m-d', $rawDeparture);
    if (!$departureDate || $departureDate->format('Y-m-d') !== $rawDeparture)
        $errors['departure'] = 'Invalid departure date.';
}
if ($arrivalDate && $departureDate && $departureDate <= $arrivalDate)
    $errors['departure'] = 'Departure must be after arrival.';

// Guests: 1-20 or empty
$guests = null;
if ($rawGuests !== '') {
    $guests = filter_var($rawGuests, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 20]]);
    if ($guests === false) $errors['guests'] = 'Guests must be 1-20.';
}

// Phone: optional, max 30 chars
if (mb_strlen($phone) > 30) $errors['phone'] = 'Phone max 30 chars.';

// Build the message body from structured fields
$parts = [];
if ($rawArrival !== '')   $parts[] = "Arrival:   {$rawArrival}";
if ($rawDeparture !== '') $parts[] = "Departure: {$rawDeparture}";
if ($rawGuests !== '')    $parts[] = "Guests:    {$rawGuests}";
if ($phone !== '')        $parts[] = "Phone:     {$phone}";
if ($notes !== '')        $parts[] = "Notes:     {$notes}";
$message = implode("\n", $parts);
if (mb_strlen($message) > 5000) $errors['message'] = 'Message too long (5000 max).';

if ($errors) {
    http_response_code(422);
    echo json_encode(['error' => 'Validation failed', 'details' => $errors]);
    exit;
}

// Insert
try {
    $db = Database::get();
    $stmt = $db->prepare('
        INSERT INTO contact_submissions
            (name, email, phone, arrival_date, departure_date, guests, message, is_read, created_at)
        VALUES
            (:name, :email, :phone, :arrival, :departure, :guests, :message, 0, NOW())
    ');
    $stmt->execute([
        'name'      => $name,
        'email'     => $email,
        'phone'     => $phone !== '' ? $phone : null,
        'arrival'   => $arrivalDate  ? $arrivalDate->format('Y-m-d') : null,
        'departure' => $departureDate ? $departureDate->format('Y-m-d') : null,
        'guests'    => $guests,
        'message'   => $message,
    ]);
    $_SESSION['contact_rate'][$ip][] = $now;

    if (is_admin_logged_in()) {
        log_activity('contact_submit', 'contact_submissions', (int)$db->lastInsertId(), ['email' => $email]);
    }

    echo json_encode(['ok' => true, 'message' => 'Thanks - we\'ll reply shortly.']);
} catch (Throwable $e) {
    error_log('contact insert failed: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Could not save. Try email info@viataluxe.com.']);
}
