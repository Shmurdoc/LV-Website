-- Viata Luxe Guesthouse — Seed Data
-- Run after schema.sql

USE viata_luxe;

-- =====================================================
-- GLOBAL SETTINGS
-- =====================================================
INSERT INTO global_settings (setting_key, setting_value, setting_type, setting_group, sort_order) VALUES
-- General
('site_name', 'Viata Luxe Guesthouse', 'text', 'general', 1),
('site_tagline', 'Luxury Self-Catering Accommodation · Limpopo', 'text', 'general', 2),
('site_description', 'Award-winning luxury self-catering guesthouse in Limpopo, South Africa. 4-star rated with secure parking, jacuzzi, and bushveld views.', 'textarea', 'general', 3),
('logo', 'assets/images/logo.png', 'image', 'general', 4),
('favicon', 'assets/images/favicon.png', 'image', 'general', 5),
('contact_email', 'reservations@vialuxe.co.za', 'email', 'general', 6),
('contact_phone', '+27 82 000 0000', 'phone', 'general', 7),
('whatsapp_number', '27820000000', 'phone', 'general', 8),
('address', 'Limpopo, South Africa', 'text', 'general', 9),

-- Social
('facebook_url', 'https://facebook.com/vialuxe', 'url', 'social', 1),
('instagram_url', 'https://instagram.com/vialuxe', 'url', 'social', 2),
('youtube_url', 'https://youtube.com/@vialuxe', 'url', 'social', 3),
('tiktok_url', '', 'url', 'social', 4),
('tripadvisor_url', 'https://tripadvisor.com/vialuxe', 'url', 'social', 5),

-- Booking
('booking_url', 'https://book.nightsbridge.com/38331', 'url', 'booking', 1),
('booking_cta_text', 'Check Availability — NightsBridge', 'text', 'booking', 2),
('booking_button_text', 'Book Now', 'text', 'booking', 3),

-- Hero
('hero_title', 'The Bush is Calling', 'text', 'hero', 1),
('hero_subtitle', '4-Star Luxury Self-Catering', 'text', 'hero', 2),
('hero_bg_image', 'uploads/hero/hero-main.jpg', 'image', 'hero', 3),

-- Footer
('footer_about', 'Viata Luxe Guesthouse offers award-winning luxury self-catering accommodation in the heart of Limpopo. Perfect for business travelers, weekend getaways, and bushveld adventures.', 'textarea', 'footer', 1),
('footer_copyright', '© 2025 Viata Luxe Guesthouse. All rights reserved.', 'text', 'footer', 2);

-- =====================================================
-- PAGES
-- =====================================================
INSERT INTO pages (id, slug, title, subtitle, meta_title, meta_description, template, is_published, is_homepage, sort_order) VALUES
(1, 'home', 'Home', 'Luxury Self-Catering in Limpopo', 'Viata Luxe Guesthouse — 4-Star Luxury Self-Catering Limpopo', 'Award-winning luxury self-catering guesthouse in Limpopo. Secure parking, jacuzzi, bushveld views. Book direct for best rates.', 'home', 1, 1, 1),
(2, 'accommodation', 'Accommodation', '4 Luxury Apartments', 'Accommodation — Viata Luxe Guesthouse', 'Choose from 4 luxury self-catering apartments in Limpopo. Queen beds, full kitchens, jacuzzi access, secure parking.', 'default', 1, 0, 2),
(3, 'gallery', 'Gallery', 'See Our Spaces', 'Gallery — Viata Luxe Guesthouse', 'Browse photos of our luxury apartments, facilities, and the beautiful Limpopo bushveld.', 'default', 1, 0, 3),
(4, 'safari', 'Safari & Activities', 'Discover Limpopo', 'Safari & Activities — Viata Luxe Guesthouse', 'Game drives, bushveld walks, and Limpopo adventures. Your gateway to South Africa\'s wildlife.', 'default', 1, 0, 4),
(5, 'contact', 'Contact Us', 'Get in Touch', 'Contact — Viata Luxe Guesthouse', 'Contact Viata Luxe Guesthouse for reservations, inquiries, and special requests.', 'default', 1, 0, 5),
(6, 'about', 'About Us', 'Our Story', 'About — Viata Luxe Guesthouse', 'Learn about Viata Luxe Guesthouse, our story, and our commitment to luxury hospitality.', 'default', 1, 0, 6);

