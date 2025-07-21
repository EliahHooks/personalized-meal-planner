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


-- PROTEIN (15 items)
INSERT INTO Foods 
(name, category, serving_amount, serving_unit, calories_per_serving, protein_grams, sodium_mg, sugar_grams, carbs_grams, fat_grams, fiber_grams, dietary_tags, allergens, ingredients)
VALUES
('Chicken Breast', 'protein', 4.00, 'oz', 185, 35.00, 74, 0.00, 0.00, 4.00, 0.00, 'gluten-free,keto', '', 'chicken breast'),
('Eggs', 'protein', 1.00, 'large', 70, 6.00, 70, 0.00, 1.00, 5.00, 0.00, 'gluten-free,keto', 'eggs', 'whole egg'),
('Salmon', 'protein', 3.00, 'oz', 180, 22.00, 50, 0.00, 0.00, 10.00, 0.00, 'gluten-free,paleo', 'fish', 'salmon'),
('Tofu', 'protein', 0.50, 'cup', 94, 10.00, 9, 0.00, 2.00, 5.00, 1.00, 'vegan,gluten-free', 'soy', 'soybeans, water, coagulant'),
('Lean Ground Beef', 'protein', 3.00, 'oz', 200, 22.00, 75, 0.00, 0.00, 13.00, 0.00, 'gluten-free,keto', '', 'lean ground beef'),
('Turkey Breast', 'protein', 3.00, 'oz', 125, 26.00, 55, 0.00, 0.00, 1.00, 0.00, 'gluten-free,keto', '', 'turkey breast'),
('Canned Tuna', 'protein', 3.00, 'oz', 99, 20.00, 290, 0.00, 0.00, 1.00, 0.00, 'gluten-free,paleo', 'fish', 'tuna, water, salt'),
('Pork Loin', 'protein', 3.00, 'oz', 160, 23.00, 55, 0.00, 0.00, 7.00, 0.00, 'gluten-free', '', 'pork loin'),
('Shrimp', 'protein', 3.00, 'oz', 84, 18.00, 148, 0.00, 1.00, 1.00, 0.00, 'gluten-free,paleo', 'shellfish', 'shrimp'),
('Tempeh', 'protein', 0.50, 'cup', 160, 15.00, 9, 0.00, 9.00, 7.00, 5.00, 'vegan,gluten-free', 'soy', 'fermented soybeans'),
('Lentils', 'protein', 1.00, 'cup', 230, 18.00, 4, 4.00, 40.00, 1.00, 16.00, 'vegan,gluten-free', '', 'lentils'),
('Greek Yogurt (Plain)', 'protein', 1.00, 'cup', 100, 17.00, 60, 6.00, 7.00, 0.70, 0.00, '', 'dairy', 'milk, cultures'),
('Venison', 'protein', 3.00, 'oz', 134, 26.00, 58, 0.00, 0.00, 2.00, 0.00, 'gluten-free,paleo', '', 'venison'),
('Duck Breast', 'protein', 3.00, 'oz', 200, 19.00, 58, 0.00, 0.00, 14.00, 0.00, 'gluten-free', '', 'duck breast'),
('Edamame', 'protein', 0.50, 'cup', 95, 9.00, 15, 2.00, 8.00, 3.00, 4.00, 'vegan,gluten-free', 'soy', 'soybeans'),
('Black Beans', 'protein', 1.00, 'cup', 227, 15.00, 1, 1.00, 40.00, 1.00, 15.00, 'vegan,gluten-free', '', 'black beans');

