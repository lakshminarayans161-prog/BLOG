CREATE TABLE IF NOT EXISTS posts (
id INT AUTO_INCREMENT PRIMARY KEY,
title VARCHAR(255) NOT NULL,
    content TEXT NOT NUll,
    content TEXT NOT NULL,
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert demo user (username: demo, password: demo123)
INSERT INTO users (username, password) VALUES 
('demo', '$2y$10$uKftQrvRZ4z1BWJGmSXQPeoF4PshnMPipAmWnE8cGgkDvl9BMmPb6'); 
-- password is "demo123"
('demo', '$2y$10$wRflzWFhd9BEO1hWUBYx3OXR2Dayfx7bNLd7d/6pl.eDwSjSZEpnG'); 
-- password hash corresponds to: demo123

-- Insert sample posts
INSERT INTO posts (title, content) VALUES
('Welcome Post', 'This is the first sample post for testing.'),
('PHP CRUD App', 'This post explains how to build a CRUD app in PHP.'),
('MySQL Tips', 'Learn how to optimize MySQL queries for performance.'),
('Bootstrap Integration', 'Bootstrap makes UI responsive and beautiful.'),
('Search Feature', 'Now you can search posts by title or content.'),
('Pagination Example', 'Posts are paginated with 5 per page.'),
('Task 3 Demo', 'This demonstrates Task 3 with advanced features.'),
('Another Sample', 'Just another sample blog post for testing.'),
('XAMPP Setup', 'Guide to install and configure XAMPP for local dev.'),
('Final Post', 'This is the 10th sample post for full pagination demo.');
('Morning Routine', 'Started the day with tea and a walk.'),
('Travel Notes', 'A short trip to the hills last weekend.'),
('Cooking Diary', 'Tried a simple pasta recipe at home.'),
('Weekend Plans', 'Planning to relax and watch a movie.'),
('Daily Journal', 'Work was busy but productive.'),
('Book Corner', 'Finished reading a novel and enjoyed the story.'),
('Fitness Log', 'Went for jogging in the park, felt refreshed.'),
('Evening Thoughts', 'Had a nice chat with friends over coffee.'),
('Learning Note', 'Practicing PHP daily improves my coding confidence.'),
('Simple Joys', 'Watched the sunset, a calming experience.'),
('Sunday Recap', 'Spent time with family and enjoyed home cooked meals.');