-- =====================================================
-- APARTMENTS
-- =====================================================
INSERT INTO apartments (id, page_id, name, slug, subtitle, description, price_per_night, max_guests, room_size_m2, bedrooms, beds_description, hero_image, sort_order) VALUES
(1, 2, 'Bilateral', 'bilateral', 'Symmetry & Serenity',
 'A beautifully appointed 2 sleeper apartment with modern finishes, queen bed, full kitchen, and private balcony overlooking the bushveld. Perfect for couples seeking a romantic escape.',
 1800.00, 2, 45.0, 1, 'Queen bed 157cm', 'uploads/apartments/bilateral-hero.jpg', 1),
(2, 2, 'Classic', 'classic', 'Timeless Elegance',
 'Our Classic apartment offers sophisticated comfort with a queen bed, ensuite bathroom, fully equipped kitchen, and stunning bushveld views. Ideal for business travelers.',
 2200.00, 2, 55.0, 1, 'Queen bed 157cm', 'uploads/apartments/classic-hero.jpg', 2),
(3, 2, 'Comfort', 'comfort', 'Relaxed Luxury',
 'Spacious 2 sleeper with separate living area, queen bed, full kitchen with dishwasher, and private patio. The perfect blend of comfort and style.',
 2500.00, 2, 65.0, 1, 'Queen bed 157cm', 'uploads/apartments/comfort-hero.jpg', 3),
(4, 2, 'Deluxe', 'deluxe', 'Premium Experience',
 'Our finest apartment featuring premium finishes, king bed, luxury bathroom with soaking tub, gourmet kitchen, and panoramic bushveld views. The ultimate Limpopo experience.',
 3200.00, 2, 80.0, 1, 'King bed 183cm', 'uploads/apartments/deluxe-hero.jpg', 4);

-- =====================================================
-- APARTMENT IMAGES
-- =====================================================
INSERT INTO apartment_images (apartment_id, image_path, alt_text, caption, sort_order, is_hero) VALUES
-- Bilateral
(1, 'uploads/apartments/bilateral-1.jpg', 'Bilateral apartment bedroom', 'Queen bed with bushveld views', 1, 1),
(1, 'uploads/apartments/bilateral-2.jpg', 'Bilateral apartment kitchen', 'Full modern kitchen', 2, 0),
(1, 'uploads/apartments/bilateral-3.jpg', 'Bilateral apartment bathroom', 'Ensuite bathroom', 3, 0),
-- Classic
(2, 'uploads/apartments/classic-1.jpg', 'Classic apartment bedroom', 'Elegant queen bedroom', 1, 1),
(2, 'uploads/apartments/classic-2.jpg', 'Classic apartment living', 'Open plan living area', 2, 0),
(2, 'uploads/apartments/classic-3.jpg', 'Classic apartment patio', 'Private patio with views', 3, 0),
-- Comfort
(3, 'uploads/apartments/comfort-1.jpg', 'Comfort apartment bedroom', 'Spacious queen bedroom', 1, 1),
(3, 'uploads/apartments/comfort-2.jpg', 'Comfort apartment kitchen', 'Gourmet kitchen', 2, 0),
(3, 'uploads/apartments/comfort-3.jpg', 'Comfort apartment lounge', 'Separate living area', 3, 0),
-- Deluxe
(4, 'uploads/apartments/deluxe-1.jpg', 'Deluxe apartment bedroom', 'King bed with premium linens', 1, 1),
(4, 'uploads/apartments/deluxe-2.jpg', 'Deluxe apartment bathroom', 'Luxury soaking tub', 2, 0),
(4, 'uploads/apartments/deluxe-3.jpg', 'Deluxe apartment panorama', 'Panoramic bushveld views', 3, 0);

-- =====================================================
-- APARTMENT AMENITIES
-- =====================================================
INSERT INTO apartment_amenities (apartment_id, amenity_name, amenity_icon, sort_order) VALUES
-- Common amenities for all
(1, 'Free WiFi', 'wifi', 1),
(1, 'DStv', 'tv', 2),
(1, 'Full Kitchen', 'kitchen', 3),
(1, 'Secure Parking', 'car', 4),
(1, 'Jacuzzi Access', 'hot-tub', 5),
(1, 'Air Conditioning', 'snowflake', 6),
(1, 'Private Balcony', 'balcony', 7),

