<?php
require_once __DIR__ . '/../../config/conexion.php';
require_once __DIR__ . '/../../models/Estudiante.php';
require_once __DIR__ . '/../../models/Curso.php';

$estudianteModel = new Estudiante($conexion);
$cursoModel = new Curso($conexion);

$estudiantes = $estudianteModel->obtenerTodos();
$cursos = $cursoModel->obtenerTodos();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estudiantes y Cursos</title>
    <link rel="stylesheet" href="../../assets/css/src/output.css">
</head>
<body>
    <?php include_once "../includes/header.php"; ?>

    <main class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8 py-4 hws">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-2">

            <!-- Columna izquierda: formulario -->
            <div class="bg-zinc-200 backdrop:blur-xl shadow-lg p-4 w-full">
                <h2 class="uppercase text-xl font-bold text-center py-4">Agregar Estudiante</h2>

                <?php if (isset($_GET['status'])): ?>
                    <?php if ($_GET['status'] === 'success'): ?>
                        <div class="bg-green-100 text-green-800 text-center p-3 rounded-md mb-4">Estudiante procesado correctamente.</div>
                    <?php elseif ($_GET['status'] === 'deleted'): ?>
                        <div class="bg-green-100 text-green-800 text-center p-3 rounded-md mb-4">Estudiante eliminado correctamente.</div>
                    <?php elseif ($_GET['status'] === 'error'): ?>
                        <div class="bg-red-100 text-red-800 text-center p-3 rounded-md mb-4">
                            Error: <?= htmlspecialchars($_GET['msg'] ?? 'No se pudo completar la operación') ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

                <form action="../../controllers/controllerEstudiantes.php" method="POST" class="flex flex-col gap-4 max-w-sm mx-auto">
                    <input type="text" name="nombre_completo" placeholder="Nombre completo" class="border border-gray-300 rounded-md p-2" required>
                    
                    <label class="text-sm text-gray-600 -mb-2">Fecha de Nacimiento:</label>
                    <input type="date" name="fecha_nacimiento" class="border border-gray-300 rounded-md p-2" required>
                    
                    <label class="text-sm text-gray-600 -mb-2">Fecha de Salida (Opcional):</label>
                    <input type="date" name="fecha_salida" class="border border-gray-300 rounded-md p-2">
                    
                    <select name="id_curso" class="border border-gray-300 rounded-md p-2" required>
                        <option value="">-- Seleccionar Curso --</option>
                        <?php foreach ($cursos as $c): ?>
                            <option value="<?= (int)$c['id'] ?>"><?= htmlspecialchars($c['nombre_curso']) ?></option>
                        <?php endforeach; ?>
                    </select>

                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 cursor-pointer text-white font-bold py-2 px-4 rounded-xl">Agregar Estudiante</button>
                </form>
            </div>

            <!-- Columna derecha: listado -->
            <div class="bg-zinc-200 backdrop:blur-xl shadow-lg p-4 w-full">
                <h2 class="uppercase text-xl font-bold text-center py-4">Estudiantes Registrados</h2>

                <?php if (empty($estudiantes)): ?>
                    <p class="text-center text-gray-500 py-6">Aún no hay estudiantes registrados.</p>
                <?php else: ?>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 max-h-[700px] overflow-y-auto pr-1">
                        <?php foreach ($estudiantes as $est): ?>
                            <div class="bg-white rounded-xl shadow flex flex-col overflow-hidden">
                                <div class="p-4 flex-1">
                                    <p class="font-bold text-center text-lg text-blue-900"><?= htmlspecialchars($est['nombre_completo']) ?></p>
                                    <p class="text-sm text-gray-800 font-semibold text-center mt-2">
                                        Curso: <?= htmlspecialchars($est['curso_nombre'] ?? 'Sin curso') ?>
                                    </p>
                                    <p class="text-xs text-gray-600 text-center mt-1">Nacimiento: <?= htmlspecialchars($est['fecha_nacimiento']) ?></p>
                                    <p class="text-xs text-gray-600 text-center">Salida: <?= !empty($est['fecha_salida']) ? htmlspecialchars($est['fecha_salida']) : 'En curso' ?></p>
                                </div>

                                <div class="flex gap-2 p-3 pt-0">
                                    <a href="../../controllers/editarEstudiante.php?id=<?= (int)$est['id'] ?>"
                                       class="flex-1 bg-amber-500 hover:bg-amber-600 text-white text-sm font-bold py-1 px-3 rounded-md text-center cursor-pointer">
                                        Editar
                                    </a>
                                    <button type="button"
                                            onclick="abrirModalEliminar(<?= (int)$est['id'] ?>, '<?= htmlspecialchars($est['nombre_completo'], ENT_QUOTES) ?>')"
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

    <!-- Modal de confirmación -->
    <div id="modalEliminar" class="hidden fixed inset-0 bg-black/50 items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-lg p-6 max-w-sm w-full mx-4">
            <h3 class="text-lg font-bold text-center mb-2">¿Eliminar estudiante?</h3>
            <p class="text-center text-gray-600 mb-6">
                Estás a punto de eliminar a <span id="nombreEstudianteEliminar" class="font-bold"></span>.
            </p>
            <div class="flex gap-3">
                <button type="button" onclick="cerrarModalEliminar()"
                        class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-xl cursor-pointer">
                    Cancelar
                </button>
                <a id="linkConfirmarEliminar" href="#"
                   class="flex-1 bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-xl text-center">
                    Sí, eliminar
                </a>
            </div>
        </div>
    </div>

    <script>
        function abrirModalEliminar(id, nombre) {
            document.getElementById('nombreEstudianteEliminar').textContent = nombre;
            document.getElementById('linkConfirmarEliminar').href = '../../controllers/eliminarEstudiante.php?id=' + id;
            const modal = document.getElementById('modalEliminar');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
        function cerrarModalEliminar() {
            const modal = document.getElementById('modalEliminar');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
        document.getElementById('modalEliminar').addEventListener('click', function (e) {
            if (e.target === this) cerrarModalEliminar();
        });
    </script>

    <?php include_once __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>