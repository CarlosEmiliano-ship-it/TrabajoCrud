<?php
require_once __DIR__ . "/../config/conexion.php";
require_once __DIR__ . "/../models/Tarea.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $titulo      = trim($_POST['titulo'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $fechaLimite = trim($_POST['fecha_limite'] ?? '');
    $idEmpleado  = (int)($_POST['id_empleado'] ?? 0);
    $estado      = trim($_POST['estado'] ?? 'pendiente');

    if ($titulo === '' || $fechaLimite === '' || $idEmpleado === 0) {
        header("Location: " . BASE_URL . "index.php?status=error&msg=campos_incompletos");
        exit;
    }

    try {
        $tareaModel = new Tarea($conexion);

        $guardado = $tareaModel->agregar([
            'titulo'       => $titulo,
            'descripcion'  => $descripcion,
            'fecha_limite' => $fechaLimite,
            'id_empleado'  => $idEmpleado,
            'estado'       => $estado
        ]);

        if ($guardado) {
            header("Location: " . BASE_URL . "index.php?status=success");
        } else {
            header("Location: " . BASE_URL . "index.php?status=error&msg=error_al_insertar");
        }
        exit;

    } catch (PDOException $e) {
        header("Location: " . BASE_URL . "index.php?status=error&msg=" . urlencode($e->getMessage()));
        exit;
    }
} else {
    header("Location: " . BASE_URL . "index.php");
    exit;
}