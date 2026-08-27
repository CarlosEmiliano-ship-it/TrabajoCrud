<?php
require_once __DIR__ . "/../config/conexion.php";
require_once __DIR__ . "/../models/Estudiante.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombreCompleto  = trim($_POST['nombre_completo'] ?? '');
    $fechaNacimiento = trim($_POST['fecha_nacimiento'] ?? '');
    $fechaSalida     = trim($_POST['fecha_salida'] ?? '');
    $idCurso         = (int)($_POST['id_curso'] ?? 0);

    // Validación básica
    if ($nombreCompleto === '' || $fechaNacimiento === '' || $idCurso === 0) {
        header("Location: " . BASE_URL . "views/estudiantes/agregar.php?status=error&msg=campos_incompletos");
        exit;
    }

    // Si la fecha de salida viene vacía, la pasamos como nula
    $fechaSalida = $fechaSalida === '' ? null : $fechaSalida;

    try {
        $estudianteModel = new Estudiante($conexion);

        $guardado = $estudianteModel->agregar([
            'nombre_completo'  => $nombreCompleto,
            'fecha_nacimiento' => $fechaNacimiento,
            'fecha_salida'     => $fechaSalida,
            'id_curso'         => $idCurso
        ]);

        if ($guardado) {
            header("Location: " . BASE_URL . "views/estudiantes/agregar.php?status=success");
        } else {
            header("Location: " . BASE_URL . "views/estudiantes/agregar.php?status=error&msg=error_al_insertar");
        }
        exit;

    } catch (PDOException $e) {
        header("Location: " . BASE_URL . "views/estudiantes/agregar.php?status=error&msg=" . urlencode($e->getMessage()));
        exit;
    }
} else {
    header("Location: " . BASE_URL . "views/estudiantes/agregar.php");
    exit;
}