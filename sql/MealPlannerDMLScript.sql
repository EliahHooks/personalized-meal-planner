-- Clear existing data
DELETE FROM UserMeals;
DELETE FROM Foods;
DELETE FROM Users;
DELETE FROM UserLog;

-- Insert admin with password "password"
INSERT INTO Users (userName, password, email, role)
VALUES (
    'admin',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'admin1@example.com',
    'admin'
);


INSERT INTO Foods 
(name, category, serving_amount, serving_unit, calories_per_serving, protein_grams, sodium_mg, sugar_grams, carbs_grams, fat_grams, fiber_grams)
VALUES
('Chicken Breast', 'protein', 4.00, 'oz', 185, 35.00, 74, 0.00, 0.00, 4.00, 0.00),
('Eggs', 'protein', 1.00, 'large', 70, 6.00, 70, 0.00, 1.00, 5.00, 0.00),
('Salmon', 'protein', 3.00, 'oz', 180, 22.00, 50, 0.00, 0.00, 10.00, 0.00),
('Tofu', 'protein', 0.50, 'cup', 94, 10.00, 9, 0.00, 2.00, 5.00, 1.00),
('Lean Ground Beef', 'protein', 3.00, 'oz', 200, 22.00, 75, 0.00, 0.00, 13.00, 0.00),
('Turkey Breast', 'protein', 3.00, 'oz', 125, 26.00, 55, 0.00, 0.00, 1.00, 0.00),
('Canned Tuna', 'protein', 3.00, 'oz', 99, 20.00, 290, 0.00, 0.00, 1.00, 0.00);


INSERT INTO Foods 
(name, category, serving_amount, serving_unit, calories_per_serving, protein_grams, sodium_mg, sugar_grams, carbs_grams, fat_grams, fiber_grams)
VALUES
('White Rice', 'grain', 1.00, 'cup', 205, 4.30, 1, 0.10, 45.00, 0.40, 0.60),
('Brown Rice', 'grain', 1.00, 'cup', 215, 5.00, 10, 0.70, 45.00, 1.80, 3.50),
('Whole Wheat Bread', 'grain', 1.00, 'slice', 80, 4.00, 150, 1.50, 13.00, 1.00, 2.00),
('Pasta (cooked)', 'grain', 1.00, 'cup', 220, 8.00, 1, 1.00, 43.00, 1.00, 2.50),
('Quinoa', 'grain', 1.00, 'cup', 222, 8.00, 13, 0.90, 39.00, 3.60, 5.20),
('Oatmeal', 'grain', 1.00, 'cup', 158, 6.00, 2, 0.50, 27.00, 3.20, 4.00),
('Tortilla (flour)', 'grain', 1.00, 'medium', 150, 4.00, 310, 1.00, 26.00, 4.00, 1.00);


INSERT INTO Foods 
(name, category, serving_amount, serving_unit, calories_per_serving, protein_grams, sodium_mg, sugar_grams, carbs_grams, fat_grams, fiber_grams)
VALUES
('Spinach', 'vegetable', 1.00, 'cup', 7, 0.90, 24, 0.10, 1.10, 0.10, 0.70),
('Broccoli', 'vegetable', 1.00, 'cup', 55, 4.60, 40, 2.20, 11.00, 0.60, 5.10),
('Carrots', 'vegetable', 1.00, 'cup', 50, 1.20, 90, 5.00, 12.00, 0.30, 3.00),
('Kale', 'vegetable', 1.00, 'cup', 33, 2.50, 30, 0.50, 7.00, 0.60, 2.60),
('Bell Pepper', 'vegetable', 1.00, 'cup', 45, 1.00, 2, 6.00, 10.00, 0.40, 3.10),
('Zucchini', 'vegetable', 1.00, 'cup', 20, 1.50, 10, 2.00, 4.00, 0.20, 1.40),
('Cauliflower', 'vegetable', 1.00, 'cup', 25, 2.00, 30, 2.00, 5.00, 0.10, 2.00);


INSERT INTO Foods 
(name, category, serving_amount, serving_unit, calories_per_serving, protein_grams, sodium_mg, sugar_grams, carbs_grams, fat_grams, fiber_grams)
VALUES
('Apple', 'fruit', 1.00, 'medium', 95, 0.50, 2, 19.00, 25.00, 0.30, 4.40),
('Banana', 'fruit', 1.00, 'medium', 105, 1.30, 1, 14.00, 27.00, 0.40, 3.10),
('Orange', 'fruit', 1.00, 'medium', 62, 1.20, 0, 12.00, 15.40, 0.20, 3.10),
('Strawberries', 'fruit', 1.00, 'cup', 50, 1.00, 1, 7.00, 11.00, 0.50, 3.00),
('Blueberries', 'fruit', 1.00, 'cup', 84, 1.10, 1, 15.00, 21.00, 0.50, 3.60),
('Grapes', 'fruit', 1.00, 'cup', 104, 1.10, 3, 23.00, 27.00, 0.20, 1.40),
('Mango', 'fruit', 1.00, 'cup', 99, 1.40, 1, 23.00, 25.00, 0.60, 2.60);


