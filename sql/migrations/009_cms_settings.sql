-- =====================================================
-- Migration 009: Add settings for hardcoded frontend content
-- Viata Luxe Guesthouse
-- =====================================================
-- Adds settings keys so hardcoded template text becomes
-- editable via Admin → Settings.
-- =====================================================

-- ── Hero meta strip (render-section.php) ──
INSERT IGNORE INTO site_settings (setting_key, setting_value, created_at) VALUES
('hero_meta_strip', 'Self-catering · 4 Apartments · Hosted', NOW());

-- ── Featured section (featured.php) ──
INSERT IGNORE INTO site_settings (setting_key, setting_value, created_at) VALUES
('featured_kicker', '4 Luxury Options', NOW()),
('featured_heading_prefix', 'Our', NOW()),
('featured_heading_suffix', ' Apartments', NOW()),
('featured_subhead', 'Each 13 m² suite is self-catering with city views, queen bed, and everything you need for a comfortable Phalaborwa stay.', NOW()),
('featured_cta_text', 'View Details', NOW());

-- ── Pricing section (pricing.php) ──
INSERT IGNORE INTO site_settings (setting_key, setting_value, created_at) VALUES
('pricing_spec_label', 'City Views', NOW()),
('pricing_price_suffix', 'per night · Self-catering', NOW());

-- ── Dining fallback items (dining.php) ──
INSERT IGNORE INTO site_settings (setting_key, setting_value, created_at) VALUES
('dining_fallback_items', '[{"icon":"🍽️","title":"Self-Catering","text":"Full kitchens in every apartment with modern appliances."},{"icon":"🔥","title":"Braai & Boma","text":"Outdoor braai area with boma seating under the stars."},{"icon":"🍽️","title":"Local Restaurants","text":"Minutes from Phalaborwa dining — Letšatši, Nando\'s, and more."},{"icon":"🍽️","title":"Private Bush Dinner","text":"Curated private dining experience in the bushveld."}]', NOW());

-- ── Contact page (contact.php) ──
INSERT IGNORE INTO site_settings (setting_key, setting_value, created_at) VALUES
('contact_hero_title', 'Contact <em>Us</em>', NOW()),
('contact_form_heading', 'Send us a message', NOW()),
('contact_form_placeholder_name', 'Your name', NOW()),
('contact_form_placeholder_email', 'you@example.com', NOW()),
('contact_form_placeholder_comment', 'Comment or Message', NOW()),
('contact_form_button', 'Send Enquiry', NOW()),
('contact_info_card_phone_label', 'Phone', NOW()),
('contact_info_card_email_label', 'Email', NOW()),
('contact_info_card_address_label', 'Address', NOW()),
('contact_map_button', 'Load Map — Google Maps', NOW()),
('contact_nightsbridge_kicker', 'NightsBridge — Instant book', NOW()),
('contact_nightsbridge_title', 'Book direct — 38331', NOW()),
('contact_nightsbridge_desc', 'Book direct via NightsBridge — instant confirmation.', NOW()),
('contact_nightsbridge_cta', 'Open NightsBridge — 38331', NOW()),
('contact_business_heading', 'Minutes to Kruger', NOW()),
('contact_business_body', 'We Would Love To Hear From You...', NOW());

-- ── Accommodation page (accommodation.php) ──
INSERT IGNORE INTO site_settings (setting_key, setting_value, created_at) VALUES
('accom_hero_kicker', 'Accommodation — 4 Apartments · Viata Luxe', NOW()),
('accom_hero_title', 'Four apartments.<br><em>One standard: luxe.</em>', NOW()),
('accom_hero_lead', 'One Bedroom Apartment · 5 Sleeper Apartment · Classic 1 · Classic 2 — self-catering, city views, from R950/night.', NOW()),
('accom_chip_size', '13 m² · Queen beds', NOW()),
('accom_chip_guests', 'Max 2–6 guests', NOW()),
('accom_rate_title', 'Rate & Terms', NOW()),
('accom_rate_cancelation', 'Cancelation Policy', NOW()),
('accom_rate_cancel_0_7', '0–7 days within stay: 100% charged', NOW()),
('accom_rate_cancel_8_14', '8–14 days within stay: 50% charged', NOW()),
('accom_rate_cancel_15plus', '15+ days: full refund', NOW()),
('accom_rate_wifi', 'Wifi complimentary', NOW()),
('accom_5sleeper_title', '5 Sleeper Apartment', NOW()),
('accom_5sleeper_desc', '5 Sleeper: 1 bedroom queen-sized bed, sleeper couch, and double bunk — ideal for families.', NOW()),
('accom_cta_heading', 'Ready to stay?<br><em>One check.</em>', NOW()),
('accom_cta_body', 'Pick Classic 1–4, pick a date, instant confirm.', NOW());

