-- =====================================================
-- Migration 010: Phase 5 — remaining hardcoded text
-- Viata Luxe Guesthouse
-- =====================================================

-- ── contact-form.php labels ──
INSERT IGNORE INTO site_settings (setting_key, setting_value, created_at) VALUES
('contact_form_label_name', 'Name *', NOW()),
('contact_form_label_email', 'Email *', NOW()),
('contact_form_label_message', 'Message *', NOW()),
('contact_form_placeholder_msg', 'How can we help?', NOW()),
('contact_form_btn_text', 'Send Message', NOW()),
('contact_form_sending', 'Sending…', NOW());

-- ── apartment-cards.php specs ──
INSERT IGNORE INTO site_settings (setting_key, setting_value, created_at) VALUES
('apt_cards_spec_sleeps', 'Sleeps', NOW()),
('apt_cards_price_suffix', 'per night', NOW()),
('apt_cards_view_detail', 'View detail →', NOW()),
('apt_cards_book_now', 'Book Now', NOW()),
('apt_cards_spec_views', 'Bushveld Views', NOW());

-- ── pricing.php amenity descriptions ──
INSERT IGNORE INTO site_settings (setting_key, setting_value, created_at) VALUES
('pricing_amenity_wifi_desc', 'Complimentary high-speed', NOW()),
('pricing_amenity_dstv_desc', 'Flat-screen satellite', NOW()),
('pricing_amenity_full_kitchen_desc', 'Tea/Coffee, Minibar, Kettle', NOW()),
('pricing_amenity_gourmet_kitchen_desc', 'Fully equipped, premium', NOW()),
('pricing_amenity_secure_parking_desc', 'Covered, on-site', NOW()),
('pricing_amenity_swimming_pool_desc', 'Shared pool', NOW()),
('pricing_amenity_private_pool_desc', 'In-unit pool', NOW()),
('pricing_amenity_premium_pool_desc', 'In-suite, luxury', NOW()),
('pricing_amenity_air_conditioning_desc', 'Climate controlled', NOW()),
('pricing_amenity_ensuite_bathroom_desc', 'Private, modern fittings', NOW()),
('pricing_amenity_dishwasher_desc', 'Convenience included', NOW()),
('pricing_amenity_private_patio_desc', 'Outdoor relaxation', NOW()),
('pricing_amenity_private_balcony_desc', 'City view perch', NOW()),
('pricing_amenity_soaking_tub_desc', 'Deep soaking, spa-style', NOW()),
('pricing_amenity_panoramic_views_desc', 'Breathtaking Phalaborwa', NOW()),
('pricing_amenity_premium_linens_desc', 'Curated comfort', NOW());

-- ── pricing.php specs ──
INSERT IGNORE INTO site_settings (setting_key, setting_value, created_at) VALUES
('pricing_spec_sleeps', 'Sleeps', NOW());

-- ── featured.php price suffix ──
INSERT IGNORE INTO site_settings (setting_key, setting_value, created_at) VALUES
('featured_price_suffix', '/night', NOW());

-- ── testimonials.php badge ──
INSERT IGNORE INTO site_settings (setting_key, setting_value, created_at) VALUES
('testimonials_badge', 'Verified Guest', NOW());

-- ── gallery.php hero + filter ──
INSERT IGNORE INTO site_settings (setting_key, setting_value, created_at) VALUES
('gallery_hero_lead', 'Our curated collection of Viata Luxe interiors, kitchens, bathrooms, and outdoor spaces.', NOW()),
('gallery_filter_all', 'All', NOW());

-- ── safari.php chips + kicker ──
INSERT IGNORE INTO site_settings (setting_key, setting_value, created_at) VALUES
('safari_chip_gate_text', 'Phalaborwa Gate', NOW()),
('safari_chip_experiences_text', 'Boat · Canyon · Amarula', NOW()),
('safari_beyond_gate_kicker', 'Beyond Gate — measured distances', NOW());

-- ── accommodation.php ──
INSERT IGNORE INTO site_settings (setting_key, setting_value, created_at) VALUES
('accom_table_header_policy', 'Policy', NOW()),
('accom_table_header_detail', 'Detail', NOW()),
('accom_check_description', 'Wifi complimentary in room · DSTV flat-screen · Spacious en-suite', NOW()),
('accom_amenities_kicker', 'Amenities', NOW()),
('accom_amenity_fallback_1_name', 'Wifi + DSTV', NOW()),
('accom_amenity_fallback_1_desc', 'Complimentary WiFi · Flat-screen DSTV', NOW()),
('accom_amenity_fallback_2_name', 'Spacious', NOW()),
('accom_amenity_fallback_2_desc', 'Large en-suite, comfortable, curated', NOW()),
('accom_price_suffix', 'per night · Cancellation 0–7 days 100%', NOW()),
('accom_spec_sleeps_label', 'Sleeps', NOW()),
('accom_spec_city_views_label', 'City Views', NOW()),
('accom_view_detail_btn', 'View', NOW()),
('accom_view_detail_arrow', 'detail →', NOW()),
('accom_book_now_btn', 'Book Now', NOW());

-- ── apartment.php ──
INSERT IGNORE INTO site_settings (setting_key, setting_value, created_at) VALUES
('accom_spec_bathroom_label', 'Private bathroom', NOW()),
('apt_gallery_kicker_prefix', 'Gallery', NOW()),
('apt_gallery_kicker_suffix', 'extras', NOW()),
('apt_cta_btn_prefix', 'Book', NOW()),
('apt_cta_btn_suffix', '— NightsBridge', NOW()),
('accom_table_cancelation_title', 'Cancelation', NOW()),
('accom_table_bedding_title', 'Bedding', NOW()),
('accom_table_bedding_room_text', 'bedroom', NOW());

-- ── 404.php ──
INSERT IGNORE INTO site_settings (setting_key, setting_value, created_at) VALUES
('error_404_heading', '404', NOW()),
('error_404_body', 'The page you''re looking for doesn''t exist or has been moved.', NOW()),
('error_404_button', 'Return Home', NOW());

-- ── contact.php map subtitle ──
INSERT IGNORE INTO site_settings (setting_key, setting_value, created_at) VALUES
('contact_map_subtitle', 'Phalaborwa 1390 — Corner 13 Prinsloo & Nollie Bosman', NOW());
