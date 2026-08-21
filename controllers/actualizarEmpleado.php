<?php
require_once __DIR__ . "/../config/conexion.php";
require_once __DIR__ . "/../models/Empleado.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: " . BASE_URL . "views/empleados/agregar.php");
    exit;
}

$id = isset($_POST['id']) ? (int) $_POST['id'] : 0;

if ($id <= 0) {
    header("Location: " . BASE_URL . "views/empleados/agregar.php?status=error&msg=id_invalido");
    exit;
}

$nombres   = trim($_POST['nombres'] ?? '');
$apellidos = trim($_POST['apellidos'] ?? '');
$fechaNac  = trim($_POST['fecha_nac'] ?? '');
$salario   = trim($_POST['salario'] ?? '');
$puesto    = trim($_POST['puesto'] ?? '');

if ($nombres === '' || $apellidos === '' || $fechaNac === '' || $salario === '' || $puesto === '') {
    header("Location: " . BASE_URL . "controllers/editarEmpleado.php?id=" . $id . "&status=error&msg=campos_incompletos");
    exit;
}

$rutaImagen = $_POST['imagen_actual'] ?? null;

if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
    $carpetaDestino = __DIR__ . '/../assets/uploads/';

    if (!is_dir($carpetaDestino)) {
        mkdir($carpetaDestino, 0755, true);
    }

    $extensionesPermitidas = ['jpg', 'jpeg', 'png', 'webp'];
    $extension = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));

    if (in_array($extension, $extensionesPermitidas)) {
        $nombreArchivo = uniqid('emp_') . '.' . $extension;
        $rutaCompleta  = $carpetaDestino . $nombreArchivo;

        if (move_uploaded_file($_FILES['foto']['tmp_name'], $rutaCompleta)) {
            if (!empty($rutaImagen)) {
                $rutaAnterior = __DIR__ . '/../' . $rutaImagen;
                if (file_exists($rutaAnterior)) {
                    unlink($rutaAnterior);
                }
            }
            $rutaImagen = 'assets/uploads/' . $nombreArchivo;
        }
    }
}

try {
    $empleadoModel = new Empleado($conexion);

    $actualizado = $empleadoModel->actualizar($id, [
        'nombres'   => $nombres,
        'apellidos' => $apellidos,
        'fecha_nac' => $fechaNac,
        'salario'   => $salario,
        'puesto'    => $puesto,
        'imagen'    => $rutaImagen,
    ]);

    header("Location: " . BASE_URL . ($actualizado
        ? "views/empleados/agregar.php?status=updated"
        : "controllers/editarEmpleado.php?id=" . $id . "&status=error&msg=no_se_pudo_actualizar"));
    exit;

} catch (PDOException $e) {
    header("Location: " . BASE_URL . "controllers/editarEmpleado.php?id=" . $id . "&status=error&msg=" . urlencode($e->getMessage()));
    exit;
}