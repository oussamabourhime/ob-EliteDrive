<?php

// 1. DATABASE SETUP
$host = 'localhost';
$user = 'root'; // default XAMPP user
$pass = '';     // default XAMPP password is empty

try {
    $setup_pdo = new PDO("mysql:host=$host;charset=utf8mb4", $user, $pass);
    $setup_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create DB
    $setup_pdo->exec("CREATE DATABASE IF NOT EXISTS ob_elitedrive CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $setup_pdo->exec("USE ob_elitedrive");
    
    // Create Tables
    $setup_pdo->exec("
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
        )
    ");
    
    $setup_pdo->exec("
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
        )
    ");
    
    $setup_pdo->exec("
        CREATE TABLE IF NOT EXISTS contacts (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(100) NOT NULL,
            message TEXT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");
    
    // Insert initial data if empty
    $stmt = $setup_pdo->query("SELECT COUNT(*) FROM cars");
    if ($stmt->fetchColumn() == 0) {
        $setup_pdo->exec("
            INSERT INTO cars (brand, model, year, transmission, fuel, price_per_day, image_url, category) VALUES
            ('Mercedes-Benz', 'G-Class AMG 63', 2024, 'Automatic', 'Petrol', 3500.00, 'assets/images/cars/g-class.jpg', 'Luxury SUV'),
            ('BMW', 'M8 Competition', 2023, 'Automatic', 'Petrol', 4200.00, 'assets/images/cars/bmw-m8.jpg', 'Sport'),
            ('Range Rover', 'Autobiography', 2024, 'Automatic', 'Hybrid', 3800.00, 'assets/images/cars/range-rover.jpg', 'Luxury SUV'),
            ('Porsche', 'Panamera', 2023, 'Automatic', 'Hybrid', 3600.00, 'assets/images/cars/panamera.jpg', 'Luxury Sedan')
        ");
    }
} catch (PDOException $e) {
    // Ignore setup errors implicitly on production, but useful to know.
}

// 2. DIRECTORIES AND ASSETS SETUP
$target_dir = __DIR__ . '/assets/images/cars';
$source_dir = 'C:/Users/ob';

if (!file_exists($target_dir)) {
    mkdir($target_dir, 0777, true);
}

// Map the generated files to the target filenames in the database
// Note: We'll use glob to find the exact generated filename because of the timestamp suffixes
function copyLatestImage($source_dir, $target_dir, $prefix, $final_name) {
    if (file_exists("$target_dir/$final_name")) return; // already copied
    
    $files = glob("$source_dir/{$prefix}_*.png");
    if (!empty($files)) {
        // Sort by modification time to get the latest
        usort($files, function($a, $b) {
            return filemtime($b) - filemtime($a);
        });
        copy($files[0], "$target_dir/$final_name");
    }
}

copyLatestImage($source_dir, $target_dir, 'g_class_amg', 'g-class.jpg');
copyLatestImage($source_dir, $target_dir, 'bmw_m8', 'bmw-m8.jpg');
copyLatestImage($source_dir, $target_dir, 'range_rover', 'range-rover.jpg');
copyLatestImage($source_dir, $target_dir, 'panamera', 'panamera.jpg');
copyLatestImage($source_dir, $target_dir, 'rsq8', 'audi-rsq8.jpg');
copyLatestImage($source_dir, $target_dir, 'roma', 'ferrari-roma.jpg');

