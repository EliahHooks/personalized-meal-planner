-- Clear existing data
DELETE FROM UserMeals;
DELETE FROM Foods;
DELETE FROM Users;
DELETE FROM UserLog;

-- Insert user1 with password "password"
INSERT INTO Users (userName, password, height, weight, email, role)
VALUES (
    'user1',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    72, 150, 'johndoe@example.com', 'user'
);

-- Insert user2 with password "password"
INSERT INTO Users (userName, password, role)
VALUES (
    'user2',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'user'
);

-- Insert admin with password "password"
INSERT INTO Users (userName, password, email, role)
VALUES (
    'admin',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'admin1@example.com',
    'admin'
);

-- Insert 2 foods into the food table: chicken and spinach
INSERT INTO Foods 
(name, category, serving_amount, serving_unit, calories_per_serving, protein_grams, sodium_mg, sugar_grams, carbs_grams, fat_grams, fiber_grams)
VALUES
('Chicken Breast', 'protein', 4.00, 'oz', 185, 35.00, 74, 0.00, 0.00, 4.00, 0.00),
('Spinach', 'vegetable', 1.00, 'cup', 7, 0.90, 24, 0.10, 1.10, 0.10, 0.70),
('Water', 'beverage', 8.00, 'fl oz', 0, 0.00, 0, 0.00, 0.00, 0.00, 0.00),
('Coca-Cola', 'beverage', 12.00, 'fl oz', 140, 0.00, 45, 39.00, 39.00, 0.00, 0.00),
('Toast', 'grain', 1.00, 'slice', 75, 2.00, 130, 1.00, 14.00, 1.00, 1.00),
('Eggs', 'protein', 1.00, 'large', 70, 6.00, 70, 0.00, 1.00, 5.00, 0.00),
('Biscuit', 'grain', 1.00, 'medium', 180, 3.00, 440, 2.00, 20.00, 8.00, 1.00);

-- Insert a meal for user1 (assumes Chicken ID = 1, Spinach ID = 2)
INSERT INTO UserMeals (userID, mealName, mealType, entreeID, side1ID)
VALUES (1, 'Chicken & Spinach Lunch', 'lunch', 1, 2);


