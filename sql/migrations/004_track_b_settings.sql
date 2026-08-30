-- Viata Luxe — Track B Settings Migration (Builder B)
-- Ensures site_settings keys exist for branding/preloader/trust/booking/seo/footer
-- Normalizes logo_dark vs logo, favicon etc. — idempotent ON DUPLICATE KEY UPDATE
-- Run: mysql -u root viata_luxe < sql/migrations/004_track_b_settings.sql
-- Reversible DOWN at bottom (DELETE WHERE setting_key IN (...))

USE viata_luxe;

-- site_settings is canonical; global_settings is VIEW alias (via 002). All INSERTs target site_settings.

-- =====================================================
-- 1. Branding — logo_dark / logo_light / logo_monogram / favicon / site_name_brand
-- =====================================================
INSERT INTO site_settings (setting_key, setting_value, setting_type, setting_group, sort_order) VALUES
('logo_dark', '/Luxury Images/logos/logo-kruger-national-park.png', 'image', 'branding', 1),
('logo_light', '/Luxury Images/logos/logo-kruger-national-park-text.png', 'image', 'branding', 2),
('logo_monogram', '/Luxury Images/logos/logo-viata-monogram-gold.png', 'image', 'branding', 3),
('favicon', '/Luxury Images/logos/logo-viata-monogram-gold.png', 'image', 'branding', 4),
('site_name_brand', 'Viata Luxe Guesthouse', 'text', 'branding', 5)
ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value), setting_type = VALUES(setting_type), setting_group = VALUES(setting_group);

-- Ensure legacy logo key still points to dark logo for B/C
INSERT INTO site_settings (setting_key, setting_value, setting_type, setting_group, sort_order)
SELECT 'logo', '/Luxury Images/logos/logo-kruger-national-park.png', 'image', 'general', 4
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM site_settings WHERE setting_key='logo')
ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value);

-- =====================================================
-- 2. Preloader — preloader_mark/sub/bg
-- =====================================================
INSERT INTO site_settings (setting_key, setting_value, setting_type, setting_group, sort_order) VALUES
('preloader_mark', 'Viata Luxe', 'text', 'branding', 10),
('preloader_sub', 'Phalaborwa · Kruger Minutes', 'text', 'branding', 11),
('preloader_bg', '#0B1A2E', 'text', 'branding', 12)
ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value);

-- =====================================================
-- 3. Trust bar — badge / nightsbridge / kicker / right
-- =====================================================
INSERT INTO site_settings (setting_key, setting_value, setting_type, setting_group, sort_order) VALUES
('trust_badge_text', '86 Nollie Bosman St', 'text', 'trust', 1),
('trust_badge_sub', '· Phalaborwa 1390', 'text', 'trust', 2),
('trust_nightsbridge', 'NightsBridge · Instant book', 'text', 'trust', 3),
('trust_kicker', 'Minutes to Kruger Gate', 'text', 'trust', 4),
('trust_right_bold', 'No catalogue.', 'text', 'trust', 5),
('trust_right_text', '4 apartments, each curated.', 'text', 'trust', 6),
('trust_right_muted', 'From R950 · Host on arrival', 'text', 'trust', 7)
ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value);

-- =====================================================
-- 4. Booking — booking_url / cta + whatsapp for specials
-- =====================================================
INSERT INTO site_settings (setting_key, setting_value, setting_type, setting_group, sort_order) VALUES
('booking_url', 'https://book.nightsbridge.com/38331', 'url', 'booking', 1),
('booking_cta_text', 'Check Availability — NightsBridge', 'text', 'booking', 2),
('booking_button_text', 'Book Now', 'text', 'booking', 3),
('booking_whatsapp_number', '27618417838', 'phone', 'booking', 4),
('booking_whatsapp_message', 'Hi Viata Luxe, I''d like to enquire about the 3-night stay offer.', 'text', 'booking', 5)
ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value);

