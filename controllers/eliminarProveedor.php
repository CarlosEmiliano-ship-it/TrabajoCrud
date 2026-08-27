<?php
require_once __DIR__ . "/../config/conexion.php";

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header("Location: " . BASE_URL . "views/proveedores/agregar.php?status=error&msg=id_invalido");
    exit;
}

try {
    $stmt = $conexion->prepare("DELETE FROM proveedor WHERE id = :id");
    $stmt->execute([':id' => $id]);

    header("Location: " . BASE_URL . "views/proveedores/agregar.php?status=deleted");
    exit;
} catch (PDOException $e) {
    header("Location: " . BASE_URL . "views/proveedores/agregar.php?status=error&msg=" . urlencode($e->getMessage()));
    exit;
}