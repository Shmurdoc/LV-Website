<?php
/**
 * 404 Page — Viata Luxe Guesthouse
 */

http_response_code(404);

$nav = get_navigation();
$settings = settings_group('branding');
$contact = settings_group('contact');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Not Found — Viata Luxe Guesthouse</title>
    <link rel="stylesheet" href="/css/tokens.css">
    <link rel="stylesheet" href="/css/main.css">
</head>
<body>
    <main id="main-content" style="min-height: 60vh; display: flex; align-items: center; justify-content: center; text-align: center; padding: 4rem 2rem;">
        <div>
            <h1 style="font-family: var(--font-display); font-size: 6rem; color: var(--gold); margin-bottom: 1rem;">404</h1>
            <p style="font-size: 1.25rem; color: var(--text-secondary); margin-bottom: 2rem;">The page you're looking for doesn't exist or has been moved.</p>
            <a href="/" class="btn btn-primary">Return Home</a>
        </div>
    </main>
</body>
</html>
