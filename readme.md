# To run this application you must need to create a database also need to create below tables

# create a database call `jobportal`

# Create the users table

CREATE TABLE users (
id INT AUTO_INCREMENT PRIMARY KEY,
username VARCHAR(50) NOT NULL,
email VARCHAR(100) NOT NULL UNIQUE,
password VARCHAR(255) NOT NULL
);

# Create the jobs table

CREATE TABLE jobs (
id INT AUTO_INCREMENT PRIMARY KEY,
title VARCHAR(100) NOT NULL,
description TEXT NOT NULL,
type ENUM('Full Time', 'Part Time') NOT NULL,
salary DECIMAL(10, 2) NOT NULL,
shift VARCHAR(50),
user_id INT NOT NULL,
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (user_id) REFERENCES users(id)
);

# Create the applications table

CREATE TABLE applications (
id INT AUTO_INCREMENT PRIMARY KEY,
user_id INT NOT NULL,
job_id INT NOT NULL,
applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
name VARCHAR(100) NOT NULL,
email VARCHAR(100) NOT NULL,
expected_salary DECIMAL(10, 2) NOT NULL,
FOREIGN KEY (user_id) REFERENCES users(id),
FOREIGN KEY (job_id) REFERENCES jobs(id)
);