-- GRAINS (15 items)
INSERT INTO Foods 
(name, category, serving_amount, serving_unit, calories_per_serving, protein_grams, sodium_mg, sugar_grams, carbs_grams, fat_grams, fiber_grams, dietary_tags, allergens, ingredients)
VALUES
('White Rice', 'grain', 1.00, 'cup', 205, 4.30, 1, 0.10, 45.00, 0.40, 0.60, 'vegan,gluten-free', '', 'white rice'),
('Brown Rice', 'grain', 1.00, 'cup', 215, 5.00, 10, 0.70, 45.00, 1.80, 3.50, 'vegan,gluten-free', '', 'brown rice'),
('Whole Wheat Bread', 'grain', 1.00, 'slice', 80, 4.00, 150, 1.50, 13.00, 1.00, 2.00, 'vegan', 'gluten', 'whole wheat flour, water, yeast, salt'),
('Pasta (cooked)', 'grain', 1.00, 'cup', 220, 8.00, 1, 1.00, 43.00, 1.00, 2.50, 'vegan', 'gluten', 'durum wheat semolina, water'),
('Quinoa', 'grain', 1.00, 'cup', 222, 8.00, 13, 0.90, 39.00, 3.60, 5.20, 'vegan,gluten-free', '', 'quinoa'),
('Oatmeal', 'grain', 1.00, 'cup', 158, 6.00, 2, 0.50, 27.00, 3.20, 4.00, 'vegan,gluten-free', 'gluten', 'oats'),
('Tortilla (flour)', 'grain', 1.00, 'medium', 150, 4.00, 310, 1.00, 26.00, 4.00, 1.00, 'vegan', 'gluten', 'wheat flour, water, salt, oil'),
('Barley', 'grain', 1.00, 'cup', 193, 3.50, 5, 0.80, 44.30, 0.70, 6.0, 'vegan', 'gluten', 'barley'),
('Cornmeal', 'grain', 1.00, 'cup', 440, 9.00, 20, 0.50, 95.00, 3.50, 7.0, 'vegan,gluten-free', '', 'cornmeal'),
('Buckwheat', 'grain', 1.00, 'cup', 155, 5.70, 1, 0.40, 33.00, 1.00, 4.50, 'vegan,gluten-free', '', 'buckwheat'),
('Rye Bread', 'grain', 1.00, 'slice', 83, 2.70, 180, 1.20, 15.00, 0.90, 2.00, 'vegan', 'gluten', 'rye flour, water, yeast, salt'),
('Millet', 'grain', 1.00, 'cup', 207, 6.10, 5, 0.00, 41.20, 1.70, 2.30, 'vegan,gluten-free', '', 'millet'),
('Couscous', 'grain', 1.00, 'cup', 176, 6.10, 10, 0.10, 36.00, 0.30, 2.20, 'vegan', 'gluten', 'semolina wheat, water'),
('Amaranth', 'grain', 1.00, 'cup', 251, 9.30, 15, 1.60, 46.00, 4.00, 5.20, 'vegan,gluten-free', '', 'amaranth grain'),
('Wild Rice', 'grain', 1.00, 'cup', 166, 6.50, 5, 0.70, 35.00, 0.60, 3.00, 'vegan,gluten-free', '', 'wild rice');

-- VEGETABLE (15 items)
INSERT INTO Foods 
(name, category, serving_amount, serving_unit, calories_per_serving, protein_grams, sodium_mg, sugar_grams, carbs_grams, fat_grams, fiber_grams, dietary_tags, allergens, ingredients)
VALUES
('Spinach', 'vegetable', 1.00, 'cup', 7, 0.90, 24, 0.10, 1.10, 0.10, 0.70, 'vegan,gluten-free,keto', '', 'spinach'),
('Broccoli', 'vegetable', 1.00, 'cup', 55, 4.60, 40, 2.20, 11.00, 0.60, 5.10, 'vegan,gluten-free,keto', '', 'broccoli'),
('Carrots', 'vegetable', 1.00, 'cup', 50, 1.20, 90, 5.00, 12.00, 0.30, 3.00, 'vegan,gluten-free', '', 'carrots'),
('Kale', 'vegetable', 1.00, 'cup', 33, 2.50, 30, 0.50, 7.00, 0.60, 2.60, 'vegan,gluten-free,keto', '', 'kale'),
('Bell Pepper', 'vegetable', 1.00, 'cup', 45, 1.00, 2, 6.00, 10.00, 0.40, 3.10, 'vegan,gluten-free', '', 'bell pepper'),
('Zucchini', 'vegetable', 1.00, 'cup', 20, 1.50, 10, 2.00, 4.00, 0.20, 1.40, 'vegan,gluten-free', '', 'zucchini'),
('Cauliflower', 'vegetable', 1.00, 'cup', 25, 2.00, 30, 2.00, 5.00, 0.10, 2.00, 'vegan,gluten-free', '', 'cauliflower'),
('Asparagus', 'vegetable', 1.00, 'cup', 27, 3.00, 2, 2.00, 5.00, 0.20, 2.80, 'vegan,gluten-free', '', 'asparagus'),
('Cucumber', 'vegetable', 1.00, 'cup', 16, 0.70, 2, 1.70, 4.00, 0.10, 0.50, 'vegan,gluten-free', '', 'cucumber'),
('Green Beans', 'vegetable', 1.00, 'cup', 31, 2.00, 6, 3.30, 7.00, 0.20, 3.40, 'vegan,gluten-free', '', 'green beans'),
('Brussels Sprouts', 'vegetable', 1.00, 'cup', 38, 3.00, 28, 2.20, 8.00, 0.30, 3.30, 'vegan,gluten-free', '', 'brussels sprouts'),
('Eggplant', 'vegetable', 1.00, 'cup', 20, 0.80, 2, 3.20, 5.00, 0.10, 2.50, 'vegan,gluten-free', '', 'eggplant'),
('Sweet Corn', 'vegetable', 1.00, 'ear', 77, 3.00, 15, 6.40, 19.00, 1.10, 2.00, 'vegan,gluten-free', '', 'corn'),
('Onion', 'vegetable', 1.00, 'medium', 44, 1.20, 4, 9.00, 10.00, 0.10, 1.90, 'vegan,gluten-free', '', 'onion'),
('Mushrooms', 'vegetable', 1.00, 'cup', 15, 2.20, 5, 1.40, 2.30, 0.20, 0.70, 'vegan,gluten-free', '', 'mushrooms');