INSERT INTO Foods 
(name, category, serving_amount, serving_unit, calories_per_serving, protein_grams, sodium_mg, sugar_grams, carbs_grams, fat_grams, fiber_grams)
VALUES
('Whole Milk', 'dairy', 1.00, 'cup', 150, 8.00, 120, 12.00, 12.00, 8.00, 0.00),
('Skim Milk', 'dairy', 1.00, 'cup', 90, 8.00, 130, 12.00, 12.00, 0.50, 0.00),
('Greek Yogurt', 'dairy', 1.00, 'cup', 100, 17.00, 60, 6.00, 7.00, 0.70, 0.00),
('Cheddar Cheese', 'dairy', 1.00, 'oz', 113, 7.00, 174, 0.40, 1.00, 9.00, 0.00),
('Cottage Cheese', 'dairy', 0.50, 'cup', 100, 14.00, 400, 3.00, 3.00, 4.00, 0.00),
('Butter', 'dairy', 1.00, 'tbsp', 102, 0.10, 82, 0.00, 0.00, 12.00, 0.00);


INSERT INTO Foods 
(name, category, serving_amount, serving_unit, calories_per_serving, protein_grams, sodium_mg, sugar_grams, carbs_grams, fat_grams, fiber_grams)
VALUES
('Water', 'beverage', 8.00, 'fl oz', 0, 0.00, 0, 0.00, 0.00, 0.00, 0.00),
('Coca-Cola', 'beverage', 12.00, 'fl oz', 140, 0.00, 45, 39.00, 39.00, 0.00, 0.00),
('Orange Juice', 'beverage', 8.00, 'fl oz', 110, 2.00, 2, 20.00, 26.00, 0.50, 0.40),
('Black Coffee', 'beverage', 8.00, 'fl oz', 2, 0.30, 5, 0.00, 0.00, 0.00, 0.00),
('Green Tea', 'beverage', 8.00, 'fl oz', 0, 0.00, 5, 0.00, 0.00, 0.00, 0.00),
('Protein Shake', 'beverage', 11.00, 'fl oz', 160, 20.00, 140, 4.00, 6.00, 3.00, 1.00);


