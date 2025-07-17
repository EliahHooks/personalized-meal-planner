DROP TABLE IF EXISTS WeeklyPlan;
DROP TABLE IF EXISTS UserMeals;
DROP TABLE IF EXISTS UserPreferences;
DROP TABLE IF EXISTS UserLog;
DROP TABLE IF EXISTS Foods;
DROP TABLE IF EXISTS Users;

CREATE TABLE Users 
(
    userID INT AUTO_INCREMENT,
    userName VARCHAR(20) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    height DOUBLE,
    weight DOUBLE,
    age INT,
    email VARCHAR(255) UNIQUE,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (userID)
);

CREATE TABLE UserPreferences (
    preference_id INT PRIMARY KEY AUTO_INCREMENT,
    userID INT NOT NULL UNIQUE,
    activity_level ENUM('low', 'medium', 'high'),
    dietaryStyle ENUM('none', 'vegetarian', 'vegan', 'keto', 'pescatarian') DEFAULT 'none',  
    goal ENUM('lose', 'gain', 'maintain'),
    dietary_preference VARCHAR(50),
    allergies TEXT,
    dislikes TEXT,
    meals_per_day INT DEFAULT 3,
    calorie_goal INT DEFAULT 2000,
    FOREIGN KEY (userID) REFERENCES Users(UserID) ON DELETE CASCADE
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
  planDate DATE,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,

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

CREATE TABLE UserLog 
(
    logID INT AUTO_INCREMENT PRIMARY KEY,
    userID INT,                           
    userName VARCHAR(20),                
    actionType ENUM(
        'register', 
        'login', 
        'logout', 
        'create_meal', 
        'edit_meal', 
        'delete_meal', 
        'complete_meal',
        'update_profile', 
        'admin_action', 
        'other'
    ) NOT NULL,

    details TEXT,
    actionDate DATETIME DEFAULT NOW(),
    FOREIGN KEY (userID) REFERENCES Users(userID) ON DELETE CASCADE
);

-- WeeklyPlan table for storing weekly meal assignments
CREATE TABLE WeeklyPlan (
    id INT PRIMARY KEY AUTO_INCREMENT,
    userID INT NOT NULL,
    meal_id INT NOT NULL,
    day_of_week ENUM('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday') NOT NULL,
    meal_type ENUM('breakfast', 'lunch', 'dinner') NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (userID) REFERENCES Users(userID) ON DELETE CASCADE,
    FOREIGN KEY (meal_id) REFERENCES UserMeals(id) ON DELETE CASCADE,
    
    -- Ensure only one meal per user per day per meal type
    UNIQUE KEY unique_user_day_meal (userID, day_of_week, meal_type),
    
    INDEX idx_user_day (userID, day_of_week),
    INDEX idx_meal_type (meal_type)
);