-- Fullstack Developer Assessment - SQL Schema & Seed Data
-- Matches the exact table names and column structures from the Python API assessment

-- 1. Create Tables
CREATE TABLE IF NOT EXISTS menu_names (
    menu_id INT PRIMARY KEY,
    menu_name VARCHAR(50) NOT NULL
);

CREATE TABLE IF NOT EXISTS categories (
    cat_id INT PRIMARY KEY,
    category_name VARCHAR(100) NOT NULL,
    menu_id INT,
    FOREIGN KEY (menu_id) REFERENCES menu_names(menu_id)
);

CREATE TABLE IF NOT EXISTS menu (
    item_id INT PRIMARY KEY,
    item_name VARCHAR(100) NOT NULL,
    cat_id INT,
    menu_id INT,
    size VARCHAR(50) NULL,
    price VARCHAR(50) NOT NULL,
    FOREIGN KEY (cat_id) REFERENCES categories(cat_id),
    FOREIGN KEY (menu_id) REFERENCES menu_names(menu_id)
);

CREATE TABLE IF NOT EXISTS order_history (
    id INT PRIMARY KEY,
    order_date VARCHAR(50) NOT NULL,
    order_id INT NOT NULL,
    item_id INT NOT NULL,
    size VARCHAR(50) NULL,
    price VARCHAR(50) NOT NULL,
    qty INT NOT NULL,
    order_status VARCHAR(50) NOT NULL,
    total VARCHAR(50) NOT NULL,
    FOREIGN KEY (item_id) REFERENCES menu(item_id)
);

CREATE TABLE IF NOT EXISTS payments (
    id INT PRIMARY KEY,
    payment_date VARCHAR(50) NOT NULL,
    payment_id INT NOT NULL,
    order_id INT NOT NULL,
    amount_due VARCHAR(50) NOT NULL,
    tips VARCHAR(50) NOT NULL,
    discount VARCHAR(50) NOT NULL,
    total_paid VARCHAR(50) NOT NULL,
    payment_type VARCHAR(50) NOT NULL,
    payment_status VARCHAR(50) NOT NULL
);

-- 2. Insert Menu Names
INSERT INTO menu_names (menu_id, menu_name) VALUES 
(1, 'Food'),
(2, 'Drinks');

-- 3. Insert Categories
INSERT INTO categories (cat_id, category_name, menu_id) VALUES 
(1, 'Starters', 1),
(2, 'Soft Drinks', 2),
(3, 'Mains', 1),
(4, 'Desserts', 2),
(5, 'Hot Drinks', 2);

-- 4. Insert Menu Items
INSERT INTO menu (item_id, item_name, cat_id, menu_id, size, price) VALUES 
(1, 'Item1', 1, 1, 'Small, Large', '1.50, 2.50'),
(2, 'Item2', 1, 1, NULL, '3'),
(3, 'Item3', 2, 2, NULL, '2.5'),
(4, 'Item4', 2, 2, NULL, '1.5'),
(5, 'Item5', 2, 1, NULL, '1'),
(6, 'Item6', 3, 1, 'Small, Large', '2.50, 3.6'),
(7, 'Item7', 3, 1, NULL, '2.5'),
(8, 'Item8', 4, 2, 'Small, Large', '3.75, 6.5'),
(9, 'Item9', 4, 2, NULL, '1.5'),
(10, 'Item10', 5, 2, NULL, '2');

