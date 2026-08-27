<?php
require_once __DIR__ . "/../config/conexion.php";
require_once __DIR__ . "/../models/Proveedor.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre   = trim($_POST['nombre_proveedor'] ?? '');
    $contacto = trim($_POST['contacto'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');

    if ($nombre === '' || $contacto === '' || $telefono === '') {
        header("Location: " . BASE_URL . "views/proveedores/agregar.php?status=error&msg=campos_incompletos");
        exit;
    }

    try {
        $proveedorModel = new Proveedor($conexion);
        $guardado = $proveedorModel->agregar([
            'nombre_proveedor' => $nombre,
            'contacto'         => $contacto,
            'telefono'         => $telefono
        ]);

        header("Location: " . BASE_URL . "views/proveedores/agregar.php?status=" . ($guardado ? "success" : "error"));
        exit;
    } catch (PDOException $e) {
        header("Location: " . BASE_URL . "views/proveedores/agregar.php?status=error&msg=" . urlencode($e->getMessage()));
        exit;
    }
} else {
    header("Location: " . BASE_URL . "views/proveedores/agregar.php");
    exit;
}