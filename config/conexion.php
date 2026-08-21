<?php
define('BASE_URL', 'http://localhost/tarea/');

$dsn = "mysql:host=localhost;dbname=tarea";
$username = "root";
$password = "";

try {
    $conexion = new PDO($dsn, $username, $password);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}