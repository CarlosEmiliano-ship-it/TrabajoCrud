<?php
require_once __DIR__ . "/../config/conexion.php";
require_once __DIR__ . "/../models/Estudiante.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: " . BASE_URL . "views/estudiantes/agregar.php");
    exit;
}

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
if ($id <= 0) {
    header("Location: " . BASE_URL . "views/estudiantes/agregar.php?status=error&msg=id_invalido");
    exit;
}

$nombres   = trim($_POST['nombres'] ?? '');
$apellidos = trim($_POST['apellidos'] ?? '');

if ($nombres === '' || $apellidos === '') {
    header("Location: " . BASE_URL . "controllers/editarEstudiante.php?id=" . $id . "&status=error&msg=campos_incompletos");
    exit;
}

try {
    $estudianteModel = new Estudiante($conexion);
    $actualizado = $estudianteModel->actualizar($id, [
        'nombres'   => $nombres,
        'apellidos' => $apellidos
    ]);

    header("Location: " . BASE_URL . ($actualizado
        ? "views/estudiantes/agregar.php?status=updated"
        : "controllers/editarEstudiante.php?id=" . $id . "&status=error&msg=no_se_pudo_actualizar"));
    exit;
} catch (PDOException $e) {
    header("Location: " . BASE_URL . "controllers/editarEstudiante.php?id=" . $id . "&status=error&msg=" . urlencode($e->getMessage()));
    exit;
}