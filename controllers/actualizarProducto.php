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

$nombre_producto  = trim($_POST['nombre_producto'] ?? '');
$fecha_creacion   = trim($_POST['fecha_creacion'] ?? '');
$fecha_caducidad  = !empty($_POST['fecha_caducidad']) ? trim($_POST['fecha_caducidad']) : null;
$precio           = trim($_POST['precio'] ?? '');
$stock            = trim($_POST['stock'] ?? '');
$proveedor_id     = isset($_POST['proveedor_id']) ? (int)$_POST['proveedor_id'] : 0;

if ($nombre_producto === '' || $precio === '' || $proveedor_id <= 0) {
    header("Location: " . BASE_URL . "controllers/editarProducto.php?id=" . $id . "&status=error&msg=campos_incompletos");
    exit;
}

try {
    $productoModel = new Producto($conexion);
    $actualizado = $productoModel->actualizar($id, [
        'nombre_producto'  => $nombre_producto,
        'fecha_creacion'   => $fecha_creacion,
        'fecha_caducidad'  => $fecha_caducidad,
        'precio'           => $precio,
        'stock'            => $stock,
        'proveedor_id'     => $proveedor_id
    ]);

    header("Location: " . BASE_URL . ($actualizado
        ? "views/productos/agregar.php?status=success"
        : "controllers/editarProducto.php?id=" . $id . "&status=error&msg=no_se_pudo_actualizar"));
    exit;
} catch (PDOException $e) {
    header("Location: " . BASE_URL . "controllers/editarProducto.php?id=" . $id . "&status=error&msg=" . urlencode($e->getMessage()));
    exit;
}