<?php

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'hotel_manager';

try {
    $conn = new mysqli($host, $username, $password);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    echo "Connected successfully\n";
    
    // Create database if not exists
    $sql = "CREATE DATABASE IF NOT EXISTS hotel_manager";
    if ($conn->query($sql) === TRUE) {
        echo "Database created successfully\n";
    } else {
        echo "Error creating database: " . $conn->error . "\n";
    }
    
    // Select the database
    $conn->select_db($database);
    
    // Create users table if not exists
    $sql = "CREATE TABLE IF NOT EXISTS users (
        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(100) UNIQUE NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        role ENUM('admin', 'staff') DEFAULT 'staff',
        created_at DATETIME NULL,
        updated_at DATETIME NULL
    )";
    
    if ($conn->query($sql) === TRUE) {
        echo "Table users created successfully\n";
    } else {
        echo "Error creating table: " . $conn->error . "\n";
    }
    
    // Create guests table if not exists
    $sql = "CREATE TABLE IF NOT EXISTS guests (
        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        first_name VARCHAR(50) NOT NULL,
        last_name VARCHAR(50) NOT NULL,
        email VARCHAR(100) NOT NULL,
        phone VARCHAR(20) NOT NULL,
        room_number VARCHAR(10) NOT NULL,
        check_in_date DATE NOT NULL,
        check_out_date DATE NOT NULL,
        status ENUM('checked_in', 'checked_out', 'reserved') DEFAULT 'reserved',
        created_at DATETIME NULL,
        updated_at DATETIME NULL,
        INDEX(email),
        INDEX(room_number)
    )";
    
    if ($conn->query($sql) === TRUE) {
        echo "Table guests created successfully\n";
    } else {
        echo "Error creating table: " . $conn->error . "\n";
    }
    
    // Insert admin user if not exists
    $password = password_hash('admin123', PASSWORD_DEFAULT);
    $created_at = date('Y-m-d H:i:s');
    
    $sql = "INSERT IGNORE INTO users (username, email, password, role, created_at, updated_at)
            VALUES ('admin', 'admin@hotel.com', '$password', 'admin', '$created_at', '$created_at')";
    
    if ($conn->query($sql) === TRUE) {
        echo "Admin user created successfully\n";
    } else {
        echo "Error creating admin user: " . $conn->error . "\n";
    }
    
    $conn->close();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
} 