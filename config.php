<?php
// config.php

// OHIKO AKATSA (SQLi): mysqli_connect eta query kateatuekin erabiltzea.
// Hemen PDO + prepared statements erabiliko dugu beti.

$dsn = "mysql:host=localhost;dbname=webshop;charset=utf8mb4";
$dbUser = "root";
$dbPass = "";

// Erroreak salbuespen moduan botatzeko:
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $dbUser, $dbPass, $options);
} catch (PDOException $e) {
    die("DB errorea: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
}

session_start();

// Erabiltzailearen informazioa lortzeko helper txiki bat:
function current_user_id(): ?int {
    return $_SESSION['user_id'] ?? null;
}