INSERT INTO Users (userName, password, height, weight, email, role, created_at) VALUES
('alice01', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 65.2, 120.5, 'alice01@example.com', 'user', DATE_SUB(CURDATE(), INTERVAL 3 DAY)),
('bob92', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 70.1, 190.3, 'bob92@example.com', 'user', DATE_SUB(CURDATE(), INTERVAL 15 DAY)),
('csmith', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 68.5, 155.0, 'csmith@example.com', 'user', DATE_SUB(CURDATE(), INTERVAL 30 DAY)),
('danielk', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 72.3, 200.2, 'danielk@example.com', 'user', DATE_SUB(CURDATE(), INTERVAL 21 DAY)),
('emilyt', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 63.8, 132.0, 'emilyt@example.com', 'user', DATE_SUB(CURDATE(), INTERVAL 9 DAY)),
('franklin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 74.1, 210.7, 'franklin@example.com', 'user', DATE_SUB(CURDATE(), INTERVAL 40 DAY)),
('grace33', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 66.5, 125.4, 'grace33@example.com', 'user', DATE_SUB(CURDATE(), INTERVAL 6 DAY)),
('haroldv', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 69.9, 180.0, 'haroldv@example.com', 'user', DATE_SUB(CURDATE(), INTERVAL 18 DAY)),
('isabel99', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 64.2, 115.3, 'isabel99@example.com', 'user', DATE_SUB(CURDATE(), INTERVAL 2 DAY)),
('jackie8', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 71.0, 170.5, 'jackie8@example.com', 'user', DATE_SUB(CURDATE(), INTERVAL 25 DAY)),
('kevinm', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 69.0, 165.5, 'kevinm@example.com', 'user', DATE_SUB(CURDATE(), INTERVAL 12 DAY)),
('lucy77', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 67.4, 130.1, 'lucy77@example.com', 'user', DATE_SUB(CURDATE(), INTERVAL 4 DAY)),
('mikec', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 73.2, 200.0, 'mikec@example.com', 'user', DATE_SUB(CURDATE(), INTERVAL 29 DAY)),
('natalie', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 62.5, 122.3, 'natalie@example.com', 'user', DATE_SUB(CURDATE(), INTERVAL 11 DAY)),
('owenx', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 75.0, 215.8, 'owenx@example.com', 'user', DATE_SUB(CURDATE(), INTERVAL 5 DAY)),
('paula4', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 65.7, 128.2, 'paula4@example.com', 'user', DATE_SUB(CURDATE(), INTERVAL 26 DAY)),
('quentin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 70.2, 175.0, 'quentin@example.com', 'user', DATE_SUB(CURDATE(), INTERVAL 35 DAY)),
('rachel', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 64.0, 119.8, 'rachel@example.com', 'user', DATE_SUB(CURDATE(), INTERVAL 10 DAY)),
('stevenq', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 72.9, 195.1, 'stevenq@example.com', 'user', DATE_SUB(CURDATE(), INTERVAL 20 DAY)),
('tammyk', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 66.9, 140.4, 'tammyk@example.com', 'user', DATE_SUB(CURDATE(), INTERVAL 7 DAY)),
('ulysses', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 74.4, 208.0, 'ulysses@example.com', 'user', DATE_SUB(CURDATE(), INTERVAL 8 DAY)),
('valerie', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 62.9, 117.0, 'valerie@example.com', 'user', DATE_SUB(CURDATE(), INTERVAL 31 DAY)),
('william9', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 71.4, 185.5, 'william9@example.com', 'user', DATE_SUB(CURDATE(), INTERVAL 27 DAY)),
('ximena3', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 65.3, 123.9, 'ximena3@example.com', 'user', DATE_SUB(CURDATE(), INTERVAL 13 DAY)),
('yosef4', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 68.1, 169.7, 'yosef4@example.com', 'user', DATE_SUB(CURDATE(), INTERVAL 17 DAY));

-- Insert sample meals for users (entreeID, side1ID, side2ID, drinkID use IDs 1–7)
INSERT INTO UserMeals (userID, mealName, mealType, entreeID, side1ID, side2ID, drinkID) VALUES
(2, 'Egg Toast Combo', 'breakfast', 6, 5, NULL, 3),
(3, 'Power Lunch', 'lunch', 1, 2, 4, 4),
(3, 'Sweet Dinner', 'dinner', 4, 5, NULL, 4),
(4, 'Protein Start', 'breakfast', 6, NULL, NULL, 3),
(5, 'Veggie Plate', 'lunch', 2, 4, 5, 3),
(6, 'Light Lunch', 'lunch', 6, 4, NULL, 3),
(7, 'Biscuit Breakfast', 'breakfast', 7, 5, NULL, 4),
(7, 'Full Meal', 'dinner', 1, 2, 4, 6),
(8, 'Grain Bowl', 'lunch', 5, 2, NULL, 3),
(9, 'Quick Meal', 'dinner', 1, 4, NULL, 2),
(10, 'Morning Start', 'breakfast', 6, NULL, NULL, 3),
(10, 'Energy Lunch', 'lunch', 1, 2, 5, 4),
(11, 'Healthy Combo', 'dinner', 1, 2, 4, 4),
(12, 'Fiber Meal', 'lunch', 2, 5, NULL, 3),
(13, 'Classic Combo', 'dinner', 6, 5, NULL, 3),
(14, 'Simple Breakfast', 'breakfast', 6, NULL, NULL, 3),
(15, 'Chicken Biscuit', 'lunch', 1, 7, NULL, 4),
(15, 'Egg Toast', 'breakfast', 6, 5, NULL, 3),
(16, 'Evening Meal', 'dinner', 1, 2, 4, 4),
(17, 'Spinach Starter', 'breakfast', 2, NULL, NULL, 3),
(18, 'Quick Bite', 'lunch', 1, 5, NULL, 3),
(19, 'Dinner Boost', 'dinner', 6, 2, 4, 3),
(20, 'Balanced Meal', 'lunch', 1, 2, 5, 4),
(21, 'Classic Breakfast', 'breakfast', 6, NULL, NULL, 3),
(21, 'Light Lunch', 'lunch', 1, 2, 4, 3),
(22, 'Morning Energy', 'breakfast', 6, 5, NULL, 3),
(23, 'Dinner Combo', 'dinner', 1, 2, 4, 4),
(24, 'Simple Lunch', 'lunch', 6, 5, NULL, 3),
(25, 'Night Fuel', 'dinner', 1, 4, 5, 2);
