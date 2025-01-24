<?php
// config.php

$host = 'localhost';
$dbname = 'yashraj_stdbs'; // your database name
$username = 'root'; // your MySQL username
$password = 'rootadmin'; // your MySQL password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