(2, 'Free WiFi', 'wifi', 1),
(2, 'DStv', 'tv', 2),
(2, 'Full Kitchen', 'kitchen', 3),
(2, 'Secure Parking', 'car', 4),
(2, 'Jacuzzi Access', 'hot-tub', 5),
(2, 'Air Conditioning', 'snowflake', 6),
(2, 'Ensuite Bathroom', 'bath', 7),

(3, 'Free WiFi', 'wifi', 1),
(3, 'DStv', 'tv', 2),
(3, 'Full Kitchen', 'kitchen', 3),
(3, 'Secure Parking', 'car', 4),
(3, 'Jacuzzi Access', 'hot-tub', 5),
(3, 'Air Conditioning', 'snowflake', 6),
(3, 'Dishwasher', 'dishwasher', 7),
(3, 'Private Patio', 'patio', 8),

(4, 'Free WiFi', 'wifi', 1),
(4, 'DStv', 'tv', 2),
(4, 'Gourmet Kitchen', 'kitchen', 3),
(4, 'Secure Parking', 'car', 4),
(4, 'Jacuzzi Access', 'hot-tub', 5),
(4, 'Air Conditioning', 'snowflake', 6),
(4, 'Soaking Tub', 'bath', 7),
(4, 'Panoramic Views', 'mountain', 8),
(4, 'Premium Linens', 'bed', 9);

-- =====================================================
-- SECTIONS — HOME PAGE
-- =====================================================
INSERT INTO sections (page_id, section_type, title, subtitle, content, image, link_url, link_text, sort_order) VALUES
-- Hero
(1, 'hero', 'The Bush is Calling', '4-Star Luxury Self-Catering', NULL, 'uploads/hero/hero-main.jpg', NULL, NULL, 1),

-- Stats
(1, 'stats', 'Why Viata Luxe?', NULL, '[{"value":"4","label":"Luxury Apartments"},{"value":"1800","label":"From Per Night"},{"value":"100","label":"5-Star Reviews"},{"value":"24","label":"Hour Support"}]', NULL, NULL, NULL, 2),

-- Image + Text
(1, 'image-text', 'Your Limpopo Escape Awaits', 'Award-Winning Guesthouse', '<p>Viata Luxe Guesthouse offers the perfect blend of luxury and nature. Each apartment is designed for comfort, with modern amenities and breathtaking bushveld views.</p><p>Whether you''re here for business or pleasure, our dedicated team ensures your stay is nothing short of exceptional.</p>', 'uploads/about/guesthouse-exterior.jpg', '/accommodation', 'View Apartments', 3),

-- Gallery
(1, 'gallery', 'Gallery', 'A Visual Journey', NULL, NULL, '/gallery', 'View Full Gallery', 4),

-- Testimonials
(1, 'testimonials', 'Guest Voices', 'What Our Guests Say', 'From around the world, our guests share their Viata Luxe experience.', NULL, NULL, NULL, 5),

-- Booking CTA
(1, 'booking-cta', 'Book Your Stay', 'Experience Luxury', 'Self-catering from R1,800 per night. Secure parking. Jacuzzi. Bushveld views.', NULL, NULL, NULL, 6),

-- Safari Teaser
(1, 'safari-teaser', 'Safari & Activities', 'Discover Limpopo', 'Game drives, bushveld walks, and Limpopo adventures await.', 'uploads/safari/safari-hero.jpg', '/safari', 'Explore Safari', 7),

-- Pricing
(1, 'pricing', 'Our Apartments', '4 Luxury Options', 'Choose from our range of luxury self-catering apartments.', NULL, NULL, NULL, 8),

-- Specials
(1, 'specials', 'Summer Special', 'Book Direct & Save', '10% off when you book direct. Use code VIATA10.', NULL, 'https://book.nightsbridge.com/38331', 'Claim Offer', 9);

