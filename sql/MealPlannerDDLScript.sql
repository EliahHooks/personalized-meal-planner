DROP TABLE IF EXISTS UserMeals;
DROP TABLE IF EXISTS Foods;
DROP TABLE IF EXISTS Users;

CREATE TABLE Users 
(
    userID INT AUTO_INCREMENT,
    userName VARCHAR(20) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    height DOUBLE,
    weight DOUBLE,
    email VARCHAR(255) UNIQUE,
    role ENUM('user', 'admin') DEFAULT 'user',
    PRIMARY KEY (userID)
);

CREATE TABLE Foods (
  id INT PRIMARY KEY AUTO_INCREMENT,

  name VARCHAR(50) NOT NULL,

  category ENUM('protein', 'grain', 'vegetable', 'fruit', 'dairy', 'beverage') NOT NULL,

  serving_amount DECIMAL(6,2),           -- e.g., 4.00
  serving_unit VARCHAR(20),              -- e.g., 'oz', 'cup', 'g'

  calories_per_serving INT,

  protein_grams DECIMAL(5,2),
  sodium_mg INT,
  sugar_grams DECIMAL(5,2),
  carbs_grams DECIMAL(5,2),
  fat_grams DECIMAL(5,2),
  fiber_grams DECIMAL(5,2),

  INDEX idx_category (category),
  INDEX idx_nutrition (protein_grams, sodium_mg, sugar_grams)
);

CREATE TABLE UserMeals ( 
  id INT PRIMARY KEY AUTO_INCREMENT,  

  userID INT NOT NULL,  
  mealName VARCHAR(50),  
  mealType ENUM('breakfast', 'lunch', 'dinner') NOT NULL,  

  entreeID INT DEFAULT NULL,  
  side1ID INT DEFAULT NULL,  
  side2ID INT DEFAULT NULL,  
  drinkID INT DEFAULT NULL,  

  FOREIGN KEY (userID) REFERENCES Users(userID) ON DELETE CASCADE,  
  FOREIGN KEY (entreeID) REFERENCES Foods(id) ON DELETE SET NULL,  
  FOREIGN KEY (side1ID) REFERENCES Foods(id) ON DELETE SET NULL,  
  FOREIGN KEY (side2ID) REFERENCES Foods(id) ON DELETE SET NULL,  
  FOREIGN KEY (drinkID) REFERENCES Foods(id) ON DELETE SET NULL
);