-- =====================================================
-- 5. SEO — meta/OG canonical/json LD keys (site_settings.seo group)
-- =====================================================
INSERT INTO site_settings (setting_key, setting_value, setting_type, setting_group, sort_order) VALUES
('seo_title_home', 'Viata Luxe Guesthouse — Luxury Accommodation in Phalaborwa Near Kruger National Park', 'text', 'seo', 1),
('seo_description_home', 'Discover Viata Luxe Guesthouse in Phalaborwa — your elegant home away from home just minutes from Kruger National Park. Book luxury accommodation with comfort, style, and top-tier service.', 'textarea', 'seo', 2),
('seo_og_title', 'Viata Luxe Guesthouse — Luxury Accommodation in Phalaborwa', 'text', 'seo', 3),
('seo_og_description', 'Elegant guesthouse minutes from Kruger National Park. 4 curated apartments, self-catering, from R950/night.', 'textarea', 'seo', 4),
('seo_og_type', 'website', 'text', 'seo', 5),
('seo_og_url', 'https://viataluxe.com/', 'url', 'seo', 6),
('seo_og_image', 'https://viataluxe.com/Luxury%20Images/pool/pool-overview-entertainment-area.jpg', 'url', 'seo', 7),
('seo_og_locale', 'en_ZA', 'text', 'seo', 8),
('seo_site_name', 'Viata Luxe Guesthouse', 'text', 'seo', 9),
('seo_twitter_card', 'summary_large_image', 'text', 'seo', 10),
('seo_canonical', 'https://viataluxe.com/', 'url', 'seo', 11),
('og_image_home', 'https://viataluxe.com/Luxury%20Images/pool/pool-overview-entertainment-area.jpg', 'url', 'general', 12)
ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value);

-- =====================================================
-- 6. Footer — brand/copyright/credit/legal + contact fallback
-- =====================================================
INSERT INTO site_settings (setting_key, setting_value, setting_type, setting_group, sort_order) VALUES
('footer_brand', 'Viata Luxe · Phalaborwa', 'text', 'footer', 1),
('footer_copyright', '© 2026 Viata Luxe Guesthouse. 86 Nollie Bosman Street, Phalaborwa 1390.', 'text', 'footer', 2),
('footer_credit', 'Built with pride by Recast Media', 'text', 'footer', 3),
('footer_legal', '© 2026 Viata Luxe Guesthouse. All rights reserved.', 'text', 'footer', 4),
('footer_about', 'Viata Luxe Guesthouse offers award-winning luxury self-catering accommodation in the heart of Limpopo. Perfect for business travelers, weekend getaways, and bushveld adventures.', 'textarea', 'footer', 5),
('site_tagline', 'Luxury self-catering in Phalaborwa — bushveld views, secure parking, jacuzzi.', 'text', 'general', 2)
ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value);

-- =====================================================
-- 7. Contact — ensure phone/whatsapp/email/address_full exist (idempotent)
-- =====================================================
INSERT INTO site_settings (setting_key, setting_value, setting_type, setting_group, sort_order) VALUES
('email', 'info@viataluxe.com', 'email', 'contact', 1),
('phone_tel', '+27157810518', 'phone', 'contact', 2),
('phone_tel_display', '015 781 0518', 'phone', 'contact', 3),
('phone_mobile', '+27794182077', 'phone', 'contact', 4),
('phone_mobile_display', '079 418 2077', 'phone', 'contact', 5),
('whatsapp', '27794182077', 'phone', 'contact', 6),
('address_full', '86 Nollie Bosman Street, Phalaborwa, 1390', 'text', 'contact', 7)
ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value);

-- =====================================================
-- 8. Normalize charset
-- =====================================================
ALTER TABLE site_settings CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- =====================================================
-- DOWN (rollback) — manual:
-- =====================================================
-- DELETE FROM site_settings WHERE setting_key IN (
--   'logo_dark','logo_light','logo_monogram','favicon','site_name_brand',
--   'preloader_mark','preloader_sub','preloader_bg',
--   'trust_badge_text','trust_badge_sub','trust_nightsbridge','trust_kicker','trust_right_bold','trust_right_text','trust_right_muted',
--   'booking_url','booking_cta_text','booking_button_text','booking_whatsapp_number','booking_whatsapp_message',
--   'seo_title_home','seo_description_home','seo_og_title','seo_og_description','seo_og_type','seo_og_url','seo_og_image','seo_og_locale','seo_site_name','seo_twitter_card','seo_canonical','og_image_home',
--   'footer_brand','footer_copyright','footer_credit','footer_legal','footer_about'
-- );
