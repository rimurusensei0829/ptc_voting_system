<?php
$host = 'localhost'; // Your database host
$dbname = 'voting_system'; // Your database name
$username = 'root'; // Your database username
$password = ''; // Your database password

try {
    // Create a PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Set error mode
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}
?>
