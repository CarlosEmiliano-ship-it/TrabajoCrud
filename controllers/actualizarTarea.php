<?php
require_once __DIR__ . "/../config/conexion.php";
require_once __DIR__ . "/../models/Tarea.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id          = (int)($_POST['id'] ?? 0);
    $titulo      = trim($_POST['titulo'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $fechaLimite = trim($_POST['fecha_limite'] ?? '');
    $idEmpleado  = (int)($_POST['id_empleado'] ?? 0);
    $estado      = trim($_POST['estado'] ?? 'pendiente');

    if ($id > 0 && $titulo !== '' && $fechaLimite !== '' && $idEmpleado > 0) {
        $tareaModel = new Tarea($conexion);
        $actualizado = $tareaModel->actualizar($id, [
            'titulo'       => $titulo,
            'descripcion'  => $descripcion,
            'fecha_limite' => $fechaLimite,
            'id_empleado'  => $idEmpleado,
            'estado'       => $estado
        ]);

        if ($actualizado) {
            header("Location: " . BASE_URL . "index.php?status=updated");
            exit;
        }
    }
}

header("Location: " . BASE_URL . "index.php?status=error&msg=error_actualizar");
exit;