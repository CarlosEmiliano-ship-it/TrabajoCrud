<?php
require_once "../config/conexion.php";
require_once "../models/Tarea.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: " . BASE_URL . "index.php");
    exit;
}

$id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
if ($id <= 0) {
    header("Location: " . BASE_URL . "index.php?status=error&msg=id_invalido");
    exit;
}

$titulo      = trim($_POST['titulo'] ?? '');
$descripcion = trim($_POST['descripcion'] ?? '');
$fechaLimite = trim($_POST['fecha_limite'] ?? '');
$idEmpleado  = isset($_POST['id_empleado']) ? (int) $_POST['id_empleado'] : 0;
$estado      = trim($_POST['estado'] ?? 'pendiente');

if ($titulo === '' || $idEmpleado <= 0) {
    header("Location: " . BASE_URL . "views/tareas/editar.php?id=" . $id . "&status=error&msg=campos_incompletos");
    exit;
}

try {
    $tareaModel = new Tarea($conexion);

    $actualizado = $tareaModel->actualizar($id, [
        'titulo'       => $titulo,
        'descripcion'  => $descripcion,
        'fecha_limite' => $fechaLimite ?: null,
        'id_empleado'  => $idEmpleado,
        'estado'       => $estado,
    ]);

    header("Location: " . BASE_URL . ($actualizado
        ? "index.php?status=updated"
        : "views/tareas/editar.php?id=" . $id . "&status=error&msg=no_se_pudo_actualizar"));
    exit;

} catch (PDOException $e) {
    header("Location: " . BASE_URL . "views/tareas/editar.php?id=" . $id . "&status=error&msg=" . urlencode($e->getMessage()));
    exit;
}