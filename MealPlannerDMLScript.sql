
-- Insert all fields into User
INSERT INTO Users (userName, password, height, weight, email)
VALUES ('user1', 'password123', 72, 150, 'johndoe@example.com');

-- Inserting just username and password into User
INSERT INTO Users (userName, password)
VALUES ('user2', 'testPW123');

-- Inserting an admin into the table
INSERT INTO Users (userName, password, email, role)
VALUES ('admin', 'admin', 'admin1@example.com', 'admin');
