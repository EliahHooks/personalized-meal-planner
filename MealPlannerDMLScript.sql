-- Insert all fields into User (user1)
INSERT INTO Users (userName, password, height, weight, email, role)
VALUES (
    'user1',
    '$2y$10$2JDZh3fsG3yLidVsSg.YeOKgZsyqwEyHLcccpWZgVFL4V6D35llAq',
    72, 150, 'johndoe@example.com', 'user'
);

-- Inserting just username and password into User (user2)
INSERT INTO Users (userName, password, role)
VALUES (
    'user2',
    '$2y$10$0YOTDFlV0fsYokCfbZOGVeq1TSUgdkKDq5KeMiMJRuTIv2xtXsTZ2',
    'user'
);

-- Inserting an admin into the table
INSERT INTO Users (userName, password, email, role)
VALUES (
    'admin',
    '$2y$10$EpOsHjfwViVszkRT1zd6Je3z0Zq5R6lE1YeGO.qZT0CN6b63WhK4i',
    'admin1@example.com',
    'admin'
);
