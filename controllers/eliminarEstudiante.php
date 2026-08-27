<?php
require_once __DIR__ . "/../config/conexion.php";
require_once __DIR__ . "/../models/Estudiante.php";

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    header("Location: " . BASE_URL . "views/estudiantes/agregar.php?status=error&msg=id_invalido");
    exit;
}

try {
    $estudianteModel = new Estudiante($conexion);
    $eliminado = $estudianteModel->eliminar($id);

    header("Location: " . BASE_URL . "views/estudiantes/agregar.php?status=" . ($eliminado ? 'deleted' : 'error'));
    exit;

} catch (PDOException $e) {
    header("Location: " . BASE_URL . "views/estudiantes/agregar.php?status=error&msg=" . urlencode($e->getMessage()));
    exit;
}