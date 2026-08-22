<?php
require_once "../config/conexion.php";
require_once "../models/Tarea.php";

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0) {
    header("Location: " . BASE_URL . "index.php?status=error&msg=id_invalido");
    exit;
}

try {
    $tareaModel = new Tarea($conexion);
    $eliminado = $tareaModel->eliminar($id);

    header("Location: " . BASE_URL . "index.php?status=" . ($eliminado ? 'deleted' : 'error'));
    exit;

} catch (PDOException $e) {
    header("Location: " . BASE_URL . "index.php?status=error&msg=" . urlencode($e->getMessage()));
    exit;
}