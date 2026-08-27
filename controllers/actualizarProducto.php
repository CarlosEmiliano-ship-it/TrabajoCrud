<?php
require_once __DIR__ . "/../config/conexion.php";
require_once __DIR__ . "/../models/Producto.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: " . BASE_URL . "views/productos/agregar.php");
    exit;
}

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
if ($id <= 0) {
    header("Location: " . BASE_URL . "views/productos/agregar.php?status=error&msg=id_invalido");
    exit;
}

$nombre = trim($_POST['nombre'] ?? '');
$precio = trim($_POST['precio'] ?? '');
$stock  = trim($_POST['stock'] ?? '');

if ($nombre === '' || $precio === '') {
    header("Location: " . BASE_URL . "controllers/editarProducto.php?id=" . $id . "&status=error&msg=campos_incompletos");
    exit;
}

try {
    $productoModel = new Producto($conexion);
    $actualizado = $productoModel->actualizar($id, [
        'nombre' => $nombre,
        'precio' => $precio,
        'stock'  => $stock
    ]);

    header("Location: " . BASE_URL . ($actualizado
        ? "views/productos/agregar.php?status=updated"
        : "controllers/editarProducto.php?id=" . $id . "&status=error&msg=no_se_pudo_actualizar"));
    exit;
} catch (PDOException $e) {
    header("Location: " . BASE_URL . "controllers/editarProducto.php?id=" . $id . "&status=error&msg=" . urlencode($e->getMessage()));
    exit;
}