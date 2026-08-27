<?php
require_once __DIR__ . "/config/conexion.php";
require_once __DIR__ . "/models/Tarea.php";
require_once __DIR__ . "/models/Empleado.php";

$tareaModel = new Tarea($conexion);
$empleadoModel = new Empleado($conexion);

$tareas = $tareaModel->obtenerTodas();
$empleados = $empleadoModel->obtenerTodos();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Tareas</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/output.css">
</head>
<body>
    <?php include_once __DIR__ . "/views/includes/header.php"; ?>

    <main class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8 py-4">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <!-- Columna izquierda: Formulario -->
            <div class="bg-zinc-200 shadow-lg p-4 w-full rounded-lg">
                <h2 class="uppercase text-xl font-bold text-center py-4">Agregar Tarea</h2>

                <?php if (isset($_GET['status'])): ?>
                    <?php if ($_GET['status'] === 'success'): ?>
                        <div class="bg-green-100 text-green-800 text-center p-3 rounded-md mb-4">Tarea registrada correctamente.</div>
                    <?php elseif ($_GET['status'] === 'updated'): ?>
                        <div class="bg-green-100 text-green-800 text-center p-3 rounded-md mb-4">Tarea actualizada correctamente.</div>
                    <?php elseif ($_GET['status'] === 'deleted'): ?>
                        <div class="bg-green-100 text-green-800 text-center p-3 rounded-md mb-4">Tarea eliminada correctamente.</div>
                    <?php elseif ($_GET['status'] === 'error'): ?>
                        <div class="bg-red-100 text-red-800 text-center p-3 rounded-md mb-4">
                            Error: <?= htmlspecialchars($_GET['msg'] ?? 'Operación no completada') ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

                <form action="<?= BASE_URL ?>controllers/controllerTareas.php" method="POST" class="flex flex-col gap-4 max-w-sm mx-auto">
                    <input type="text" name="titulo" placeholder="Título de la tarea" class="border border-gray-300 rounded-md p-2" required>
                    
                    <textarea name="descripcion" placeholder="Descripción de la tarea" class="border border-gray-300 rounded-md p-2 h-24" required></textarea>
                    
                    <label class="text-sm text-gray-600 font-bold -mb-2">Fecha límite:</label>
                    <input type="date" name="fecha_limite" class="border border-gray-300 rounded-md p-2" required>

                    <select name="id_empleado" class="border border-gray-300 rounded-md p-2" required>
                        <option value="">-- Selecciona un Empleado --</option>
                        <?php foreach ($empleados as $emp): ?>
                            <option value="<?= (int)$emp['id'] ?>">
                                <?= htmlspecialchars($emp['nombres'] . ' ' . $emp['apellidos']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <select name="estado" class="border border-gray-300 rounded-md p-2" required>
                        <option value="pendiente">Pendiente</option>
                        <option value="en_proceso">En Proceso</option>
                        <option value="completada">Completada</option>
                    </select>

                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 cursor-pointer text-white font-bold py-2 px-4 rounded-xl">
                        Guardar Tarea
                    </button>
                </form>
            </div>

            <!-- Columna derecha: Listado -->
            <div class="bg-zinc-200 shadow-lg p-4 w-full rounded-lg">
                <h2 class="uppercase text-xl font-bold text-center py-4">Tareas registradas</h2>

                <?php if (empty($tareas)): ?>
                    <p class="text-center text-gray-500 py-6">No hay tareas pendientes.</p>
                <?php else: ?>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 max-h-[700px] overflow-y-auto pr-1">
                        <?php foreach ($tareas as $tar): ?>
                            <div class="bg-white rounded-xl shadow flex flex-col justify-between p-4">
                                <div>
                                    <div class="flex justify-between items-center mb-2">
                                        <h3 class="font-bold text-lg text-gray-800"><?= htmlspecialchars($tar['titulo']) ?></h3>
                                        <span class="text-xs px-2 py-1 rounded-full font-bold uppercase
                                            <?= $tar['estado'] === 'completada' ? 'bg-green-200 text-green-800' : ($tar['estado'] === 'en_proceso' ? 'bg-yellow-200 text-yellow-800' : 'bg-red-200 text-red-800') ?>">
                                            <?= str_replace('_', ' ', $tar['estado']) ?>
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600 mb-2"><?= htmlspecialchars($tar['descripcion']) ?></p>
                                    <p class="text-xs text-gray-500 font-semibold">Asignado a: <?= htmlspecialchars($tar['empleado_nombre']) ?></p>
                                    <p class="text-xs text-gray-500 font-semibold">Límite: <?= htmlspecialchars($tar['fecha_limite']) ?></p>
                                </div>

                                <div class="flex gap-2 pt-4">
                                    <a href="<?= BASE_URL ?>views/tareas/editar.php?id=<?= (int)$tar['id'] ?>"
                                       class="flex-1 bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-bold py-1 px-3 rounded-md text-center">
                                        Editar
                                    </a>

                                    <button type="button"
                                            onclick="abrirModalEliminarTarea(<?= (int)$tar['id'] ?>, '<?= htmlspecialchars($tar['titulo'], ENT_QUOTES) ?>')"
                                            class="flex-1 bg-red-500 hover:bg-red-600 text-white text-sm font-bold py-1 px-3 rounded-md text-center cursor-pointer">
                                        Eliminar
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </main>

    <!-- Modal de confirmación para eliminar tarea -->
    <div id="modalEliminarTarea" class="hidden fixed inset-0 bg-black/50 items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-lg p-6 max-w-sm w-full mx-4">
            <h3 class="text-lg font-bold text-center mb-2">¿Eliminar Tarea?</h3>
            <p class="text-center text-gray-600 mb-6">
                Estás a punto de borrar "<span id="tituloTareaEliminar" class="font-bold"></span>". Esta acción no se puede deshacer.
            </p>
            <div class="flex gap-3">
                <button type="button" onclick="cerrarModalEliminarTarea()"
                        class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-xl cursor-pointer">
                    Cancelar
                </button>
                <a id="linkConfirmarEliminarTarea" href="#"
                   class="flex-1 bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-xl text-center">
                    Sí, eliminar
                </a>
            </div>
        </div>
    </div>

    <script>
        function abrirModalEliminarTarea(id, titulo) {
            document.getElementById('tituloTareaEliminar').textContent = titulo;
            document.getElementById('linkConfirmarEliminarTarea').href = '<?= BASE_URL ?>controllers/eliminarTarea.php?id=' + id;
            const modal = document.getElementById('modalEliminarTarea');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function cerrarModalEliminarTarea() {
            const modal = document.getElementById('modalEliminarTarea');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        document.getElementById('modalEliminarTarea').addEventListener('click', function (e) {
            if (e.target === this) cerrarModalEliminarTarea();
        });
    </script>

    <?php include_once __DIR__ . "/views/includes/footer.php"; ?>
</body>
</html>