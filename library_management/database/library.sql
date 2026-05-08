CREATE DATABASE IF NOT EXISTS library_db;

USE library_db;

CREATE TABLE books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100),
    author VARCHAR(100),
    category VARCHAR(100),
    status VARCHAR(50)
);