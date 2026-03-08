<?php
// includes/db.php

$host = 'localhost';
$db   = 'ob_rentaltours';
$user = 'root'; // default XAMPP user
$pass = '';     // default XAMPP password is empty

$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // In a real production environment, you would log this error and show a generic message
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
    // echo JSON for API or a proper HTML error page
    // exit('Database connection failed.');
}
?>
