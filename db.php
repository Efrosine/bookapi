<?php
$host = 'localhost';
$db_name = 'elibrary';
$username = 'root';
$password = '';
$connection = null;

try {
    $connection = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $exception) {
    echo "Connection error: " . $exception->getMessage();
    exit();
}
