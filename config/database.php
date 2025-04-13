<?php
/**
 * Database Configuration
 * This file contains the database connection settings
 */

// Database connection constants
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'root');
define('DB_NAME', 'mydelights');

// Create connection
function getDbConnection() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Set charset to UTF-8
    $conn->set_charset("utf8");
    
    return $conn;
}

// Helper function to execute queries and handle errors
function executeQuery($sql) {
    $conn = getDbConnection();
    $result = $conn->query($sql);
    
    if (!$result) {
        die("Query failed: " . $conn->error);
    }
    
    $conn->close();
    return $result;
}

// Function to safely prepare and execute parameterized statements
function prepareAndExecute($sql, $types, $params) {
    $conn = getDbConnection();
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    
    if ($types && $params) {
        $stmt->bind_param($types, ...$params);
    }
    
    $result = $stmt->execute();
    
    if (!$result) {
        die("Execute failed: " . $stmt->error);
    }
    
    $result = $stmt->get_result();
    $stmt->close();
    $conn->close();
    
    return $result;
}
?> 