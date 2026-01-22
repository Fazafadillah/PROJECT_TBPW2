CREATE DATABASE medwell;
USE medwell;

-- 1) Users (Pasien & Admin)
CREATE TABLE users (
  id_user INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin','pasien') NOT NULL DEFAULT 'pasien',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2) Doctors
CREATE TABLE doctors (
  id_doctor INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100) NOT NULL,
  spesialis VARCHAR(100) NOT NULL,
  no_hp VARCHAR(20),
  email VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  status_aktif TINYINT(1) DEFAULT 1
);

-- 3) Appointments
CREATE TABLE appointments (
  id_appointment INT AUTO_INCREMENT PRIMARY KEY,
  id_user INT NOT NULL,
  id_doctor INT NOT NULL,
  tanggal DATE NOT NULL,
  jam TIME NOT NULL,
  keluhan TEXT,
  status ENUM('pending','approved','done','cancel') DEFAULT 'pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  FOREIGN KEY (id_user) REFERENCES users(id_user) ON DELETE CASCADE,
  FOREIGN KEY (id_doctor) REFERENCES doctors(id_doctor) ON DELETE CASCADE
);

-- 4) Medical Records
CREATE TABLE medical_records (
  id_record INT AUTO_INCREMENT PRIMARY KEY,
  id_appointment INT NOT NULL UNIQUE,
  tekanan_darah VARCHAR(20),
  berat_badan VARCHAR(10),
  diagnosa TEXT,
  tindakan TEXT,
  catatan_dokter TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  FOREIGN KEY (id_appointment) REFERENCES appointments(id_appointment) ON DELETE CASCADE
);

-- 5) Prescriptions
CREATE TABLE prescriptions (
  id_prescription INT AUTO_INCREMENT PRIMARY KEY,
  id_record INT NOT NULL,
  nama_obat VARCHAR(100) NOT NULL,
  dosis VARCHAR(50),
  aturan_pakai VARCHAR(100),
  jumlah INT DEFAULT 1,

  FOREIGN KEY (id_record) REFERENCES medical_records(id_record) ON DELETE CASCADE
);

-- Default admin
INSERT INTO users (nama, email, password, role)
VALUES ('Admin MedWell', 'admin@medwell.com', '$2y$10$NfM8TjVgFfYtH7u0x4i4xeoGKh6I3Xb7h9t8gPpZ4KqjT1f6vZk9K', 'admin');
-- password admin: admin123
