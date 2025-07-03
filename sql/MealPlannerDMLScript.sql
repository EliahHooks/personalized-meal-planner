-- Clear existing data
DELETE FROM Users;

-- Insert user1 with password "password123"
INSERT INTO Users (userName, password, height, weight, email, role)
VALUES (
    'user1',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    72, 150, 'johndoe@example.com', 'user'
);

-- Insert user2 with password "password123"
INSERT INTO Users (userName, password, role)
VALUES (
    'user2',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'user'
);

-- Insert admin with password "password123"
INSERT INTO Users (userName, password, email, role)
VALUES (
    'admin',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'admin1@example.com',
    'admin'
);
