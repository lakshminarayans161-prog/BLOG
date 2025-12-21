-- Create database
CREATE DATABASE IF NOT EXISTS blog CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE blog;

-- Users table
DROP TABLE IF EXISTS users;
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  name VARCHAR(100),
  mobile VARCHAR(15) UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin','editor','user') NOT NULL DEFAULT 'user',
  profile_pic VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Posts table
DROP TABLE IF EXISTS posts;
CREATE TABLE posts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  title VARCHAR(255) NOT NULL,
  content TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Insert sample users
INSERT INTO users (username, name, mobile, password, role) VALUES
('admin', 'Super Admin', '9000000001', '$2y$10$JdWujjwq5vWnMjGhK2JXx.t0LiQetiOw.r8nAfAJC/D6JPoJU2l1m', 'admin'), -- password: Admin@1
('editor1', 'Ravi Patel', '9000000002', '$2y$10$HTiZgT2vW8VG3QluMUJxeeADzFK3t7raNmjVq11gfXq.gjIrNW.qm', 'editor'), -- password: Editor@1
('editor2', 'Anita Shah', '9000000003', '$2y$10$cjk10rrabLzPf2YmPPKJTeCUSt6c7VYtTKlXOZ23rIXAbNfXXauGe', 'editor'), -- password: Editor@2
('user1', 'Jay Mehta', '9000000004', '$2y$10$AC2j7di1FjV9R9itkUWJ/eho1Z06ODroiIAQxfFvfRaqkLfIEB6OS', 'user'), -- password: User@1
('user2', 'Priya Desai', '9000000005', '$2y$10$DP8TQAojNrxiWEgwMbvMa.ykTMgZ4O51viz1tp6t0iypA8mfJ6YM2', 'user'), -- password: User@2 
('user3', 'Amit Kumar', '9000000006', '$2y$10$ToBgxFWZGqq8b8Ao0mFd1eUKyUoVM3ldBIV0QZ9y4Lnr/nAOHR44.', 'user'); -- password: User@3

-- 15 example posts (mix of admin, editors, users)
INSERT INTO posts (user_id, title, content) VALUES
(1, 'Welcome to RentLet Blog', 'This is the first post created by Admin.'),
(1, 'System Update', 'We have introduced new roles: Admin, Editor, User.'),
(2, 'Editor Tips', 'Here are some tips for writing better content.'),
(2, 'My First Article', 'Excited to publish my first article as an Editor!'),
(3, 'Travel Story', 'Sharing my recent trip experience.'),
(3, 'Healthy Living', 'Tips on maintaining a balanced lifestyle.'),
(2, 'User Thoughts', 'This is my first post as a regular user.'),
(2, 'Daily Motivation', 'Always believe in yourself.'),
(3, 'Cooking Recipe', 'A simple recipe for delicious pasta.'),
(3, 'Book Review', 'Review of a great novel I just finished reading.'),
(2, 'Fitness Journey', 'Started gym and feeling great!'),
(3, 'Movie Recommendation', 'A must-watch thriller for everyone.'),
(2, 'Work From Home', 'How to stay productive while working from home.'),
(3, 'Tech Trends', 'Latest trends in technology this year.'),
(1, 'Admin Notice', 'Please follow community guidelines when posting.');