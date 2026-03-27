<?php
// SCRIPT DE INSTALACIÓN DE BASE DE DATOS FOZC
// Ejecuta este archivo UNA vez en tu navegador: http://localhost/FOZC/setup_db.php

$host = "localhost";
$user = "root";
$pass = "";

$conn = new mysqli($host, $user, $pass);

if ($conn->connect_error) {
    die("Error de conexión al servidor MySQL: " . $conn->connect_error);
}

// Crear base de datos
$conn->query("CREATE DATABASE IF NOT EXISTS fozc_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
$conn->select_db("fozc_db");

// Crear tabla usuarios
$sql = "CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    correo VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql)) {
    echo "<h2 style='color:green;font-family:Arial'>Base de datos <strong>fozc_db</strong> creada correctamente.</h2>";
    echo "<p style='font-family:Arial'>Tabla <strong>usuarios</strong> lista.</p>";
    echo "<p style='font-family:Arial'><a href='index.php'>Ir al sitio principal</a></p>";
} else {
    echo "Error creando tabla: " . $conn->error;
}

$conn->close();
?>
