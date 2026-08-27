<?php
require_once __DIR__ . "/../config/conexion.php";
require_once __DIR__ . "/../models/Curso.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: " . BASE_URL . "views/cursos/agregar.php");
    exit;
}

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
if ($id <= 0) {
    header("Location: " . BASE_URL . "views/cursos/agregar.php?status=error&msg=id_invalido");
    exit;
}

$nombre      = trim($_POST['nombre_curso'] ?? '');
$descripcion = trim($_POST['descripcion'] ?? '');

if ($nombre === '') {
    header("Location: " . BASE_URL . "controllers/editarCurso.php?id=" . $id . "&status=error&msg=campos_incompletos");
    exit;
}

try {
    $cursoModel = new Curso($conexion);
    $actualizado = $cursoModel->actualizar($id, [
        'nombre_curso' => $nombre,
        'descripcion'  => $descripcion
    ]);

    header("Location: " . BASE_URL . ($actualizado
        ? "views/cursos/agregar.php?status=updated"
        : "controllers/editarCurso.php?id=" . $id . "&status=error&msg=no_se_pudo_actualizar"));
    exit;
} catch (PDOException $e) {
    header("Location: " . BASE_URL . "controllers/editarCurso.php?id=" . $id . "&status=error&msg=" . urlencode($e->getMessage()));
    exit;
}