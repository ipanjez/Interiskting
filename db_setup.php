<?php
// Database setup script for XAMPP
$host = 'localhost';
$username = 'root';
$password = ''; // Default XAMPP password
$database = 'n1565822_amr_br';

echo "Testing database connection...\n";

try {
    // First, connect without specifying database to check if MySQL is running
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✓ MySQL connection successful\n";
    
    // Check if database exists
    $stmt = $pdo->query("SHOW DATABASES LIKE '$database'");
    if ($stmt->rowCount() > 0) {
        echo "✓ Database '$database' already exists\n";
    } else {
        echo "× Database '$database' does not exist\n";
        echo "Creating database '$database'...\n";
        
        // Create database
        $pdo->exec("CREATE DATABASE `$database` CHARACTER SET utf8 COLLATE utf8_general_ci");
        echo "✓ Database '$database' created successfully\n";
    }
    
    // Test connection to the specific database
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    echo "✓ Connection to database '$database' successful\n";
    
} catch (PDOException $e) {
    echo "× Error: " . $e->getMessage() . "\n";
    echo "Please make sure:\n";
    echo "1. XAMPP MySQL service is running\n";
    echo "2. MySQL credentials are correct\n";
    echo "3. PHP PDO MySQL extension is enabled\n";
}
?>
