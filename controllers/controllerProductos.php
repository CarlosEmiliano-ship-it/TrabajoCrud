<?php
require_once __DIR__ . "/../config/conexion.php";
require_once __DIR__ . "/../models/Producto.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombreProducto = trim($_POST['nombre_producto'] ?? '');
    $fechaCreacion  = trim($_POST['fecha_creacion'] ?? '');
    $fechaCaducidad = trim($_POST['fecha_caducidad'] ?? '');
    $precio         = (float)($_POST['precio'] ?? 0);
    $stock          = (int)($_POST['stock'] ?? 0);
    $proveedorId    = (int)($_POST['proveedor_id'] ?? 0);

    if ($nombreProducto === '' || $fechaCreacion === '' || $proveedorId === 0) {
        header("Location: " . BASE_URL . "views/productos/agregar.php?status=error&msg=campos_incompletos");
        exit;
    }

    $fechaCaducidad = $fechaCaducidad === '' ? null : $fechaCaducidad;

    try {
        $productoModel = new Producto($conexion);

        $guardado = $productoModel->agregar([
            'nombre_producto'  => $nombreProducto,
            'fecha_creacion'   => $fechaCreacion,
            'fecha_caducidad'  => $fechaCaducidad,
            'precio'           => $precio,
            'stock'            => $stock,
            'proveedor_id'     => $proveedorId
        ]);

        if ($guardado) {
            header("Location: " . BASE_URL . "views/productos/agregar.php?status=success");
        } else {
            header("Location: " . BASE_URL . "views/productos/agregar.php?status=error&msg=error_al_insertar");
        }
        exit;

    } catch (PDOException $e) {
        header("Location: " . BASE_URL . "views/productos/agregar.php?status=error&msg=" . urlencode($e->getMessage()));
        exit;
    }
} else {
    header("Location: " . BASE_URL . "views/productos/agregar.php");
    exit;
}