-- FRUIT (15 items)
INSERT INTO Foods 
(name, category, serving_amount, serving_unit, calories_per_serving, protein_grams, sodium_mg, sugar_grams, carbs_grams, fat_grams, fiber_grams, dietary_tags, allergens, ingredients)
VALUES
('Apple', 'fruit', 1.00, 'medium', 95, 0.50, 2, 19.00, 25.00, 0.30, 4.40, 'vegan,gluten-free', '', 'apple'),
('Banana', 'fruit', 1.00, 'medium', 105, 1.30, 1, 14.00, 27.00, 0.40, 3.10, 'vegan,gluten-free', '', 'banana'),
('Orange', 'fruit', 1.00, 'medium', 62, 1.20, 0, 12.00, 15.40, 0.20, 3.10, 'vegan,gluten-free', '', 'orange'),
('Strawberries', 'fruit', 1.00, 'cup', 50, 1.00, 1, 7.00, 11.00, 0.50, 3.00, 'vegan,gluten-free', '', 'strawberries'),
('Blueberries', 'fruit', 1.00, 'cup', 84, 1.10, 1, 15.00, 21.00, 0.50, 3.60, 'vegan,gluten-free', '', 'blueberries'),
('Grapes', 'fruit', 1.00, 'cup', 104, 1.10, 3, 23.00, 27.00, 0.20, 1.40, 'vegan,gluten-free', '', 'grapes'),
('Mango', 'fruit', 1.00, 'cup', 99, 1.40, 1, 23.00, 25.00, 0.60, 2.60, 'vegan,gluten-free', '', 'mango'),
('Pineapple', 'fruit', 1.00, 'cup', 82, 0.90, 2, 16.00, 22.00, 0.20, 2.30, 'vegan,gluten-free', '', 'pineapple'),
('Watermelon', 'fruit', 1.00, 'cup', 46, 0.90, 2, 9.00, 12.00, 0.20, 0.60, 'vegan,gluten-free', '', 'watermelon'),
('Peach', 'fruit', 1.00, 'medium', 59, 1.00, 0, 13.00, 15.00, 0.40, 2.30, 'vegan,gluten-free', '', 'peach'),
('Pear', 'fruit', 1.00, 'medium', 101, 0.60, 2, 17.00, 27.00, 0.20, 5.10, 'vegan,gluten-free', '', 'pear'),
('Cherries', 'fruit', 1.00, 'cup', 87, 1.50, 3, 18.00, 22.00, 0.30, 3.0, 'vegan,gluten-free', '', 'cherries'),
('Raspberries', 'fruit', 1.00, 'cup', 64, 1.50, 1, 5.40, 15.00, 0.80, 8.0, 'vegan,gluten-free', '', 'raspberries'),
('Blackberries', 'fruit', 1.00, 'cup', 62, 2.00, 2, 7.00, 14.00, 0.70, 7.6, 'vegan,gluten-free', '', 'blackberries'),
('Cantaloupe', 'fruit', 1.00, 'cup', 53, 1.30, 18, 13.00, 13.00, 0.30, 1.4, 'vegan,gluten-free', '', 'cantaloupe');