-- =====================================================
-- SECTIONS — ACCOMMODATION PAGE
-- =====================================================
INSERT INTO sections (page_id, section_type, title, subtitle, content, image, link_url, link_text, sort_order) VALUES
(2, 'hero', 'Accommodation', 'Luxury Self-Catering Apartments', NULL, 'uploads/hero/accommodation-hero.jpg', NULL, NULL, 1),
(2, 'apartment-cards', 'Our Apartments', '4 Luxury Options', 'Each apartment offers a unique experience with premium finishes and bushveld views.', NULL, NULL, NULL, 2),
(2, 'stats', 'Every Apartment Includes', NULL, '[{"value":"100%","label":"Free WiFi"},{"value":"24/7","label":"Security"},{"value":"Free","label":"Parking"},{"value":"All","label":"Full Kitchen"}]', NULL, NULL, NULL, 3),
(2, 'booking-cta', 'Reserve Your Apartment', 'Instant Confirmation', 'Book online for instant confirmation. Best rates guaranteed.', NULL, NULL, NULL, 4);

-- =====================================================
-- SECTIONS — GALLERY PAGE
-- =====================================================
INSERT INTO sections (page_id, section_type, title, subtitle, content, image, link_url, link_text, sort_order) VALUES
(3, 'hero', 'Gallery', 'A Visual Journey', NULL, 'uploads/hero/gallery-hero.jpg', NULL, NULL, 1),
(3, 'gallery', 'Our Spaces', 'Browse Photos', NULL, NULL, NULL, NULL, 2);

-- =====================================================
-- SECTIONS — SAFARI PAGE
-- =====================================================
INSERT INTO sections (page_id, section_type, title, subtitle, content, image, link_url, link_text, sort_order) VALUES
(4, 'hero', 'Safari & Activities', 'Discover Limpopo', NULL, 'uploads/hero/safari-hero.jpg', NULL, NULL, 1),
(4, 'safari-teaser', 'Game Drives', 'Big 5 Encounters', 'Experience the thrill of seeing elephants, lions, leopards, rhinos, and buffalo in their natural habitat.', 'uploads/safari/game-drive.jpg', NULL, NULL, 2),
(4, 'image-text', 'Bushveld Walks', 'Guided Nature Trails', 'Explore the Limpopo bushveld on foot with our expert guides. Discover hidden waterfalls, ancient trees, and incredible birdlife.', 'uploads/safari/bushwalk.jpg', NULL, NULL, 3),
(4, 'specials', 'Safari Packages', 'All-Inclusive Options', 'Combine your stay with curated safari experiences. Game drives, bush walks, and cultural tours.', NULL, NULL, NULL, 4);

-- =====================================================
-- SECTIONS — CONTACT PAGE
-- =====================================================
INSERT INTO sections (page_id, section_type, title, subtitle, content, image, link_url, link_text, sort_order) VALUES
(5, 'hero', 'Contact Us', 'Get in Touch', NULL, 'uploads/hero/contact-hero.jpg', NULL, NULL, 1),
(5, 'text-content', 'We''d Love to Hear From You', 'Reach Out', '<p>Whether you have a question about availability, need help planning your stay, or want to arrange a special request, our team is here to help.</p><p><strong>Email:</strong> reservations@vialuxe.co.za</p><p><strong>Phone:</strong> +27 82 000 0000</p><p><strong>WhatsApp:</strong> +27 82 000 0000</p>', NULL, NULL, NULL, 2),
(5, 'contact-form', 'Send us a message', 'We reply within hours', NULL, NULL, NULL, NULL, 3),
(2, 'faqs', 'Frequently Asked Questions', 'Everything you need to know', NULL, NULL, NULL, NULL, 5);

-- =====================================================
-- SECTIONS — ABOUT PAGE
-- =====================================================
INSERT INTO sections (page_id, section_type, title, subtitle, content, image, link_url, link_text, sort_order) VALUES
(6, 'hero', 'About Us', 'Our Story', NULL, 'uploads/hero/about-hero.jpg', NULL, NULL, 1),
(6, 'image-text', 'The Viata Luxe Difference', 'Since 2018', '<p>Viata Luxe Guesthouse was born from a passion for hospitality and a love of the Limpopo bushveld. What started as a vision to create the perfect escape has grown into an award-winning guesthouse.</p><p>Our commitment to excellence has earned us recognition as one of Limpopo''s top accommodation providers, with hundreds of 5-star reviews from guests around the world.</p>', 'uploads/about/founder.jpg', NULL, NULL, 2),
(6, 'stats', 'Our Achievements', NULL, '[{"value":"2018","label":"Established"},{"value":"4.9","label":"Google Rating"},{"value":"500+","label":"Happy Guests"},{"value":"4","label":"Luxury Apartments"}]', NULL, NULL, NULL, 3);

