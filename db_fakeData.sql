-- =========================================
-- USERS
-- =========================================
INSERT INTO users (username, email, password_hash, address, phone) VALUES
('laura_crafts', 'laura@example.com', 'hash123', 'Calle Falsa 123, Madrid', '600123456'),
('pedro_maker', 'pedro@example.com', 'hash123', 'Avenida del Sol 45, Barcelona', '611234567'),
('ana_handmade', 'ana@example.com', 'hash123', 'Calle Mayor 10, Valencia', '622345678'),
('marta_designs', 'marta@example.com', 'hash123', 'Plaza Nueva 7, Sevilla', '633456789'),
('charles_art', 'charles@example.com', 'hash123', 'Calle Luna 22, Bilbao', '644567890');


-- =========================================
-- PRODUCTS (Handmade items with images)
-- =========================================
INSERT INTO products (name, description, price, image_path) VALUES
('Embroidered Tote Bag', 'Hand-embroidered cotton tote bag with floral pattern.', 18.00, 'products/product_tote.webp'),
('Polymer Clay Earrings', 'Lightweight handmade earrings crafted from polymer clay.', 12.50, 'products/product_earrings.webp'),
('Lavender Soy Candle', 'Hand-poured soy candle with natural lavender fragrance.', 9.99, 'products/product_candle.webp'),
('Hand-stitched Notebook A5', 'A5 notebook with kraft cover and handmade stitching.', 7.50, 'products/product_notebook.webp'),
('Macrame Keychain', 'Artisan keychain made with cotton rope using macrame technique.', 6.00, 'products/product_keychain.jpg'),
('Resin Pendant Necklace', 'Minimalist resin pendant with preserved natural flowers inside.', 14.90, 'products/product_pendant.jpg'),
('Hand-painted Ceramic Mug', 'Ceramic mug decorated by hand with permanent enamel paint.', 16.00, 'products/product_mug.jpg'),
('Embroidery DIY Kit', 'Complete DIY embroidery kit including hoop, thread, needles, and pattern.', 20.00, 'products/embroideryKit.jpg');

-- =========================================
-- ORDERS
-- =========================================
INSERT INTO orders (user_id, total) VALUES
(1, 27.99),   -- Laura
(2, 12.50),   -- Pedro
(1, 22.40),   -- Laura
(3, 36.00),   -- Ana
(5, 12.00);   -- Charles

-- =========================================
-- ORDER ITEMS
-- =========================================

-- Order 1 — Laura
INSERT INTO order_items (order_id, product_id, quantity, unit_price) VALUES
(1, 1, 1, 18.00),   -- Tote bag
(1, 5, 1, 6.00),    -- Macrame keychain
(1, 3, 1, 9.99);    -- Lavender candle

-- Order 2 — Pedro
INSERT INTO order_items (order_id, product_id, quantity, unit_price) VALUES
(2, 2, 1, 12.50);   -- Polymer clay earrings

-- Order 3 — Laura
INSERT INTO order_items (order_id, product_id, quantity, unit_price) VALUES
(3, 4, 1, 7.50),    -- Handmade notebook
(3, 6, 1, 14.90);   -- Resin necklace

-- Order 4 — Ana
INSERT INTO order_items (order_id, product_id, quantity, unit_price) VALUES
(4, 8, 1, 20.00),   -- Embroidery kit
(4, 7, 1, 16.00);   -- Hand-painted mug

-- Order 5 — Charles
INSERT INTO order_items (order_id, product_id, quantity, unit_price) VALUES
(5, 5, 2, 6.00);    -- Two macrame keychains

-- =========================================
-- REVIEWS
-- =========================================
INSERT INTO reviews (user_id, product_id, rating, comment, image_path) VALUES
(1, 1, 5, 'Beautiful craftsmanship! The embroidery is perfect.', 'reviews/tote1.jpg'),
(2, 2, 4, 'Very nice earrings, lightweight and comfortable.', NULL),
(3, 3, 5, 'The lavender scent is amazing. Super relaxing.', 'reviews/candle_lavender.png'),
(4, 7, 5, 'Gorgeous mug! The hand-painting looks even better in person.', 'reviews/mug_handpainted.jpg'),
(5, 5, 4, 'Good quality keychain. Nice handmade touch.', NULL);
