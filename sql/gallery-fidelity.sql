USE viata_luxe;

-- Rebuild gallery categories to original 5-category taxonomy
DELETE FROM gallery_images;
DELETE FROM gallery_categories;

INSERT INTO gallery_categories (id, name, slug, description, sort_order) VALUES
(1, 'Luxe Bedrooms',     'bedrooms',  'Elegantly styled bedrooms — warm linen, curated details', 1),
(2, 'Kitchens',          'kitchens',  'Fully equipped self-catering kitchens', 2),
(3, 'Luxe Bathrooms',    'bathrooms', 'Modern ensuite bathrooms', 3),
(4, 'Luxe Living Rooms', 'living',    'Open-plan living spaces with city views', 4),
(5, 'Luxe Outdoors',     'outdoors',  'Pool, garden, braai and the Limpopo bushveld', 5);

INSERT INTO gallery_images (category_id, image_path, alt_text, caption, sort_order) VALUES
-- Luxe Bedrooms
(1, 'uploads/gallery/apt-bilateral-1.jpg', 'Bilateral apartment bedroom', 'Classic Apartment 1 — queen bedroom', 1),
(1, 'uploads/gallery/apt-deluxe-1.jpg', 'Deluxe apartment bedroom', 'Deluxe Apartment 4 — grand suite', 2),
(1, 'uploads/gallery/bush-3.jpg', 'Bedroom accents', 'Curated bedroom details', 3),
(1, '/Luxury Images/bedrooms/bedroom-chevron-pillows-headboard.jpg', 'Chevron pillows headboard', 'Warm linen, editorial styling', 4),
(1, '/Luxury Images/bedrooms/bedroom-grey-curtains-ac-white-bedding.jpg', 'Grey curtains white bedding', 'Light and airy bedroom', 5),
(2, 'uploads/gallery/apt-kitchen-1.jpg', 'Modern kitchen', 'Fully equipped kitchen', 1),
(2, 'uploads/gallery/apt-comfort-1.jpg', 'Comfort apartment kitchen', 'Gourmet kitchen', 2),
(2, '/Luxury Images/kitchens/kitchen-wood-cabinets-marble-backsplash.jpg', 'Wood cabinets marble', 'Marble backsplash kitchen', 3),
(2, '/Luxury Images/kitchens/kitchen-dining-set-fruits.jpg', 'Kitchen dining with fruits', 'Dining set in kitchen', 4),
(2, '/Luxury Images/kitchens/kitchen-red-fridge-round-table.jpg', 'Round table red fridge', 'Bright kitchen dining', 5),
(3, '/Luxury Images/bathrooms/bathroom-1-sink-toilet-yellow-mat.jpg', 'Bathroom sink yellow mat', 'Modern ensuite', 1),
(3, '/Luxury Images/bathrooms/bathroom-1-shower-glass-toilet.jpg', 'Shower glass toilet', 'Glass shower ensuite', 2),
(3, '/Luxury Images/bathrooms/bathroom-shower-head-closeup.jpg', 'Shower head closeup', 'Premium shower fittings', 3),
(4, '/Luxury Images/living-rooms/living-room-tv-smart-console.jpg', 'Living room TV console', 'Open plan living', 1),
(4, '/Luxury Images/living-rooms/living-room-black-sofas-tv-unit.jpg', 'Black sofas TV unit', 'Comfortable living area', 2),
(4, '/Luxury Images/living-rooms/living-room-brown-sofa-leaf-pillows.jpg', 'Brown sofa leaf pillows', 'Relaxed lounge', 3),
(4, '/Luxury Images/living-rooms/living-room-1-orange-cushions.jpg', 'Orange cushions living', 'Vibrant accent living room', 4),
(5, 'uploads/gallery/pool-1.jpg', 'Swimming Pool area', 'Pool under the stars', 1),
(5, 'uploads/gallery/garden-1.jpg', 'Garden area', 'Lush garden setting', 2),
(5, '/Luxury Images/pool/pool-overview-entertainment-area.jpg', 'Pool entertainment area', 'Serenity by the pool', 3),
(5, '/Luxury Images/pool/pool-overview-gazebo-garden.jpg', 'Pool gazebo garden', 'Outdoor chillers', 4),
(5, '/Luxury Images/gallery-scenic/exterior-grey-cottages-red-doors.jpg', 'Exterior cottages red doors', 'Guesthouse exterior', 5),
(5, 'uploads/gallery/safari-1.jpg', 'Elephant at waterhole', 'Kruger wildlife minutes away', 6),
(5, '/Luxury Images/activities/elephants-river-crossing-herd.jpg', 'Elephants river crossing', 'Bushveld herds', 7),
(5, '/Luxury Images/gallery-scenic/wildlife-buffalo-closeup-herd.jpg', 'Buffalo herd closeup', 'Wildlife on the reserve', 8);