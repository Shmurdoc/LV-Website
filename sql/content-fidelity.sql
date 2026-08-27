-- =============================================================
-- Viata Luxe — Content Fidelity Fix (v2.0)
-- Replaces fabricated placeholder CMS content with the ORIGINAL
-- verbatim business content (sourced from D:\newkit\final website).
-- Run AFTER schema.sql + seed.sql. Idempotent (keyed updates).
-- =============================================================

USE viata_luxe;

-- =============================================================
-- 1. GLOBAL SETTINGS — real contact / branding (original values)
-- =============================================================
UPDATE global_settings SET setting_value = 'Viata Luxe Guesthouse — Luxury Accommodation in Phalaborwa Near Kruger National Park' WHERE setting_key='site_name';
UPDATE global_settings SET setting_value = 'Luxury self-catering guesthouse in Phalaborwa, minutes from Kruger National Park. 4 curated apartments. From R950/night.' WHERE setting_key='site_tagline';
UPDATE global_settings SET setting_value = 'Discover Viata Luxe Guesthouse in Phalaborwa — your elegant home away from home just minutes from Kruger National Park. Book luxury accommodation with comfort, style, and top-tier service.' WHERE setting_key='site_description';
UPDATE global_settings SET setting_value = '/Luxury Images/logos/logo-viata-full-dark.png' WHERE setting_key='logo';
UPDATE global_settings SET setting_value = '/Luxury Images/logos/logo-viata-monogram-gold.png' WHERE setting_key='favicon';
UPDATE global_settings SET setting_value = 'info@viataluxe.com' WHERE setting_key='contact_email';
UPDATE global_settings SET setting_value = '015 781 0518 / 079 418 2077' WHERE setting_key='contact_phone';
UPDATE global_settings SET setting_value = '27794182077' WHERE setting_key='whatsapp_number';
UPDATE global_settings SET setting_value = '86 Nollie Bosman Street, Phalaborwa, 1390' WHERE setting_key='address';

-- Real email/phone keys used by header/footer templates
INSERT INTO global_settings (setting_key, setting_value, setting_type, setting_group, sort_order) VALUES
('email', 'info@viataluxe.com', 'email', 'contact', 1),
('phone_tel', '+27157810518', 'phone', 'contact', 2),
('phone_tel_display', '015 781 0518', 'phone', 'contact', 3),
('phone_mobile', '+27794182077', 'phone', 'contact', 4),
('phone_mobile_display', '079 418 2077', 'phone', 'contact', 5),
('whatsapp', '27794182077', 'phone', 'contact', 6),
('address_full', '86 Nollie Bosman Street, Phalaborwa, 1390', 'text', 'contact', 7),
('meta_description_home', 'Discover Viata Luxe Guesthouse in Phalaborwa — elegant accommodation minutes from Kruger National Park. Book luxury self-catering with comfort, style, and top-tier service.', 'textarea', 'general', 10)
ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value);

-- Fix bogus social placeholder handles (leave empty unless real)
UPDATE global_settings SET setting_value = '' WHERE setting_key IN ('facebook_url','instagram_url','youtube_url','tripadvisor_url') AND setting_value IN ('https://facebook.com/vialuxe','https://instagram.com/vialuxe','https://youtube.com/@vialuxe','https://tripadvisor.com/vialuxe');

-- =============================================================
-- 2. TESTIMONIALS — original verbatim guests (Kurhula/Shawn/Ntsako/Dylan)
-- preserve the 1-to-1 real reviews from the original live site
-- =============================================================
UPDATE testimonials SET reviewer_name='Kurhula Hlomane', rating=5, source='booking.com',
  review_text='I enjoyed every moment of my stay. What an ambience.', is_featured=1, sort_order=1
  WHERE reviewer_name='Sarah M.' AND apartment_id IS NULL;

UPDATE testimonials SET reviewer_name='Shawn Radov', rating=5, source='google',
  review_text='First class services, friendly staff, amazing food my stay was a great working holiday experience', is_featured=1, sort_order=2
  WHERE reviewer_name='James K.';

UPDATE testimonials SET reviewer_name='Ntsako Phoebe Mabunda', rating=5, source='google',
  review_text='The service, the warmth and the beauty of this place was absolutely amazing. I will definitely be staying for longer next time. The host is an absolute professional and could easily be the kindest person I know.', is_featured=1, sort_order=3
  WHERE reviewer_name='Priya N.';

UPDATE testimonials SET reviewer_name='Dylan Chapman', rating=5, source='google',
  review_text='Amazing guesthouse! The units were super clean, amenities new, and the staff are really friendly. Will definitely be staying there again', is_featured=1, sort_order=4
  WHERE reviewer_name='David L.';

-- =============================================================
-- 3. APARTMENTS — real names/offering matching public slugs
-- =============================================================
UPDATE apartments SET name='Classic Apartment 1 (Bachelor)', subtitle='One Bedroom Apartment',
  description='One Bedroom Apartment. The Deluxe Room at Viata Luxe offers breathtaking views of Phalaborwa, especially enchanting at night. Explore Phalaborwa with curated tours — local culture and stunning landscapes. Breakfast and dinner available on request with menus from our affiliated exclusive restaurants, delivered to your apartment. 13 m², queen bed, self-catering.',
  price_per_night=950.00, max_guests=2, room_size_m2=13, bedrooms=1, beds_description='Queen 157cm', sort_order=1
  WHERE id=1;

