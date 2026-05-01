<?php
$host = 'localhost';
$dbname = 'Demonstration';
$username = 'developer';
$password = 'Online@112018';
$charset = 'utf8mb4';

// DSN (Data Source Name)
$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";

// PDO options
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Throw exceptions on errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Fetch associative arrays by default
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Use native prepared statements
];

try {
    $pdo = new PDO($dsn, $username, $password, $options);
    // Optional: set connection attributes
    // $pdo->exec("SET NAMES $charset");
} catch (PDOException $e) {
    error_log("PDO Connection Error: " . $e->getMessage());
    die("Database connection failed. Please try again later.");
}

// Now use $pdo for queries (prepared statements are strongly recommended)

// Example prepared statement:
// $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
// $stmt->execute([$_POST['email']]);
// $user = $stmt->fetch();

// Connection closes automatically at script end.
?>