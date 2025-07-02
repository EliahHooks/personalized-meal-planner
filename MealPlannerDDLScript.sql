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