UPDATE apartments SET name='Classic Apartment 2', subtitle='Classic Suite',
  description='Sophisticated classic suite with city views, self-catering, en-suite bathroom and free WiFi. 13 m² queen apartment with DSTV.', 
  price_per_night=950.00, max_guests=2, room_size_m2=13, bedrooms=1, beds_description='Queen 157cm', sort_order=2
  WHERE id=2;

UPDATE apartments SET name='Comfort Apartment 3', subtitle='Comfort Suite',
  description='Spacious comfort apartment with queen bed, city views, self-catering kitchen, en-suite and free WiFi. 13 m².', 
  price_per_night=950.00, max_guests=2, room_size_m2=13, bedrooms=1, beds_description='Queen 157cm', sort_order=3
  WHERE id=3;

UPDATE apartments SET name='Deluxe Apartment 4', subtitle='Deluxe Suite',
  description='Grand deluxe suite — super clean units, new amenities, premium linens. 13 m² with city views and self-catering.', 
  price_per_night=950.00, max_guests=2, room_size_m2=13, bedrooms=1, beds_description='Queen 157cm', sort_order=4
  WHERE id=4;

-- =============================================================
-- 4. HOME SECTIONS — verbatim original copy
-- =============================================================
UPDATE sections SET title='Kedibone Safari',
 subtitle='Luxury Accommodation in Phalaborwa',
 content='Prepare to embark on an unexpected soul journey as you enter Viata Luxe Guest House, nestled in the tranquil town of Phalaborwa, just moments from the Kruger National Park. True to its promise of luxury, a stay at Viata Luxe is marked by personalized service, elegant interiors, and a captivating atmosphere that celebrates the beauty of nature and relaxation.'
 WHERE id=1 AND section_type='hero';

UPDATE sections SET title='Why Viata Luxe?', subtitle=NULL,
 content='[{"value":"4","label":"Curated Apartments"},{"value":"R950","label":"From Per Night"},{"value":"2","label":"Minutes to Kruger Gate"},{"value":"100","label":"5-Star Reviews"}]'
 WHERE id=2 AND section_type='stats';

-- Promise & rooms copy (image-text, section 3)
UPDATE sections SET title='Viata Guesthouse — Luxury in Phalaborwa', subtitle='Our Rooms',
 content='<p>Elegantly decorated Bachelor and Superior apartments at Viata Luxe Guest House. Each apartment is designed for your comfort — sophistication and tranquility for your getaway.</p><h3>Our Amenities</h3><p>Enjoy freshly prepared breakfast on request, free Wi-Fi, and secure parking. Attentive staff, easy access to nearby attractions like the Kruger National Park.</p><h3>Dining Options</h3><p>Breakfast and dinner options upon request from our affiliated exclusive restaurants, delivered to your apartment for a relaxing, indulgent dining experience.</p><h3>Moments at Viata Luxe</h3><p>Relaxation in outdoor chillers · Braai under the stars · Serenity by the pool.</p>'
 WHERE id=3 AND section_type='image-text';

UPDATE sections SET title='Safari', subtitle='Kedibone Safari',
  content='In collaboration with Kedibone Safari we offer a wide range of wildlife and adventure experiences — Daily Kruger Safaris from Phalaborwa Gate, Exclusive Private Overnight Kruger Tours, Wildlife Photographic Safaris and Photographic & Lightroom Training.',
  image='uploads/safari/safari-hero.jpg'
 WHERE id=7 AND section_type='safari-teaser';

-- =============================================================
-- 5. SAFARI activities — 4 video facades (real YT ids) + verbs
-- =============================================================
INSERT INTO safari_activities (title, content, image, video_urls, link_text, sort_order, is_published) VALUES
('Kedibone Safari — Daily Safaris', 'Bold bare safaris from Phalaborwa Gate. Daily Kruger day trips and exclusive private overnight tours.', 'uploads/safari/game-drive.jpg', '["https://youtu.be/QSGZBKwRycw"]', 'Watch Safari Video', 1, 1),
('Classic Safari — Photographic', 'Wildlife photographic safaris and Lightroom training with professional guidance.', 'uploads/safari/bushwalk.jpg', '["https://youtu.be/UHpP4w8cBlI"]', 'Watch Video', 2, 1),
('Boat Safaris — Olifants River', 'Scenic boat safaris on the Olifants River — hippos, crocodiles, diverse birdlife. Visit Foskor Mine Museum and Masorini Archaeological Site.', 'uploads/safari/cultural.jpg', '["https://youtu.be/aZXatNfE3Ww"]', 'Watch Video', 3, 1),
('Adventure — Blyde & Amarula', 'Blyde River Canyon, one of the largest canyons in the world — hiking and boat trips. Visit the Amarula Lapa for a tasting.', 'uploads/safari/birding.jpg', '["https://youtu.be/sz-FMRRfpIk"]', 'Watch Video', 4, 1)
ON DUPLICATE KEY UPDATE title = VALUES(title);

-- =============================================================
-- 6. GALLERY — real categories + images (mapped already)
-- =============================================================