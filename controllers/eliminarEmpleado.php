<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../config/conexion.php";

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    header("Location: " . BASE_URL . "views/empleados/agregar.php?status=error&msg=" . urlencode("ID invalido"));
    exit;
}

try {
    // 1. Consultar la foto del empleado
    $stmt = $conexion->prepare("SELECT imagen FROM empleado WHERE id = :id LIMIT 1");
    $stmt->execute([':id' => $id]);
    $empleado = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($empleado) {
        // 2. Eliminar la foto física del servidor si existe
        if (!empty($empleado['imagen'])) {
            $rutaFoto = __DIR__ . '/../' . $empleado['imagen'];
            if (file_exists($rutaFoto) && is_file($rutaFoto)) {
                @unlink($rutaFoto);
            }
        }

        // 3. Borrar el registro de la base de datos
        $stmtDelete = $conexion->prepare("DELETE FROM empleado WHERE id = :id");
        $stmtDelete->execute([':id' => $id]);

        header("Location: " . BASE_URL . "views/empleados/agregar.php?status=deleted");
        exit;
    } else {
        header("Location: " . BASE_URL . "views/empleados/agregar.php?status=error&msg=" . urlencode("El empleado no existe"));
        exit;
    }

} catch (PDOException $e) {
    // Captura el error de clave foránea si el empleado tiene tareas asignadas
    $msg = "No se puede eliminar el empleado porque tiene tareas asignadas. Elimina o reasigna sus tareas primero.";
    header("Location: " . BASE_URL . "views/empleados/agregar.php?status=error&msg=" . urlencode($msg));
    exit;
} catch (Exception $e) {
    header("Location: " . BASE_URL . "views/empleados/agregar.php?status=error&msg=" . urlencode($e->getMessage()));
    exit;
}