-- DAIRY (15 items)
INSERT INTO Foods 
(name, category, serving_amount, serving_unit, calories_per_serving, protein_grams, sodium_mg, sugar_grams, carbs_grams, fat_grams, fiber_grams, dietary_tags, allergens, ingredients)
VALUES
('Whole Milk', 'dairy', 1.00, 'cup', 150, 8.00, 120, 12.00, 12.00, 8.00, 0.00, '', 'dairy', 'whole milk'),
('Skim Milk', 'dairy', 1.00, 'cup', 90, 8.00, 130, 12.00, 12.00, 0.50, 0.00, '', 'dairy', 'skim milk'),
('Greek Yogurt', 'dairy', 1.00, 'cup', 100, 17.00, 60, 6.00, 7.00, 0.70, 0.00, '', 'dairy', 'milk, cultures, live active cultures'),
('Cheddar Cheese', 'dairy', 1.00, 'oz', 113, 7.00, 174, 0.40, 1.00, 9.00, 0.00, '', 'dairy', 'pasteurized milk, salt, enzymes'),
('Cottage Cheese', 'dairy', 0.50, 'cup', 100, 14.00, 400, 3.00, 3.00, 4.00, 0.00, '', 'dairy', 'pasteurized milk, cream, salt'),
('Butter', 'dairy', 1.00, 'tbsp', 102, 0.10, 82, 0.00, 0.00, 12.00, 0.00, '', 'dairy', 'cream, salt'),
('Mozzarella Cheese', 'dairy', 1.00, 'oz', 85, 6.00, 175, 0.10, 1.00, 6.00, 0.00, '', 'dairy', 'milk, salt, enzymes'),
('Parmesan Cheese', 'dairy', 1.00, 'oz', 110, 10.00, 450, 0.30, 3.20, 7.00, 0.00, '', 'dairy', 'milk, salt, enzymes'),
('Sour Cream', 'dairy', 2.00, 'tbsp', 60, 1.00, 15, 1.00, 1.00, 5.00, 0.00, '', 'dairy', 'cream, bacterial culture'),
('Cream Cheese', 'dairy', 1.00, 'tbsp', 50, 1.00, 90, 1.00, 1.00, 5.00, 0.00, '', 'dairy', 'cream, milk, salt'),
('Ricotta Cheese', 'dairy', 0.25, 'cup', 108, 7.00, 50, 1.00, 3.00, 8.00, 0.00, '', 'dairy', 'milk, cream, enzymes'),
('Ice Cream', 'dairy', 0.50, 'cup', 137, 2.30, 50, 14.00, 15.00, 7.00, 0.00, '', 'dairy', 'milk, cream, sugar, eggs'),
('Heavy Cream', 'dairy', 1.00, 'tbsp', 52, 0.30, 6, 0.10, 0.40, 5.50, 0.00, '', 'dairy', 'cream'),
('Buttermilk', 'dairy', 1.00, 'cup', 98, 8.00, 120, 12.00, 12.00, 2.00, 0.00, '', 'dairy', 'cultured milk'),
('Ghee', 'dairy', 1.00, 'tbsp', 112, 0.00, 0, 0.00, 0.00, 14.00, 0.00, '', '', 'clarified butter');