-- ── Safari page (safari.php) ──
INSERT IGNORE INTO site_settings (setting_key, setting_value, created_at) VALUES
('safari_hero_title', 'Kedibone <em>Safari.</em>', NOW()),
('safari_chip_gate', 'Phalaborwa Gate', NOW()),
('safari_chip_experiences', 'Boat · Canyon · Amarula', NOW()),
('safari_video_kicker', 'Video — Safari Highlights', NOW()),
('safari_boat_title', 'Boat Safaris', NOW()),
('safari_boat_desc', 'Olifants River boat cruises with hippos, crocs, and elephants along the banks. Visit Foskor Mine Museum and Masorini archaeological site.', NOW()),
('safari_adventure_title', 'Adventure', NOW()),
('safari_adventure_desc', 'Blyde River Canyon, God\'s Window, Bourke\'s Luck Potholes, and the famous Amarula Lapa — elephant encounters and sunset drinks.', NOW()),
('safari_cta_label', 'Download Pricelist — Kedibone 2025 PDF', NOW());

-- ── Apartment detail page (apartment.php) ──
INSERT IGNORE INTO site_settings (setting_key, setting_value, created_at) VALUES
('apt_spec_chips', 'City view · Private bathroom', NOW()),
('apt_spec_label', 'City Views', NOW()),
('apt_price_suffix', 'per night · 0–7 days cancellation 100%', NOW()),
('apt_info_card_1_eyebrow', 'City Views', NOW()),
('apt_info_card_1_title', 'Breathtaking Phalaborwa', NOW()),
('apt_info_card_1_desc', 'Wake to city views from your private apartment.', NOW()),
('apt_info_card_2_eyebrow', 'Tours', NOW()),
('apt_info_card_2_title', 'Explore local culture', NOW()),
('apt_info_card_2_desc', 'Kruger Gate in 15 minutes. Blyde River Canyon nearby.', NOW()),
('apt_info_card_3_eyebrow', 'Drinks & Food', NOW()),
('apt_info_card_3_title', 'Gourmet delivered', NOW()),
('apt_info_card_3_desc', 'Self-catering kitchen. Local restaurants minutes away.', NOW()),
('apt_cancelation_title', 'Cancelation', NOW()),
('apt_cancelation_policy', '0–7 days within stay: 100% charged', NOW()),
('apt_cta_heading', 'awaits.<br><em>Book direct.</em>', NOW()),
('apt_cta_body', 'm² · Queen 157cm · City views · Host on arrival', NOW()),
('apt_booking_com_text', 'Also via Booking.com', NOW());

-- ── Footer (footer.php) ──
INSERT IGNORE INTO site_settings (setting_key, setting_value, created_at) VALUES
('footer_fine_print', 'Instant confirm · Secure · Best rate', NOW());

-- Phase 3 additions (booking-cta fallbacks + safari-teaser fallbacks)
INSERT IGNORE INTO site_settings (setting_key, setting_value, setting_type, setting_group, created_at, updated_at) VALUES
('booking_cta_label', 'Book Now', 'text', 'content', NOW(), NOW()),
('booking_cta_fine_print', 'Self-catering · Secure · Instant confirm', 'text', 'content', NOW(), NOW()),
('safari_teaser_body_title', 'Safari Videos', 'text', 'content', NOW(), NOW()),
('safari_teaser_body_sub', 'Click to play — Kruger wildlife footage', 'text', 'content', NOW(), NOW()),
('homepage_hero_cta_book', 'Book Now — NightsBridge', 'text', 'content', NOW(), NOW()),
('homepage_hero_cta_explore', 'Explore Accommodation', 'text', 'content', NOW(), NOW());
