<?php
require_once __DIR__ . "/../config/conexion.php";
require_once __DIR__ . "/../models/Producto.php";

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    header("Location: " . BASE_URL . "views/productos/agregar.php?status=error&msg=id_invalido");
    exit;
}

try {
    $productoModel = new Producto($conexion);
    $eliminado = $productoModel->eliminar($id);

    header("Location: " . BASE_URL . "views/productos/agregar.php?status=" . ($eliminado ? 'deleted' : 'error'));
    exit;

} catch (PDOException $e) {
    header("Location: " . BASE_URL . "views/productos/agregar.php?status=error&msg=" . urlencode($e->getMessage()));
    exit;
}