INSERT INTO Users (userName, password, height, weight, age, email, role, created_at) VALUES
('alice01', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 65.2, 120.5, 28, 'alice01@example.com', 'user', DATE_SUB(CURDATE(), INTERVAL 3 DAY)),
('bob92', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 70.1, 190.3, 33, 'bob92@example.com', 'user', DATE_SUB(CURDATE(), INTERVAL 15 DAY)),
('csmith', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 68.5, 155.0, 26, 'csmith@example.com', 'user', DATE_SUB(CURDATE(), INTERVAL 30 DAY)),
('danielk', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 72.3, 200.2, 37, 'danielk@example.com', 'user', DATE_SUB(CURDATE(), INTERVAL 21 DAY)),
('emilyt', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 63.8, 132.0, 24, 'emilyt@example.com', 'user', DATE_SUB(CURDATE(), INTERVAL 9 DAY)),
('franklin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 74.1, 210.7, 41, 'franklin@example.com', 'user', DATE_SUB(CURDATE(), INTERVAL 40 DAY)),
('grace33', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 66.5, 125.4, 30, 'grace33@example.com', 'user', DATE_SUB(CURDATE(), INTERVAL 6 DAY)),
('haroldv', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 69.9, 180.0, 35, 'haroldv@example.com', 'user', DATE_SUB(CURDATE(), INTERVAL 18 DAY)),
('isabel99', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 64.2, 115.3, 22, 'isabel99@example.com', 'user', DATE_SUB(CURDATE(), INTERVAL 2 DAY)),
('jackie8', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 71.0, 170.5, 29, 'jackie8@example.com', 'user', DATE_SUB(CURDATE(), INTERVAL 25 DAY)),
('kevinm', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 69.0, 165.5, 31, 'kevinm@example.com', 'user', DATE_SUB(CURDATE(), INTERVAL 12 DAY)),
('lucy77', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 67.4, 130.1, 27, 'lucy77@example.com', 'user', DATE_SUB(CURDATE(), INTERVAL 4 DAY)),
('mikec', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 73.2, 200.0, 38, 'mikec@example.com', 'user', DATE_SUB(CURDATE(), INTERVAL 29 DAY)),
('natalie', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 62.5, 122.3, 23, 'natalie@example.com', 'user', DATE_SUB(CURDATE(), INTERVAL 11 DAY)),
('owenx', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 75.0, 215.8, 40, 'owenx@example.com', 'user', DATE_SUB(CURDATE(), INTERVAL 5 DAY)),
('paula4', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 65.7, 128.2, 34, 'paula4@example.com', 'user', DATE_SUB(CURDATE(), INTERVAL 26 DAY)),
('quentin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 70.2, 175.0, 36, 'quentin@example.com', 'user', DATE_SUB(CURDATE(), INTERVAL 35 DAY)),
('rachel', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 64.0, 119.8, 25, 'rachel@example.com', 'user', DATE_SUB(CURDATE(), INTERVAL 10 DAY)),
('stevenq', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 72.9, 195.1, 39, 'stevenq@example.com', 'user', DATE_SUB(CURDATE(), INTERVAL 20 DAY)),
('tammyk', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 66.9, 140.4, 28, 'tammyk@example.com', 'user', DATE_SUB(CURDATE(), INTERVAL 7 DAY)),
('ulysses', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 74.4, 208.0, 42, 'ulysses@example.com', 'user', DATE_SUB(CURDATE(), INTERVAL 8 DAY)),
('valerie', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 62.9, 117.0, 26, 'valerie@example.com', 'user', DATE_SUB(CURDATE(), INTERVAL 31 DAY)),
('william9', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 71.4, 185.5, 32, 'william9@example.com', 'user', DATE_SUB(CURDATE(), INTERVAL 27 DAY)),
('ximena3', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 65.3, 123.9, 24, 'ximena3@example.com', 'user', DATE_SUB(CURDATE(), INTERVAL 13 DAY)),
('yosef4', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 68.1, 169.7, 33, 'yosef4@example.com', 'user', DATE_SUB(CURDATE(), INTERVAL 17 DAY));


-- Insert sample meals for users (entreeID, side1ID, side2ID, drinkID use IDs 1–7)
INSERT INTO UserMeals (userID, mealName, mealType, entreeID, side1ID, side2ID, drinkID) VALUES
(1, 'Morning Boost', 'breakfast', 2, 5, 7, 3),
(2, 'Power Protein Lunch', 'lunch', 1, 2, 4, 6),
(3, 'Veggie Delight', 'dinner', 4, 7, 3, 5),
(4, 'Classic Combo', 'breakfast', 6, 1, NULL, 2),
(5, 'Energy Meal', 'lunch', 5, 3, 6, 1),
(6, 'Light Dinner', 'dinner', 7, 4, 2, 3),
(7, 'Balanced Breakfast', 'breakfast', 3, 5, 1, 7),
(8, 'Quick Lunch', 'lunch', 1, 6, NULL, 4),
(9, 'Protein Packed Dinner', 'dinner', 6, 2, 7, 5),
(10, 'Simple Start', 'breakfast', 2, NULL, NULL, 1),
(11, 'Carb Load Lunch', 'lunch', 5, 1, 3, 6),
(12, 'Healthy Dinner', 'dinner', 4, 7, 2, 3),
(13, 'Fruit Boost Breakfast', 'breakfast', 6, 4, NULL, 5),
(14, 'Classic Lunch', 'lunch', 1, 3, 6, 1),
(15, 'Veggie Power Dinner', 'dinner', 7, 1, 2, 3),
(16, 'Morning Energy', 'breakfast', 3, 5, NULL, 7),
(17, 'Quick Protein Lunch', 'lunch', 1, 6, 4, 2),
(18, 'Balanced Dinner', 'dinner', 6, 3, 7, 5),
(19, 'Simple Breakfast', 'breakfast', 2, 4, NULL, 1),
(20, 'Veggie Lunch', 'lunch', 5, 7, 3, 6),
(21, 'Power Dinner', 'dinner', 4, 1, 2, 3),
(22, 'Light Breakfast', 'breakfast', 6, NULL, NULL, 1),
(23, 'Grain Bowl Lunch', 'lunch', 1, 5, 3, 4),
(24, 'Full Dinner', 'dinner', 7, 2, 3, 6),
(25, 'Start Fresh Breakfast', 'breakfast', 3, 4, NULL, 5);


-- Insert some sample weekly plan data
INSERT INTO WeeklyPlan (userID, meal_id, day_of_week, meal_type) VALUES
(1, 1, 'Monday', 'breakfast'),
(1, 2, 'Monday', 'lunch'),
(1, 3, 'Monday', 'dinner'),
(2, 4, 'Tuesday', 'breakfast'),
(2, 5, 'Tuesday', 'lunch'),
(3, 6, 'Wednesday', 'breakfast'),
(3, 7, 'Wednesday', 'dinner'),
(4, 8, 'Thursday', 'lunch'),
(5, 9, 'Friday', 'breakfast'),
(5, 10, 'Friday', 'dinner');