CREATE TABLE IF NOT EXISTS users (
    id BIGSERIAL PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(120) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(30) NOT NULL CHECK (role IN ('Admin', 'Health Worker')),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS patients (
    id BIGSERIAL PRIMARY KEY,
    unique_id VARCHAR(30) NOT NULL UNIQUE,
    name VARCHAR(160) NOT NULL,
    birthdate DATE NOT NULL,
    gender VARCHAR(20) NOT NULL,
    address VARCHAR(255) NOT NULL,
    contact VARCHAR(30) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS health_records (
    id BIGSERIAL PRIMARY KEY,
    patient_id BIGINT NOT NULL REFERENCES patients(id) ON DELETE CASCADE ON UPDATE CASCADE,
    date DATE NOT NULL,
    bp VARCHAR(20) NOT NULL,
    temp NUMERIC(4,1) NOT NULL,
    symptoms TEXT NOT NULL,
    diagnosis VARCHAR(255) NOT NULL,
    treatment TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users (name, email, password, role)
VALUES
('System Admin', 'admin@barangay.local', '$2y$10$8RiY2CXs6uM7vRXU8MxpN.N8y2x7zvLDo7D9ruR2IKf0j0MEfXxQe', 'Admin'),
('Health Worker', 'worker@barangay.local', '$2y$10$8RiY2CXs6uM7vRXU8MxpN.N8y2x7zvLDo7D9ruR2IKf0j0MEfXxQe', 'Health Worker')
ON CONFLICT (email) DO UPDATE SET email = EXCLUDED.email;

-- Default password for seeded users: password123
