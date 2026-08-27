<?php
require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/../models/Estudiante.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $nombre_completo = trim($_POST['nombre_completo'] ?? '');
    $fecha_nacimiento = trim($_POST['fecha_nacimiento'] ?? '');
    $fecha_salida = !empty($_POST['fecha_salida']) ? trim($_POST['fecha_salida']) : null;
    $id_curso = isset($_POST['id_curso']) ? (int)$_POST['id_curso'] : 0;

    // Validación de campos obligatorios
    if ($id <= 0 || empty($nombre_completo) || empty($fecha_nacimiento) || $id_curso <= 0) {
        header("Location: editarEstudiante.php?id={$id}&status=error&msg=campos_incompletos");
        exit;
    }

    // Estructurar los datos en el array que exige Estudiante::actualizar($id, $datos)
    $datos = [
        'nombre_completo'  => $nombre_completo,
        'fecha_nacimiento' => $fecha_nacimiento,
        'fecha_salida'     => $fecha_salida,
        'id_curso'         => $id_curso
    ];

    $estudianteModel = new Estudiante($conexion);
    
    // Pasar el ID y el array $datos
    $resultado = $estudianteModel->actualizar($id, $datos);

    if ($resultado) {
        header("Location: ../views/estudiantes/agregar.php?status=success");
        exit;
    } else {
        header("Location: editarEstudiante.php?id={$id}&status=error&msg=error_al_actualizar");
        exit;
    }
} else {
    header("Location: ../views/estudiantes/agregar.php");
    exit;
}