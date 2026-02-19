<?php
$host = 'localhost';
$db   = 'matriock_db';
$user = 'admin_chat'; // El nuevo usuario
$pass = 'Matriock2026!'; // La contraseña que pusimos arriba
$charset = 'utf8mb4';

try {
     $pdo = new PDO("mysql:host=$host;dbname=$db;charset=$charset", $user, $pass);
     $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
     die("Error: " . $e->getMessage());
}
?>