-- 5. Insert Order History
INSERT INTO order_history (id, order_date, order_id, item_id, size, price, qty, order_status, total) VALUES 
(1, '01 Oct 2025', 10, 2, '', '2.5', 1, 'Completed', '2.5'),
(2, '01 Oct 2025', 10, 3, '', '1.5', 2, 'Completed', '3'),
(3, '01 Oct 2025', 10, 1, 'Small', '3.75', 1, 'Completed', '3.75'),
(4, '01 Oct 2025', 11, 5, '', '2.75', 1, 'Completed', '2.75'),
(5, '01 Oct 2025', 11, 6, '', '1.75', 2, 'Completed', '3.5'),
(6, '01 Oct 2025', 11, 2, '', '2.5', 1, 'Completed', '2.5'),
(7, '01 Oct 2025', 11, 3, '', '3.5', 1, 'Completed', '3.5'),
(8, '01 Oct 2025', 11, 4, '', '3.75', 2, 'Completed', '7.5'),
(9, '01 Oct 2025', 11, 5, '', '1.5', 1, 'Completed', '1.5'),
(10, '01 Oct 2025', 12, 6, 'Large', '5.5', 2, 'Completed', '11'),
(11, '01 Oct 2025', 12, 7, '', '2.5', 1, 'Completed', '2.5'),
(12, '01 Oct 2025', 12, 1, 'Large', '3.5', 1, 'Completed', '3.5'),
(13, '01 Oct 2025', 13, 1, 'Small', '2.75', 2, 'Completed', '5.5'),
(14, '01 Oct 2025', 13, 6, 'Small', '1.5', 1, 'Completed', '1.5'),
(15, '01 Oct 2025', 13, 8, 'Small', '3.5', 1, 'Completed', '3.5'),
(16, '01 Oct 2025', 13, 1, 'Small', '2.5', 2, 'Completed', '5'),
(17, '01 Oct 2025', 14, 6, 'Large', '2.75', 1, 'Completed', '2.75'),
(18, '01 Oct 2025', 14, 1, 'Large', '2.75655', 2, 'Completed', '5.5131'),
(19, '01 Oct 2025', 14, 8, 'Large', '2.75', 2, 'Completed', '5.5'),
(20, '01 Oct 2025', 14, 1, 'Large', '2.7556', 2, 'Completed', '5.5112'),
(21, '01 Oct 2025', 14, 4, '', '5.5', 1, 'Completed', '5.5'),
(22, '01 Oct 2025', 14, 3, '', '2.75', 2, 'Completed', '5.5'),
(23, '01 Oct 2025', 14, 2, '', '3.5', 1, 'Completed', '3.5'),
(24, '01 Oct 2025', 14, 6, 'Large', '3.015', 3, 'Completed', '9.045'),
(25, '02 Oct 2025', 15, 2, '', '2.568', 2, 'Completed', '5.136'),
(26, '03 Oct 2025', 16, 6, 'Large', '6.586', 3, 'Completed', '19.758'),
(27, '01 Oct 2025', 17, 10, '', '2.5', 1, 'Completed', '2.5'),
(28, '01 Oct 2025', 17, 9, '', '2.75636', 1, 'Completed', '2.75636'),
(29, '01 Oct 2025', 17, 7, '', '5.63982', 1, 'Completed', '5.63982'),
(30, '05 Oct 2025', 18, 1, 'Small', '2.5698', 2, 'Completed', '5.1396'),
(31, '05 Oct 2025', 18, 6, 'Small', '5.36245', 2, 'Completed', '10.7249'),
(32, '05 Oct 2025', 18, 8, 'Small', '5.23569', 2, 'Completed', '10.47138'),
(33, '01 Oct 2025', 19, 2, '', '2.75698', 1, 'Completed', '2.75698'),
(34, '01 Oct 2025', 19, 4, '', '2.356', 1, 'Completed', '2.356'),
(35, '01 Oct 2025', 19, 5, '', '2.457', 2, 'Completed', '4.914'),
(36, '01 Oct 2025', 19, 7, '', '2.6359', 1, 'Completed', '2.6359'),
(37, '01 Oct 2025', 19, 9, '', '6.523', 1, 'Completed', '6.523'),
(38, '01 Oct 2025', 19, 10, '', '8.5412', 3, 'Completed', '25.6236'),
(39, '01 Oct 2025', 19, 6, 'Large', '5.683', 2, 'Completed', '11.366'),
-- Note: ID 40 is missing from the table list
(41, '01 Oct 2025', 19, 2, '', '6.3564', 1, 'Completed', '6.3564'),
(42, '01 Oct 2025', 19, 5, '', '7.235', 1, 'Completed', '7.235'),
(43, '01 Oct 2025', 19, 7, '', '2.365', 1, 'Completed', '2.365'),
(44, '01 Oct 2025', 20, 1, 'Large', '2.3658', 1, 'Completed', '2.3658'),
(45, '01 Oct 2025', 20, 3, '', '2.356', 1, 'Completed', '2.356'),
(46, '01 Oct 2025', 20, 6, 'Large', '1.256', 1, 'Completed', '1.256'),
(47, '01 Oct 2025', 20, 4, '', '2.635', 1, 'Completed', '2.635'),
(48, '01 Oct 2025', 20, 5, '', '5.21', 1, 'Completed', '5.21'),
(49, '01 Oct 2025', 20, 7, '', '6.325', 2, 'Completed', '12.65'),
(50, '01 Oct 2025', 20, 8, 'Small', '7.2514', 1, 'Completed', '7.2514'),
(51, '01 Oct 2025', 20, 9, '', '2.3999', 1, 'Completed', '2.3999'),
(52, '01 Oct 2025', 20, 4, '', '2.356', 3, 'Completed', '7.068'),
(53, '01 Oct 2025', 20, 6, 'Small', '4.5326', 2, 'Completed', '9.0652');

-- 6. Insert Payments
INSERT INTO payments (id, payment_date, payment_id, order_id, amount_due, tips, discount, total_paid, payment_type, payment_status) VALUES 
(1, '01 Oct 2025', 100, 10, '9.25', '0', '0', '9.25', 'Card', 'Completed'),
(2, '01 Oct 2025', 101, 11, '21.25', '0', '0', '10', 'Cash', 'Completed'),
(3, '01 Oct 2025', 102, 11, '21.25', '0', '0', '11.25', 'Card', 'Completed'),
(4, '02 Oct 2025', 103, 12, '17', '3', '4', '16', 'Card', 'Completed'),
(5, '03 Oct 2025', 104, 13, '15.5', '0', '2', '13.5', 'Card', 'Completed'),
(6, '01 Oct 2025', 105, 14, '42.8193', '0', '0', '20', 'Cash', 'Completed'),
(7, '01 Oct 2025', 106, 14, '42.8193', '0', '0', '22.82', 'Card', 'Completed'),
(8, '02 Oct 2025', 107, 15, '5.136', '0', '0', '5.14', 'Card', 'Refunded'),
(9, '03 Oct 2025', 108, 16, '19.758', '0', '0', '10', 'Cash', 'Completed'),
(10, '03 Oct 2025', 109, 16, '19.758', '0', '0', '9.76', 'Card', 'Completed'),
(11, '01 Oct 2025', 110, 17, '10.8918', '0', '0', '10.9', 'Card', 'Completed'),
(12, '05 Oct 2025', 111, 18, '26.33588', '2', '0', '25', 'Cash', 'Completed'),
(13, '05 Oct 2025', 115, 18, '26.33588', '0', '0', '3.34', 'Card', 'Completed'),
(14, '01 Oct 2025', 116, 19, '72.13188', '0', '0', '50', 'Cash', 'Completed'),
(15, '01 Oct 2025', 119, 19, '72.13188', '0', '0', '22.13', 'Card', 'Completed'),
(16, '01 Oct 2025', 120, 20, '52.2573', '0', '0', '25', 'Cash', 'Completed'),
(17, '01 Oct 2025', 121, 20, '52.2573', '0', '0', '27.28', 'Card', 'Completed');
