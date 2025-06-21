
-- Insert all fields into User
INSERT INTO Users (userName, password, height, weight, email)
VALUES ('user1', 'password123', 72, 150, 'johndoe@example.com');

-- Inserting just username and password into User
INSERT INTO Users (userName, password)
VALUES ('user2', 'testPW123');
