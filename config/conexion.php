<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
define('BASE_URL', 'http://qatx2tlp.infinityfreeapp.com/');

$dsn = "mysql:host=sql103.infinityfree.com;dbname=if0_42716290_TareasEmpleados;charset=utf8";
$username = "if0_42716290";
$password = "IQbht1cy0tSpO";

try {
    $conexion = new PDO($dsn, $username, $password);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}