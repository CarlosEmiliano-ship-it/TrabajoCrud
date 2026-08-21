<?php
require_once __DIR__ . "/../config/conexion.php";
require_once __DIR__ . "/../models/Tarea.php";

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    $tareaModel = new Tarea($conexion);
    if ($tareaModel->eliminar($id)) {
        header("Location: " . BASE_URL . "index.php?status=deleted");
    } else {
        header("Location: " . BASE_URL . "index.php?status=error&msg=no_se_pudo_eliminar");
    }
} else {
    header("Location: " . BASE_URL . "index.php?status=error&msg=id_invalido");
}
exit;