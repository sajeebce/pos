<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306', 'root', 'root');
    echo "MySQL connection successful!\n";

    // Try to create the database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS nomanpos CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "Database 'nomanpos' created or already exists!\n";
} catch(Exception $e) {
    echo "MySQL connection failed: " . $e->getMessage() . "\n";
}
