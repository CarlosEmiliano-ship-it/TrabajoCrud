<?php
require_once __DIR__ . "/../config/conexion.php";
require_once __DIR__ . "/../models/Proveedor.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: " . BASE_URL . "views/proveedores/agregar.php");
    exit;
}

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
if ($id <= 0) {
    header("Location: " . BASE_URL . "views/proveedores/agregar.php?status=error&msg=id_invalido");
    exit;
}

$nombre   = trim($_POST['nombre_proveedor'] ?? '');
$contacto = trim($_POST['contacto'] ?? '');
$telefono = trim($_POST['telefono'] ?? '');

if ($nombre === '' || $contacto === '' || $telefono === '') {
    header("Location: " . BASE_URL . "controllers/editarProveedor.php?id=" . $id . "&status=error&msg=campos_incompletos");
    exit;
}

try {
    $proveedorModel = new Proveedor($conexion);
    $actualizado = $proveedorModel->actualizar($id, [
        'nombre_proveedor' => $nombre,
        'contacto'         => $contacto,
        'telefono'         => $telefono
    ]);

    header("Location: " . BASE_URL . ($actualizado
        ? "views/proveedores/agregar.php?status=updated"
        : "controllers/editarProveedor.php?id=" . $id . "&status=error&msg=no_se_pudo_actualizar"));
    exit;
} catch (PDOException $e) {
    header("Location: " . BASE_URL . "controllers/editarProveedor.php?id=" . $id . "&status=error&msg=" . urlencode($e->getMessage()));
    exit;
}