-- =====================================================
-- SECTION ORIENTATION
-- =====================================================
INSERT INTO section_orientation (section_id, layout, background_color, text_color, padding_top, padding_bottom, alignment, animation) VALUES
(1, 'full-width', NULL, NULL, '0', '0', 'center', 'fade-up'),
(2, 'centered', NULL, NULL, '4rem', '4rem', 'center', 'fade-up'),
(3, 'text-right', NULL, NULL, '4rem', '4rem', 'left', 'fade-up'),
(4, 'full-width', NULL, NULL, '4rem', '4rem', 'center', 'fade-up'),
(5, 'centered', NULL, NULL, '4rem', '4rem', 'center', 'fade-up'),
(6, 'full-width', '#0B1D33', '#F8F6F1', '5rem', '5rem', 'center', 'fade-up'),
(7, 'text-left', NULL, NULL, '4rem', '4rem', 'left', 'fade-up'),
(8, 'centered', NULL, NULL, '4rem', '4rem', 'center', 'fade-up'),
(9, 'full-width', '#C9A84C', '#0B1D33', '2rem', '2rem', 'center', 'slide-left'),
(10, 'full-width', NULL, NULL, '0', '0', 'center', 'fade-up'),
(11, 'centered', NULL, NULL, '4rem', '4rem', 'center', 'fade-up'),
(12, 'centered', NULL, NULL, '4rem', '4rem', 'center', 'fade-up'),
(13, 'full-width', '#0B1D33', '#F8F6F1', '5rem', '5rem', 'center', 'fade-up'),
(14, 'full-width', NULL, NULL, '0', '0', 'center', 'fade-up'),
(15, 'centered', NULL, NULL, '4rem', '4rem', 'center', 'fade-up'),
(16, 'full-width', NULL, NULL, '0', '0', 'center', 'fade-up'),
(17, 'text-left', NULL, NULL, '4rem', '4rem', 'left', 'fade-up'),
(18, 'centered', NULL, NULL, '4rem', '4rem', 'center', 'fade-up'),
(19, 'full-width', '#0B1D33', '#F8F6F1', '5rem', '5rem', 'center', 'fade-up'),
(20, 'full-width', NULL, NULL, '0', '0', 'center', 'fade-up'),
(21, 'centered', NULL, NULL, '4rem', '4rem', 'center', 'fade-up'),
(22, 'text-left', NULL, NULL, '4rem', '4rem', 'left', 'fade-up'),
(23, 'centered', NULL, NULL, '4rem', '4rem', 'center', 'fade-up'),
(24, 'full-width', '#0B1D33', '#F8F6F1', '5rem', '5rem', 'center', 'fade-up'),
(25, 'centered', NULL, NULL, '4rem', '4rem', 'left', 'fade-up'),
(26, 'centered', NULL, NULL, '4rem', '4rem', 'left', 'fade-up');

-- =====================================================
-- TESTIMONIALS
-- =====================================================
INSERT INTO testimonials (apartment_id, reviewer_name, review_text, rating, source, is_featured, sort_order) VALUES
(NULL, 'Sarah M.', 'Absolutely stunning! The bushveld views from our balcony were breathtaking. The apartment was spotless and had everything we needed. Will definitely be back!', 5, 'google', 1, 1),
(NULL, 'James K.', 'Perfect weekend getaway. The jacuzzi under the stars was magical. Staff went above and beyond to make our stay special.', 5, 'google', 1, 2),
(NULL, 'Priya N.', 'Best accommodation in Limpopo! The kitchen was fully equipped, the bed was heavenly, and the views are unmatched. Highly recommend!', 5, 'tripadvisor', 1, 3),
(NULL, 'David L.', 'We stayed in the Deluxe apartment for our anniversary. Worth every rand. The soaking tub, the views, the attention to detail — perfection.', 5, 'direct', 1, 4),
(NULL, 'Michelle T.', 'Business trip made enjoyable. Fast WiFi, comfortable workspace, and the bushveld views helped me unwind after meetings. Excellent value.', 4, 'google', 1, 5),
(NULL, 'Chen W.', 'A hidden gem! We''ve traveled extensively and Viata Luxe rivals any 5-star hotel. The self-catering setup is brilliant — all the convenience of home with luxury finishes.', 5, 'booking.com', 1, 6);

