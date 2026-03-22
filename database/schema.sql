CREATE DATABASE IF NOT EXISTS barangay_health CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE barangay_health;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(120) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('Admin', 'Health Worker') NOT NULL DEFAULT 'Health Worker',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS patients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    unique_id VARCHAR(30) NOT NULL UNIQUE,
    name VARCHAR(160) NOT NULL,
    birthdate DATE NOT NULL,
    gender VARCHAR(20) NOT NULL,
    address VARCHAR(255) NOT NULL,
    contact VARCHAR(30) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS health_records (
    id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT NOT NULL,
    date DATE NOT NULL,
    bp VARCHAR(20) NOT NULL,
    temp DECIMAL(4,1) NOT NULL,
    symptoms TEXT NOT NULL,
    diagnosis VARCHAR(255) NOT NULL,
    treatment TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_health_records_patient
        FOREIGN KEY (patient_id) REFERENCES patients(id)
        ON DELETE CASCADE ON UPDATE CASCADE
);

INSERT INTO users (name, email, password, role)
VALUES
('System Admin', 'admin@barangay.local', '$2y$10$8RiY2CXs6uM7vRXU8MxpN.N8y2x7zvLDo7D9ruR2IKf0j0MEfXxQe', 'Admin'),
('Health Worker', 'worker@barangay.local', '$2y$10$8RiY2CXs6uM7vRXU8MxpN.N8y2x7zvLDo7D9ruR2IKf0j0MEfXxQe', 'Health Worker')
ON DUPLICATE KEY UPDATE email = VALUES(email);

-- Default password for seeded users: password123
