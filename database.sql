-- Create the database
CREATE DATABASE IF NOT EXISTS ob_rentaltours CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ob_rentaltours;

-- Table structure for cars
CREATE TABLE IF NOT EXISTS cars (
  id INT AUTO_INCREMENT PRIMARY KEY,
  brand VARCHAR(50) NOT NULL,
  model VARCHAR(50) NOT NULL,
  year INT NOT NULL,
  transmission VARCHAR(20) NOT NULL,
  fuel VARCHAR(20) NOT NULL,
  price_per_day DECIMAL(10, 2) NOT NULL,
  image_url VARCHAR(255) NOT NULL,
  category VARCHAR(50) NOT NULL
);

-- Insert data for cars
INSERT INTO cars (brand, model, year, transmission, fuel, price_per_day, image_url, category) VALUES
('Mercedes-Benz', 'G-Class AMG 63', 2024, 'Automatic', 'Petrol', 3500.00, 'assets/images/cars/g-class.jpg', 'Luxury SUV'),
('BMW', 'M8 Competition', 2023, 'Automatic', 'Petrol', 4200.00, 'assets/images/cars/bmw-m8.jpg', 'Sport'),
('Range Rover', 'Autobiography', 2024, 'Automatic', 'Hybrid', 3800.00, 'assets/images/cars/range-rover.jpg', 'Luxury SUV'),
('Porsche', 'Panamera', 2023, 'Automatic', 'Hybrid', 3600.00, 'assets/images/cars/panamera.jpg', 'Luxury Sedan'),
('Audi', 'RS Q8', 2024, 'Automatic', 'Petrol', 3200.00, 'assets/images/cars/audi-rsq8.jpg', 'Performance SUV'),
('Ferrari', 'Roma', 2023, 'Automatic', 'Petrol', 8500.00, 'assets/images/cars/ferrari-roma.jpg', 'Supercar');

-- Table structure for bookings
CREATE TABLE IF NOT EXISTS bookings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  car_id INT NOT NULL,
  full_name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL,
  phone VARCHAR(30) NOT NULL,
  pickup_location VARCHAR(255) NOT NULL,
  dropoff_location VARCHAR(255) NOT NULL,
  pickup_date DATETIME NOT NULL,
  dropoff_date DATETIME NOT NULL,
  total_price DECIMAL(10, 2) NOT NULL,
  status VARCHAR(20) DEFAULT 'pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (car_id) REFERENCES cars(id) ON DELETE CASCADE
);

-- Table structure for contacts
CREATE TABLE IF NOT EXISTS contacts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL,
  message TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
