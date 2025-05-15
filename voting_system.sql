-- Create database 
CREATE DATABASE IF NOT EXISTS voting_system;
USE voting_system;

-- Create the admins table with username and password
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

-- Insert default admin (username: admin, password: admin123)
-- Remember to hash password in real app, this is plain text for example
INSERT INTO admins (username, password) VALUES ('admin', 'admin123')
ON DUPLICATE KEY UPDATE username = username;

-- Create voters table to store voter info and voting status
CREATE TABLE IF NOT EXISTS voters (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id VARCHAR(20) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    has_voted TINYINT(1) DEFAULT 0
);

-- Optional: insert some sample voters
INSERT INTO voters (student_id, email) VALUES
('20250001', 'student1@example.com'),
ON DUPLICATE KEY UPDATE student_id = student_id;

-- Create candidates table
CREATE TABLE IF NOT EXISTS candidates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    position VARCHAR(255) DEFAULT 'General',
    votes INT DEFAULT 0,
    photo VARCHAR(255) DEFAULT NULL
);

-- Create votes table to store votes cast
CREATE TABLE IF NOT EXISTS votes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    voter_id INT NOT NULL,
    candidate_id INT NOT NULL,
    position VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (voter_id) REFERENCES voters(id),
    FOREIGN KEY (candidate_id) REFERENCES candidates(id)
);

-- System settings
CREATE TABLE IF NOT EXISTS settings (
    id INT PRIMARY KEY,
    voting_enabled BOOLEAN DEFAULT FALSE
);

-- Initialize settings row
INSERT INTO settings (id, voting_enabled) VALUES (1, FALSE)
ON DUPLICATE KEY UPDATE voting_enabled = voting_enabled;