-- =====================================================
-- GALLERY CATEGORIES
-- =====================================================
INSERT INTO gallery_categories (name, slug, description, sort_order) VALUES
('Apartments', 'apartments', 'Interior shots of our luxury apartments', 1),
('Facilities', 'facilities', 'Guesthouse amenities and common areas', 2),
('Bushveld', 'bushveld', 'The beautiful Limpopo landscape', 3),
('Safari', 'safari', 'Wildlife and game drive experiences', 4);

-- =====================================================
-- GALLERY IMAGES
-- =====================================================
INSERT INTO gallery_images (category_id, image_path, alt_text, caption, sort_order) VALUES
-- Apartments
(1, 'uploads/gallery/apt-bilateral-1.jpg', 'Bilateral apartment bedroom', 'Bilateral — Queen bedroom', 1),
(1, 'uploads/gallery/apt-classic-1.jpg', 'Classic apartment', 'Classic — Elegant living', 2),
(1, 'uploads/gallery/apt-comfort-1.jpg', 'Comfort apartment kitchen', 'Comfort — Gourmet kitchen', 3),
(1, 'uploads/gallery/apt-deluxe-1.jpg', 'Deluxe apartment', 'Deluxe — King suite', 4),
(1, 'uploads/gallery/apt-balcony-1.jpg', 'Balcony views', 'Morning coffee with bushveld views', 5),
(1, 'uploads/gallery/apt-kitchen-1.jpg', 'Modern kitchen', 'Full equipped modern kitchen', 6),
-- Facilities
(2, 'uploads/gallery/jacuzzi-1.jpg', 'Jacuzzi area', 'Jacuzzi under the stars', 1),
(2, 'uploads/gallery/parking-1.jpg', 'Secure parking', 'Covered secure parking', 2),
(2, 'uploads/gallery/garden-1.jpg', 'Garden area', 'Lush garden setting', 3),
(2, 'uploads/gallery/entrance-1.jpg', 'Guesthouse entrance', 'Welcome to Viata Luxe', 4),
-- Bushveld
(3, 'uploads/gallery/bush-1.jpg', 'Limpopo bushveld', 'Golden hour in the bush', 1),
(3, 'uploads/gallery/bush-2.jpg', 'Sunset views', 'Spectacular Limpopo sunset', 2),
(3, 'uploads/gallery/bush-3.jpg', 'Birdlife', 'Amazing bird watching', 3),
(3, 'uploads/gallery/bush-4.jpg', 'Trees', 'Ancient bushveld trees', 4),
-- Safari
(4, 'uploads/gallery/safari-1.jpg', 'Elephant', 'Elephant at waterhole', 1),
(4, 'uploads/gallery/safari-2.jpg', 'Lion', 'Lion pride at sunset', 2),
(4, 'uploads/gallery/safari-3.jpg', 'Giraffe', 'Giraffe in the bush', 3),
(4, 'uploads/gallery/safari-4.jpg', 'Game drive', 'Game drive experience', 4);

-- =====================================================
-- NAVIGATION
-- =====================================================
INSERT INTO navigation (label, url, page_id, parent_id, sort_order, open_in_new_tab) VALUES
('Home', NULL, 1, NULL, 1, 0),
('Accommodation', NULL, 2, NULL, 2, 0),
('Gallery', NULL, 3, NULL, 3, 0),
('Safari', NULL, 4, NULL, 4, 0),
('Contact', NULL, 5, NULL, 5, 0),
('About', NULL, 6, NULL, 6, 0),
('Book Now', 'https://book.nightsbridge.com/38331', NULL, NULL, 7, 1);

