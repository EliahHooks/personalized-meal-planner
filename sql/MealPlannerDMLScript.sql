-- Clear existing data
DELETE FROM Users;
DELETE FROM Foods;

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
-- Insert admin with password password
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
('Spinach', 'vegetable', 1.00, 'cup', 7, 0.90, 24, 0.10, 1.10, 0.10, 0.70);