-- BEVERAGES (15 items)
INSERT INTO Foods 
(name, category, serving_amount, serving_unit, calories_per_serving, protein_grams, sodium_mg, sugar_grams, carbs_grams, fat_grams, fiber_grams, dietary_tags, allergens, ingredients)
VALUES
('Water', 'beverage', 8.00, 'fl oz', 0, 0.00, 0, 0.00, 0.00, 0.00, 0.00, 'vegan,gluten-free', '', 'water'),
('Coca-Cola', 'beverage', 12.00, 'fl oz', 140, 0.00, 45, 39.00, 39.00, 0.00, 0.00, '', '', 'carbonated water, high fructose corn syrup, caramel color, phosphoric acid, natural flavors, caffeine'),
('Orange Juice', 'beverage', 8.00, 'fl oz', 110, 2.00, 2, 20.00, 26.00, 0.50, 0.40, 'vegan,gluten-free', '', 'orange juice'),
('Black Coffee', 'beverage', 8.00, 'fl oz', 2, 0.30, 5, 0.00, 0.00, 0.00, 0.00, 'vegan,gluten-free,keto', '', 'coffee'),
('Green Tea', 'beverage', 8.00, 'fl oz', 0, 0.00, 5, 0.00, 0.00, 0.00, 0.00, 'vegan,gluten-free,keto', '', 'green tea leaves'),
('Protein Shake', 'beverage', 11.00, 'fl oz', 160, 20.00, 140, 4.00, 6.00, 3.00, 1.00, '', 'milk,soy', 'milk protein concentrate, water, soy protein isolate, flavors, sweeteners'),
('Milkshake (Chocolate)', 'beverage', 8.00, 'fl oz', 350, 10.00, 150, 50.00, 40.00, 15.00, 0.00, '', 'dairy', 'milk, chocolate syrup, sugar, cream'),
('Beer', 'beverage', 12.00, 'fl oz', 153, 2.00, 14, 0.00, 13.00, 0.00, 0.00, '', 'gluten', 'water, barley malt, hops, yeast'),
('Red Wine', 'beverage', 5.00, 'fl oz', 125, 0.10, 7, 0.90, 4.00, 0.00, 0.00, '', '', 'fermented grapes'),
('White Wine', 'beverage', 5.00, 'fl oz', 121, 0.10, 7, 0.90, 4.00, 0.00, 0.00, '', '', 'fermented grapes'),
('Apple Juice', 'beverage', 8.00, 'fl oz', 114, 0.10, 10, 24.00, 28.00, 0.00, 0.00, 'vegan,gluten-free', '', 'apple juice'),
('Herbal Tea', 'beverage', 8.00, 'fl oz', 0, 0.00, 0, 0.00, 0.00, 0.00, 0.00, 'vegan,gluten-free', '', 'various herbs'),
('Soda (Diet)', 'beverage', 12.00, 'fl oz', 0, 0.00, 40, 0.00, 0.00, 0.00, 0.00, '', '', 'carbonated water, artificial sweeteners, flavors'),
('Smoothie (Mixed Fruit)', 'beverage', 8.00, 'fl oz', 150, 2.00, 10, 28.00, 35.00, 0.50, 2.00, 'vegan,gluten-free', '', 'mixed fruits, water, ice'),
('Energy Drink', 'beverage', 8.00, 'fl oz', 110, 0.00, 150, 27.00, 28.00, 0.00, 0.00, '', '', 'caffeine, taurine, sugar, vitamins');



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


-- Mark plant-based proteins as vegan
UPDATE Foods SET dietary_tags = 'vegan,vegetarian,keto' WHERE name LIKE '%tofu%' OR name LIKE '%tempeh%';

-- Mark dairy products as vegetarian only
UPDATE Foods SET dietary_tags = 'vegetarian' WHERE category = 'dairy';

-- Mark meat products with no dietary tags (empty means omnivore)
UPDATE Foods SET dietary_tags = '' WHERE category = 'protein' AND (name LIKE '%chicken%' OR name LIKE '%beef%' OR name LIKE '%pork%');

-- Mark fish as pescatarian
UPDATE Foods SET dietary_tags = 'pescatarian' WHERE name LIKE '%fish%' OR name LIKE '%salmon%' OR name LIKE '%tuna%' OR name LIKE '%shrimp%';

-- Mark vegetables and fruits as vegan
UPDATE Foods SET dietary_tags = 'vegan,vegetarian,keto' WHERE category IN ('vegetable', 'fruit');

-- Mark grains (adjust keto tag as needed - most grains are not keto)
UPDATE Foods SET dietary_tags = 'vegan,vegetarian' WHERE category = 'grain';

-- Mark low-carb vegetables as keto-friendly
UPDATE Foods SET dietary_tags = 'vegan,vegetarian,keto' WHERE name IN ('spinach', 'broccoli', 'cauliflower', 'kale', 'lettuce', 'cucumber', 'zucchini');

-- Mark beverages appropriately
UPDATE Foods SET dietary_tags = 'vegan,vegetarian,keto' WHERE category = 'beverage' AND name IN ('water', 'black coffee', 'tea');
UPDATE Foods SET dietary_tags = 'vegetarian' WHERE category = 'beverage' AND name LIKE '%milk%';

-- Foods table