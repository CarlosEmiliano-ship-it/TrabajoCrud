<?php
require_once __DIR__ . "/../config/conexion.php";
require_once __DIR__ . "/../models/Empleado.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombres   = trim($_POST['nombres'] ?? '');
    $apellidos = trim($_POST['apellidos'] ?? '');
    $fechaNac  = trim($_POST['fecha_nac'] ?? '');
    $salario   = trim($_POST['salario'] ?? '');
    $puesto    = trim($_POST['puesto'] ?? '');

    // Validación básica de campos requeridos
    if ($nombres === '' || $apellidos === '' || $fechaNac === '' || $salario === '' || $puesto === '') {
        header("Location: " . BASE_URL . "views/empleados/agregar.php?status=error&msg=campos_incompletos");
        exit;
    }

    // Procesamiento de la foto de perfil
    $rutaImagen = null;
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $carpetaDestino = __DIR__ . '/../assets/uploads/';

        if (!is_dir($carpetaDestino)) {
            mkdir($carpetaDestino, 0755, true);
        }

        $extensionesPermitidas = ['jpg', 'jpeg', 'png', 'webp'];
        $extension = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));

        if (in_array($extension, $extensionesPermitidas)) {
            $nombreArchivo = uniqid('emp_') . '.' . $extension;
            if (move_uploaded_file($_FILES['foto']['tmp_name'], $carpetaDestino . $nombreArchivo)) {
                $rutaImagen = 'assets/uploads/' . $nombreArchivo;
            }
        }
    }

    try {
        $empleadoModel = new Empleado($conexion);

        $guardado = $empleadoModel->agregar([
            'nombres'   => $nombres,
            'apellidos' => $apellidos,
            'fecha_nac' => $fechaNac,
            'salario'   => $salario,
            'puesto'    => $puesto,
            'imagen'    => $rutaImagen
        ]);

        if ($guardado) {
            header("Location: " . BASE_URL . "views/empleados/agregar.php?status=success");
        } else {
            header("Location: " . BASE_URL . "views/empleados/agregar.php?status=error&msg=error_al_insertar");
        }
        exit;

    } catch (PDOException $e) {
        header("Location: " . BASE_URL . "views/empleados/agregar.php?status=error&msg=" . urlencode($e->getMessage()));
        exit;
    }
} else {
    header("Location: " . BASE_URL . "views/empleados/agregar.php");
    exit;
}