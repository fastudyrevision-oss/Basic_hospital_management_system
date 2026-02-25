<?php
require 'config.php';

// Drop existing tables
$drop_tables = [
    "DROP TABLE IF EXISTS bill_items",
    "DROP TABLE IF EXISTS bills",
    "DROP TABLE IF EXISTS appointments",
    "DROP TABLE IF EXISTS users",
    "DROP TABLE IF EXISTS medicine",
    "DROP TABLE IF EXISTS staff",
    "DROP TABLE IF EXISTS patients",
    "DROP TABLE IF EXISTS doctors"
];

foreach ($drop_tables as $drop) {
    $pdo->exec($drop);
}

// Create Doctors Table
$pdo->exec("
CREATE TABLE doctors (
    id INT PRIMARY KEY AUTO_INCREMENT,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    speciality VARCHAR(150),
    phone_number VARCHAR(20),
    email VARCHAR(150) UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
");

// Create Patients Table
$pdo->exec("
CREATE TABLE patients (
    id INT PRIMARY KEY AUTO_INCREMENT,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    gender ENUM('Male','Female','Other'),
    dob DATE,
    phone_number VARCHAR(20),
    email VARCHAR(150) UNIQUE,
    age INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
");

// Create Staff Table
$pdo->exec("
CREATE TABLE staff (
    id INT PRIMARY KEY AUTO_INCREMENT,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    gender ENUM('Male','Female','Other'),
    phone_number VARCHAR(20),
    shift_time TIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
");

// Create Medicine Table
$pdo->exec("
CREATE TABLE medicine (
    id INT PRIMARY KEY AUTO_INCREMENT,
    product_name VARCHAR(100) NOT NULL,
    duration VARCHAR(100),
    dosage VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
");

// Create Users Table
$pdo->exec("
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','patient','doctor','staff') NOT NULL DEFAULT 'patient',
    patient_id INT NULL,
    doctor_id INT NULL,
    staff_id INT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_user_patient FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT fk_user_doctor FOREIGN KEY (doctor_id) REFERENCES doctors(id) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT fk_user_staff FOREIGN KEY (staff_id) REFERENCES staff(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;
");

// Create Appointments Table
$pdo->exec("
CREATE TABLE appointments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    doctor_id INT NOT NULL,
    patient_id INT NOT NULL,
    appointment_date DATETIME NOT NULL,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_appointment_doctor FOREIGN KEY (doctor_id) REFERENCES doctors(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_appointment_patient FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;
");

// Create Bills Table
$pdo->exec("
CREATE TABLE bills (
    id INT PRIMARY KEY AUTO_INCREMENT,
    patient_id INT NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    payment_method VARCHAR(50),
    status ENUM('paid','unpaid','pending') DEFAULT 'unpaid',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_bill_patient FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;
");

// Create Bill Items Table
$pdo->exec("
CREATE TABLE bill_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    bill_id INT NOT NULL,
    medicine_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    price DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_item_bill FOREIGN KEY (bill_id) REFERENCES bills(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_item_medicine FOREIGN KEY (medicine_id) REFERENCES medicine(id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;
");

// Create Indexes
$pdo->exec("CREATE INDEX idx_appointment_doctor ON appointments(doctor_id)");
$pdo->exec("CREATE INDEX idx_appointment_patient ON appointments(patient_id)");
$pdo->exec("CREATE INDEX idx_bill_patient ON bills(patient_id)");
$pdo->exec("CREATE INDEX idx_items_bill ON bill_items(bill_id)");
$pdo->exec("CREATE INDEX idx_items_medicine ON bill_items(medicine_id)");

// Insert sample data
$pdo->exec("
INSERT INTO doctors (first_name, last_name, speciality, phone_number, email)
VALUES
('Ahsan', 'Khan', 'Cardiologist', '0301-4567890', 'ahsan.khan@example.com'),
('Sara', 'Zahid', 'Dermatologist', '0321-9876543', 'sara.zahid@example.com'),
('Bilal', 'Hassan', 'Neurologist', '0345-1122334', 'bilal.hassan@example.com'),
('Nida', 'Malik', 'General Physician', '0306-5544332', 'nida.malik@example.com'),
('Hamza', 'Raza', 'Orthopedic Surgeon', '0333-7788992', 'hamza.raza@example.com');
");

$pdo->exec("
INSERT INTO patients (first_name, last_name, gender, dob, phone_number, email, age)
VALUES
('Faizan', 'Ali', 'Male', '1999-03-12', '0300-1234567', 'faizan.ali@example.com', 26),
('Ayesha', 'Nawaz', 'Female', '1997-11-22', '0334-8877665', 'ayesha.nawaz@example.com', 28),
('Usman', 'Saeed', 'Male', '2001-07-05', '0322-4455667', 'usman.saeed@example.com', 24),
('Hira', 'Arif', 'Female', '1998-05-18', '0304-9988771', 'hira.arif@example.com', 27),
('Zain', 'Shah', 'Male', '2000-10-09', '0343-6677884', 'zain.shah@example.com', 25);
");

$pdo->exec("
INSERT INTO staff (first_name, last_name, gender, phone_number, shift_time)
VALUES
('Imran', 'Qureshi', 'Male', '0301-8899776', '08:00:00'),
('Mehwish', 'Rehman', 'Female', '0333-4488221', '16:00:00'),
('Rizwan', 'Hashmi', 'Male', '0345-3322110', '20:00:00'),
('Sadia', 'Akhtar', 'Female', '0321-7788992', '14:00:00'),
('Talha', 'Javed', 'Male', '0308-6655442', '06:00:00');
");

$pdo->exec("
INSERT INTO medicine (product_name, duration, dosage)
VALUES
('Panadol', '5 days', '2 tablets daily'),
('Augmentin', '7 days', '1 tablet twice a day'),
('Brufen', '3 days', '1 tablet after meal'),
('Flagyl', '5 days', '2 tablets daily'),
('Cough Syrup', '4 days', '2 tablespoons daily');
");

$pdo->exec("
INSERT INTO appointments (doctor_id, patient_id, appointment_date, notes)
VALUES
(1, 1, '2025-01-05 10:00:00', 'Routine checkup'),
(2, 2, '2025-01-06 14:30:00', 'Skin allergy treatment'),
(3, 3, '2025-01-07 09:15:00', 'Headache and dizziness'),
(4, 4, '2025-01-08 11:45:00', 'Fever and weakness'),
(5, 5, '2025-01-09 16:00:00', 'Knee pain follow-up');
");

$pdo->exec("
INSERT INTO bills (patient_id, total_amount, payment_method, status)
VALUES
(1, 1500.00, 'Cash', 'paid'),
(2, 2500.00, 'Card', 'unpaid'),
(3, 1200.00, 'Cash', 'pending'),
(4, 1800.00, 'Cash', 'paid'),
(5, 3000.00, 'Card', 'paid');
");

$pdo->exec("
INSERT INTO bill_items (bill_id, medicine_id, quantity, price)
VALUES
(1, 1, 2, 200.00),
(1, 3, 1, 150.00),
(2, 2, 1, 450.00),
(2, 5, 1, 300.00),
(3, 4, 2, 180.00),
(4, 1, 1, 100.00),
(5, 2, 1, 450.00),
(5, 3, 1, 150.00);
");

// Insert sample users (password is 'password123' for all)
$hashedPassword = password_hash('password123', PASSWORD_DEFAULT);
$pdo->exec("
INSERT INTO users (email, password, role, patient_id, doctor_id, staff_id)
VALUES
('admin@hospital.com', '$hashedPassword', 'admin', NULL, NULL, NULL),
('faizan.ali@example.com', '$hashedPassword', 'patient', 1, NULL, NULL),
('ayesha.nawaz@example.com', '$hashedPassword', 'patient', 2, NULL, NULL),
('ahsan.khan@example.com', '$hashedPassword', 'doctor', NULL, 1, NULL),
('sara.zahid@example.com', '$hashedPassword', 'doctor', NULL, 2, NULL);
");

echo "Database initialized successfully!";
?>