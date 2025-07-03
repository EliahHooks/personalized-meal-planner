-- Clear existing data
DELETE FROM UserMeals;
DELETE FROM Foods;
DELETE FROM Users;

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
