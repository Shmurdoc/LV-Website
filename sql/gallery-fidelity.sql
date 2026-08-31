USE viata_luxe;

-- Rebuild gallery categories via unified public_categories (entity_type='gallery')
DELETE FROM gallery_images WHERE public_category_id IN (SELECT id FROM public_categories WHERE entity_type = 'gallery');
DELETE FROM public_categories WHERE entity_type = 'gallery';

INSERT INTO public_categories (entity_type, name, slug, description, sort_order, is_published) VALUES
('gallery', 'Luxe Bedrooms',     'bedrooms',  'Elegantly styled bedrooms — warm linen, curated details', 1, 1),
('gallery', 'Kitchens',          'kitchens',  'Fully equipped self-catering kitchens', 2, 1),
('gallery', 'Luxe Bathrooms',    'bathrooms', 'Modern ensuite bathrooms', 3, 1),
('gallery', 'Luxe Living Rooms', 'living',    'Open-plan living spaces with city views', 4, 1),
('gallery', 'Luxe Outdoors',     'outdoors',  'Pool, garden, braai and the Limpopo bushveld', 5, 1);

-- Use last_insert_id() to get the first gallery category ID
SET @gc1 = (SELECT id FROM public_categories WHERE slug = 'bedrooms' AND entity_type = 'gallery');
SET @gc2 = @gc1 + 1;
SET @gc3 = @gc1 + 2;
SET @gc4 = @gc1 + 3;
SET @gc5 = @gc1 + 4;

INSERT INTO gallery_images (public_category_id, image_path, alt_text, caption, sort_order) VALUES
-- Luxe Bedrooms
(@gc1, 'uploads/gallery/apt-bilateral-1.jpg', 'Bilateral apartment bedroom', 'Classic Apartment 1 — queen bedroom', 1),
(@gc1, 'uploads/gallery/apt-deluxe-1.jpg', 'Deluxe apartment bedroom', 'Deluxe Apartment 4 — grand suite', 2),
(@gc1, 'uploads/gallery/bush-3.jpg', 'Bedroom accents', 'Curated bedroom details', 3),
(@gc1, '/Luxury Images/bedrooms/bedroom-chevron-pillows-headboard.jpg', 'Chevron pillows headboard', 'Warm linen, editorial styling', 4),
(@gc1, '/Luxury Images/bedrooms/bedroom-grey-curtains-ac-white-bedding.jpg', 'Grey curtains white bedding', 'Light and airy bedroom', 5),
(@gc2, 'uploads/gallery/apt-kitchen-1.jpg', 'Modern kitchen', 'Fully equipped kitchen', 1),
(@gc2, 'uploads/gallery/apt-comfort-1.jpg', 'Comfort apartment kitchen', 'Gourmet kitchen', 2),
(@gc2, '/Luxury Images/kitchens/kitchen-wood-cabinets-marble-backsplash.jpg', 'Wood cabinets marble', 'Marble backsplash kitchen', 3),
(@gc2, '/Luxury Images/kitchens/kitchen-dining-set-fruits.jpg', 'Kitchen dining with fruits', 'Dining set in kitchen', 4),
(@gc2, '/Luxury Images/kitchens/kitchen-red-fridge-round-table.jpg', 'Round table red fridge', 'Bright kitchen dining', 5),
(@gc3, '/Luxury Images/bathrooms/bathroom-1-sink-toilet-yellow-mat.jpg', 'Bathroom sink yellow mat', 'Modern ensuite', 1),
(@gc3, '/Luxury Images/bathrooms/bathroom-1-shower-glass-toilet.jpg', 'Shower glass toilet', 'Glass shower ensuite', 2),
(@gc3, '/Luxury Images/bathrooms/bathroom-shower-head-closeup.jpg', 'Shower head closeup', 'Premium shower fittings', 3),
(@gc4, '/Luxury Images/living-rooms/living-room-tv-smart-console.jpg', 'Living room TV console', 'Open plan living', 1),
(@gc4, '/Luxury Images/living-rooms/living-room-black-sofas-tv-unit.jpg', 'Black sofas TV unit', 'Comfortable living area', 2),
(@gc4, '/Luxury Images/living-rooms/living-room-brown-sofa-leaf-pillows.jpg', 'Brown sofa leaf pillows', 'Relaxed lounge', 3),
(@gc4, '/Luxury Images/living-rooms/living-room-1-orange-cushions.jpg', 'Orange cushions living', 'Vibrant accent living room', 4),
(@gc5, 'uploads/gallery/pool-1.jpg', 'Swimming Pool area', 'Pool under the stars', 1),
(@gc5, 'uploads/gallery/garden-1.jpg', 'Garden area', 'Lush garden setting', 2),
(@gc5, '/Luxury Images/pool/pool-overview-entertainment-area.jpg', 'Pool entertainment area', 'Serenity by the pool', 3),
(@gc5, '/Luxury Images/pool/pool-overview-gazebo-garden.jpg', 'Pool gazebo garden', 'Outdoor chillers', 4),
(@gc5, '/Luxury Images/gallery-scenic/exterior-grey-cottages-red-doors.jpg', 'Exterior cottages red doors', 'Guesthouse exterior', 5),
(@gc5, 'uploads/gallery/safari-1.jpg', 'Elephant at waterhole', 'Kruger wildlife minutes away', 6),
(@gc5, '/Luxury Images/activities/elephants-river-crossing-herd.jpg', 'Elephants river crossing', 'Bushveld herds', 7),
(@gc5, '/Luxury Images/gallery-scenic/wildlife-buffalo-closeup-herd.jpg', 'Buffalo herd closeup', 'Wildlife on the reserve', 8);
