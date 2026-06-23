-- Database schema for the Login / Sign Up app
-- Import this with: mysql -u root -p < schema.sql

CREATE DATABASE IF NOT EXISTS auth_app CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE auth_app;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
