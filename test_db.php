<?php

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'hotel_manager';

try {
    $conn = new mysqli($host, $username, $password, $database);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    echo "Connected successfully\n";
    
    // Try to create the users table manually
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
        
        // Insert admin user
        $password = password_hash('admin123', PASSWORD_DEFAULT);
        $created_at = date('Y-m-d H:i:s');
        
        $sql = "INSERT INTO users (username, email, password, role, created_at, updated_at)
                VALUES ('admin', 'admin@hotel.com', '$password', 'admin', '$created_at', '$created_at')";
        
        if ($conn->query($sql) === TRUE) {
            echo "Admin user created successfully\n";
        } else {
            echo "Error creating admin user: " . $conn->error . "\n";
        }
    } else {
        echo "Error creating table: " . $conn->error . "\n";
    }
    
    $conn->close();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
} 