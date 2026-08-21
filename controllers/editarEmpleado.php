<?php
require_once __DIR__ . "/../config/conexion.php";
require_once __DIR__ . "/../models/Empleado.php";

$empleadoModel = new Empleado($conexion);

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$empleado = $empleadoModel->obtenerPorId($id);

if (!$empleado) {
    header("Location: " . BASE_URL . "views/empleados/agregar.php?status=error&msg=empleado_no_encontrado");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Empleado</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/src/output.css">
</head>
<body>
    <?php include_once __DIR__ . "/../views/includes/header.php"; ?>

    <main class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8 py-4">
        <div class="bg-zinc-200 shadow-lg p-4 max-w-lg mx-auto rounded-lg">
            <h2 class="uppercase text-xl font-bold text-center py-4">Editar Empleado</h2>
            
            <?php if (isset($_GET['status']) && $_GET['status'] === 'error'): ?>
                <div class="bg-red-100 text-red-800 text-center p-3 rounded-md mb-4">
                    Error: <?= htmlspecialchars($_GET['msg'] ?? 'No se pudo actualizar') ?>
                </div>
            <?php endif; ?>

            <p class="text-center py-2">Actualiza los datos de <?= htmlspecialchars($empleado['nombres']) ?></p>

            <form action="actualizarEmpleado.php" method="POST" class="flex flex-col gap-4" enctype="multipart/form-data">

                <input type="hidden" name="id" value="<?= (int) $empleado['id'] ?>">
                <input type="hidden" name="imagen_actual" value="<?= htmlspecialchars($empleado['imagen'] ?? '') ?>">

                <input type="text" name="nombres" value="<?= htmlspecialchars($empleado['nombres']) ?>"
                       placeholder="Nombre del empleado" class="border border-gray-300 rounded-md p-2" required>

                <input type="text" name="apellidos" value="<?= htmlspecialchars($empleado['apellidos']) ?>"
                       placeholder="Apellido del empleado" class="border border-gray-300 rounded-md p-2" required>

                <input type="date" name="fecha_nac" value="<?= htmlspecialchars($empleado['fecha_nac']) ?>"
                       class="border border-gray-300 rounded-md p-2" required>

                <input type="number" step="0.01" name="salario" value="<?= htmlspecialchars($empleado['salario']) ?>"
                       placeholder="Salario del empleado" class="border border-gray-300 rounded-md p-2" required>

                <input type="text" name="puesto" value="<?= htmlspecialchars($empleado['puesto'] ?? '') ?>"
                       placeholder="Puesto del empleado" class="border border-gray-300 rounded-md p-2" required>

                <?php if (!empty($empleado['imagen'])): ?>
                    <img src="<?= BASE_URL . htmlspecialchars($empleado['imagen']) ?>"
                         alt="Foto actual" class="w-20 h-20 object-cover rounded-md mx-auto">
                <?php endif; ?>

                <label class="text-sm text-gray-600">Dejar en blanco para conservar la foto actual:</label>
                <input type="file" name="foto" class="border border-gray-300 rounded-md p-2">

                <div class="flex gap-2">
                    <button type="submit" class="flex-1 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-xl cursor-pointer">
                        Guardar cambios
                    </button>
                    <a href="<?= BASE_URL ?>views/empleados/agregar.php" class="flex-1 bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-4 rounded-xl text-center">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </main>

    <?php include_once __DIR__ . "/../views/includes/footer.php"; ?>
</body>
</html>