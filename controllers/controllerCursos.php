<?php
require_once __DIR__ . "/../config/conexion.php";
require_once __DIR__ . "/../models/Curso.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre      = trim($_POST['nombre_curso'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');

    if ($nombre === '') {
        header("Location: " . BASE_URL . "views/cursos/agregar.php?status=error&msg=campos_incompletos");
        exit;
    }

    try {
        $cursoModel = new Curso($conexion);
        $guardado = $cursoModel->agregar([
            'nombre_curso' => $nombre,
            'descripcion'  => $descripcion
        ]);

        header("Location: " . BASE_URL . "views/cursos/agregar.php?status=" . ($guardado ? "success" : "error"));
        exit;
    } catch (PDOException $e) {
        header("Location: " . BASE_URL . "views/cursos/agregar.php?status=error&msg=" . urlencode($e->getMessage()));
        exit;
    }
} else {
    header("Location: " . BASE_URL . "views/cursos/agregar.php");
    exit;
}