-- =====================================================
-- SAFARI ACTIVITIES
-- =====================================================
INSERT INTO safari_activities (title, content, image, video_urls, link_url, link_text, sort_order) VALUES
('Game Drives', 'Experience the thrill of seeing the Big 5 in their natural habitat. Our guided game drives take you deep into the Limpopo bushveld for unforgettable wildlife encounters.', 'uploads/safari/game-drive.jpg', '["https://youtube.com/watch?v=example1"]', NULL, 'Book Drive', 1),
('Bushveld Walks', 'Explore the Limpopo bushveld on foot with our expert guides. Discover hidden waterfalls, ancient trees, and incredible birdlife up close.', 'uploads/safari/bushwalk.jpg', '["https://youtube.com/watch?v=example2"]', NULL, 'Learn More', 2),
('Cultural Tours', 'Immerse yourself in local culture with visits to nearby communities, craft markets, and historical sites. Learn about the rich heritage of Limpopo.', 'uploads/safari/cultural.jpg', NULL, NULL, 'Discover', 3),
('Bird Watching', 'Limpopo is a birdwatcher''s paradise with over 500 species. Our guides know the best spots to spot rare and endemic species.', 'uploads/safari/birding.jpg', NULL, NULL, 'Start Birding', 4);

-- =====================================================
-- FAQS
-- =====================================================
INSERT INTO faqs (page_id, question, answer, sort_order) VALUES
(2, 'What time is check-in and check-out?', 'Check-in is from 14:00 (2pm) and check-out is by 11:00 (11am). Early check-in and late check-out may be arranged subject to availability.', 1),
(2, 'Is breakfast included?', 'Our apartments are self-catering with fully equipped kitchens. Breakfast hampers can be arranged on request at an additional cost.', 2),
(2, 'Do you accept children?', 'Yes, children of all ages are welcome. The Deluxe apartment is particularly suitable for families with its spacious layout.', 3),
(2, 'Is there WiFi?', 'Yes, complimentary high-speed WiFi is available throughout the guesthouse.', 4),
(2, 'Can I bring my pet?', 'Unfortunately, pets are not permitted due to the bushveld environment and wildlife.', 5),
(2, 'What is your cancellation policy?', 'Free cancellation up to 7 days before arrival. Cancellations within 7 days are subject to a 50% charge. No-shows are charged in full.', 6),
(2, 'Is parking secure?', 'Yes, we offer covered, secure parking for all guests at no additional charge.', 7),
(2, 'Do you offer airport transfers?', 'Airport transfers can be arranged on request. Please contact us for pricing and availability.', 8),
(NULL, 'How do I book?', 'You can book directly through our website using the NightsBridge booking system, or contact us via email or WhatsApp for assistance.', 9),
(NULL, 'What payment methods do you accept?', 'We accept EFT, credit cards (Visa, Mastercard), and instant payment. A 50% deposit is required to confirm your booking.', 10);

-- =====================================================
-- ADMIN USER (default admin account)
-- Username: admin
-- Password: ViataLuxe2025!
-- =====================================================
INSERT INTO admin_users (username, email, password_hash, full_name, role) VALUES
('admin', 'admin@vialuxe.co.za', '$2y$12$mJV2MdMeu/touUp2skB/H.6C/hXmxwcm0MxE0KzHYlsKXaki5Xx/y', 'System Administrator', 'admin');

-- =====================================================
-- PAGE SEO
-- =====================================================
INSERT INTO page_seo (page_id, schema_type, schema_json, additional_meta) VALUES
(1, 'WebPage', '{"@context":"https://schema.org","@type":"WebPage","name":"Viata Luxe Guesthouse","description":"Award-winning luxury self-catering guesthouse in Limpopo","url":"https://vialuxe.co.za"}', '{"og:type":"website","og:site_name":"Viata Luxe Guesthouse"}'),
(2, 'WebPage', '{"@context":"https://schema.org","@type":"WebPage","name":"Accommodation","description":"4 luxury self-catering apartments in Limpopo"}', NULL),
(3, 'WebPage', '{"@context":"https://schema.org","@type":"WebPage","name":"Gallery","description":"Photos of Viata Luxe Guesthouse apartments and facilities"}', NULL),
(4, 'WebPage', '{"@context":"https://schema.org","@type":"WebPage","name":"Safari & Activities","description":"Game drives and bushveld experiences in Limpopo"}', NULL),
(5, 'ContactPage', '{"@context":"https://schema.org","@type":"ContactPage","name":"Contact Us","description":"Contact Viata Luxe Guesthouse"}', NULL),
(6, 'AboutPage', '{"@context":"https://schema.org","@type":"AboutPage","name":"About Us","description":"The story behind Viata Luxe Guesthouse"}', NULL);
