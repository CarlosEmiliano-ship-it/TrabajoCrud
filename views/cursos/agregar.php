<?php
require_once __DIR__ . "/../../config/conexion.php";
require_once __DIR__ . "/../../models/Curso.php";

$cursoModel = new Curso($conexion);
$cursos = $cursoModel->obtenerTodos();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Cursos</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/src/output.css">
</head>
<body>
    <?php include_once __DIR__ . "/../includes/header.php"; ?>

    <main class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8 py-4">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

            <!-- Columna izquierda: Formulario -->
            <div class="bg-zinc-200 shadow-lg p-4 w-full rounded-lg">
                <h2 class="uppercase text-xl font-bold text-center py-4">Agregar Curso</h2>

                <?php if (isset($_GET['status'])): ?>
                    <?php if ($_GET['status'] === 'success'): ?>
                        <div class="bg-green-100 text-green-800 text-center p-3 rounded-md mb-4">Curso agregado correctamente.</div>
                    <?php elseif ($_GET['status'] === 'updated'): ?>
                        <div class="bg-green-100 text-green-800 text-center p-3 rounded-md mb-4">Curso actualizado correctamente.</div>
                    <?php elseif ($_GET['status'] === 'deleted'): ?>
                        <div class="bg-green-100 text-green-800 text-center p-3 rounded-md mb-4">Curso eliminado correctamente.</div>
                    <?php elseif ($_GET['status'] === 'error'): ?>
                        <div class="bg-red-100 text-red-800 text-center p-3 rounded-md mb-4">
                            Error: <?= htmlspecialchars($_GET['msg'] ?? 'No se pudo completar la operación') ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

                <p class="text-center py-2">Formulario para registrar un nuevo curso</p>
                <form action="<?= BASE_URL ?>controllers/controllerCursos.php" method="POST" class="flex flex-col gap-4 max-w-sm mx-auto">
                    <input type="text" name="nombre_curso" placeholder="Nombre del curso" class="border border-gray-300 rounded-md p-2" required>
                    <textarea name="descripcion" placeholder="Descripción del curso" class="border border-gray-300 rounded-md p-2 h-24" required></textarea>
                    
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 cursor-pointer text-white font-bold py-2 px-4 rounded-xl">Agregar Curso</button>
                </form>
            </div>

            <!-- Columna derecha: Listado -->
            <div class="bg-zinc-200 shadow-lg p-4 w-full rounded-lg">
                <h2 class="uppercase text-xl font-bold text-center py-4">Cursos registrados</h2>

                <?php if (empty($cursos)): ?>
                    <p class="text-center text-gray-500 py-6">Aún no hay cursos registrados.</p>
                <?php else: ?>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 max-h-[700px] overflow-y-auto pr-1">
                        <?php foreach ($cursos as $curso): ?>
                            <div class="bg-white rounded-xl shadow flex flex-col overflow-hidden p-4">
                                <div class="flex-1">
                                    <h3 class="font-bold text-center text-lg text-blue-700 mb-2"><?= htmlspecialchars($curso['nombre_curso']) ?></h3>
                                    <p class="text-sm text-gray-600 text-center mb-4"><?= htmlspecialchars($curso['descripcion'] ?? 'Sin descripción') ?></p>
                                </div>

                                <div class="flex gap-2">
                                    <a href="<?= BASE_URL ?>controllers/editarCurso.php?id=<?= (int)$curso['id'] ?>"
                                       class="flex-1 bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-bold py-1 px-3 rounded-md text-center">
                                        Editar
                                    </a>
                                    <button type="button"
                                            onclick="abrirModalEliminar(<?= (int)$curso['id'] ?>, '<?= htmlspecialchars($curso['nombre_curso'], ENT_QUOTES) ?>')"
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
            <h3 class="text-lg font-bold text-center mb-2">¿Eliminar curso?</h3>
            <p class="text-center text-gray-600 mb-6">
                Estás a punto de eliminar "<span id="nombreEliminar" class="font-bold"></span>".
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
            document.getElementById('nombreEliminar').textContent = nombre;
            document.getElementById('linkConfirmarEliminar').href = '<?= BASE_URL ?>controllers/eliminarCurso.php?id=' + id;
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
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') cerrarModalEliminar();
        });
    </script>

    <?php include_once __DIR__ . "/../includes/footer.php"; ?>
</body>
</html>