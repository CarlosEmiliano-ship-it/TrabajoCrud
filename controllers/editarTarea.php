<?php
require_once __DIR__ . "/../config/conexion.php";
require_once __DIR__ . "/../models/Tarea.php";
require_once __DIR__ . "/../models/Empleado.php";

$tareaModel = new Tarea($conexion);
$empleadoModel = new Empleado($conexion);

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$tarea = $tareaModel->obtenerPorId($id);

if (!$tarea) {
    header("Location: " . BASE_URL . "index.php?status=error&msg=tarea_no_encontrada");
    exit;
}

$empleados = $empleadoModel->obtenerTodos();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Tarea</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/src/output.css">
</head>
<body>
    <?php include_once __DIR__ . "/../views/includes/header.php"; ?>

    <main class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8 py-4">
        <div class="bg-zinc-200 shadow-lg p-4 max-w-lg mx-auto rounded-lg">
            <h2 class="uppercase text-xl font-bold text-center py-4">Editar Tarea</h2>

            <form action="actualizarTarea.php" method="POST" class="flex flex-col gap-4">
                <input type="hidden" name="id" value="<?= (int)$tarea['id'] ?>">

                <input type="text" name="titulo" value="<?= htmlspecialchars($tarea['titulo']) ?>"
                       placeholder="Título" class="border border-gray-300 rounded-md p-2" required>

                <textarea name="descripcion" placeholder="Descripción" class="border border-gray-300 rounded-md p-2 h-24" required><?= htmlspecialchars($tarea['descripcion']) ?></textarea>

                <input type="date" name="fecha_limite" value="<?= htmlspecialchars($tarea['fecha_limite']) ?>"
                       class="border border-gray-300 rounded-md p-2" required>

                <select name="id_empleado" class="border border-gray-300 rounded-md p-2" required>
                    <?php foreach ($empleados as $emp): ?>
                        <option value="<?= (int)$emp['id'] ?>" <?= $emp['id'] == $tarea['id_empleado'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($emp['nombres'] . ' ' . $emp['apellidos']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <select name="estado" class="border border-gray-300 rounded-md p-2" required>
                    <option value="pendiente" <?= $tarea['estado'] === 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
                    <option value="en_proceso" <?= $tarea['estado'] === 'en_proceso' ? 'selected' : '' ?>>En Proceso</option>
                    <option value="completada" <?= $tarea['estado'] === 'completada' ? 'selected' : '' ?>>Completada</option>
                </select>

                <div class="flex gap-2">
                    <button type="submit" class="flex-1 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-xl cursor-pointer">
                        Guardar Cambios
                    </button>
                    <a href="<?= BASE_URL ?>index.php" class="flex-1 bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-4 rounded-xl text-center">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </main>

    <?php include_once __DIR__ . "/../views/includes/footer.php"; ?>
</body>
</html>