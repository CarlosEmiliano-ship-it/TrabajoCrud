<?php
require_once "../../config/conexion.php";
require_once "../../models/Tarea.php";
require_once "../../models/Empleado.php";

$tareaModel    = new Tarea($conexion);
$empleadoModel = new Empleado($conexion);

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
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
    <title>Editar Tarea</title>
    <link rel="stylesheet" href="../../assets/css/src/output.css">
</head>
<body>
    <?php include_once "../includes/header.php"; ?>

    <main class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8 py-4">
        <div class="bg-zinc-200 backdrop-blur-xl shadow-lg p-4 max-w-lg mx-auto rounded-xl">
            <h2 class="uppercase text-xl font-bold text-center py-4">Editar Tarea</h2>

            <?php if (isset($_GET['status']) && $_GET['status'] === 'error'): ?>
                <div class="bg-red-100 text-red-800 text-center p-3 rounded-md mb-4">
                    Error: <?= htmlspecialchars($_GET['msg'] ?? 'No se pudo actualizar la tarea') ?>
                </div>
            <?php endif; ?>

            <form action="../../controllers/actualizarTarea.php" method="POST" class="flex flex-col gap-4">
                <input type="hidden" name="id" value="<?= (int) $tarea['id'] ?>">

                <input type="text" name="titulo" value="<?= htmlspecialchars($tarea['titulo']) ?>"
                       placeholder="Título de la tarea" class="border border-gray-300 rounded-md p-2">

                <textarea name="descripcion" rows="3" placeholder="Descripción"
                          class="border border-gray-300 rounded-md p-2"><?= htmlspecialchars($tarea['descripcion'] ?? '') ?></textarea>

                <input type="date" name="fecha_limite" value="<?= htmlspecialchars($tarea['fecha_limite'] ?? '') ?>"
                       class="border border-gray-300 rounded-md p-2">

                <select name="id_empleado" class="border border-gray-300 rounded-md p-2">
                    <?php foreach ($empleados as $emp): ?>
                        <option value="<?= (int) $emp['id'] ?>"
                                <?= $emp['id'] == $tarea['id_empleado'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($emp['nombres'] . ' ' . $emp['apellidos']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <select name="estado" class="border border-gray-300 rounded-md p-2">
                    <?php foreach (['pendiente', 'en_progreso', 'completada'] as $opcion): ?>
                        <option value="<?= $opcion ?>" <?= $tarea['estado'] === $opcion ? 'selected' : '' ?>>
                            <?= ucfirst(str_replace('_', ' ', $opcion)) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <div class="flex gap-2">
                    <button type="submit" class="flex-1 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-xl">
                        Guardar cambios
                    </button>
                    <a href="../../index.php" class="flex-1 bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-4 rounded-xl text-center">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </main>

    <?php include_once "../includes/footer.php"; ?>
</body>
</html>