<?php
require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/../models/Empleado.php';

$empleadoModel = new empleado($conexion);
$empleados = $empleadoModel->obtenerTodos();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Empleados</title>
    <link rel="stylesheet" href="../../assets/css/src/output.css">
</head>
<body>
    <?php include_once "../includes/header.php"; ?>

    <main class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8 py-4 hws">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-2">

            <!-- Columna izquierda: formulario -->
            <div class="bg-zinc-200 backdrop:blur-xl shadow-lg p-4 w-full">
                <h2 class="uppercase text-xl font-bold text-center py-4">Agregar Empleado</h2>

                <?php if (isset($_GET['status'])): ?>
                    <?php if ($_GET['status'] === 'success'): ?>
                        <div class="bg-green-100 text-green-800 text-center p-3 rounded-md mb-4">Empleado agregado correctamente.</div>
                    <?php elseif ($_GET['status'] === 'updated'): ?>
                        <div class="bg-green-100 text-green-800 text-center p-3 rounded-md mb-4">Empleado actualizado correctamente.</div>
                    <?php elseif ($_GET['status'] === 'deleted'): ?>
                        <div class="bg-green-100 text-green-800 text-center p-3 rounded-md mb-4">Empleado eliminado correctamente.</div>
                    <?php elseif ($_GET['status'] === 'error'): ?>
                        <div class="bg-red-100 text-red-800 text-center p-3 rounded-md mb-4">
                            Error: <?= htmlspecialchars($_GET['msg'] ?? 'No se pudo completar la operación') ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

                <p class="text-center text-ellipsis py-5">Formulario para agregar un nuevo empleado</p>
                <form action="../../controllers/controllerEmpleados.php" method="POST" class="flex flex-col gap-4 max-w-sm mx-auto" enctype="multipart/form-data">
                    <input type="text" name="nombre" placeholder="Nombre del empleado" class="border border-gray-300 rounded-md p-2">
                    <input type="text" name="apellido" placeholder="Apellido del empleado" class="border border-gray-300 rounded-md p-2">
                    <input type="date" name="fecha_nacimiento" class="border border-gray-300 rounded-md p-2">
                    <input type="text" name="salario" placeholder="Salario del empleado" class="border border-gray-300 rounded-md p-2">
                    <input type="text" name="puesto" placeholder="Puesto del empleado" class="border border-gray-300 rounded-md p-2">
                    <input type="file" name="foto" class="border border-gray-300 rounded-md p-2">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 cursor-pointer text-white font-bold py-2 px-4 rounded-xl">Agregar Empleado</button>
                </form>
            </div>

            <!-- Columna derecha: listado -->
            <div class="bg-zinc-200 backdrop:blur-xl shadow-lg p-4 w-full">
                <h2 class="uppercase text-xl font-bold text-center py-4">Empleados registrados</h2>

                <?php if (empty($empleados)): ?>
                    <p class="text-center text-gray-500 py-6">Aún no hay empleados registrados.</p>
                <?php else: ?>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 max-h-[700px] overflow-y-auto pr-1">
                        <?php foreach ($empleados as $emp): ?>
                            <div class="bg-white rounded-xl shadow flex flex-col overflow-hidden">
                                <img src="<?= BASE_URL . htmlspecialchars($emp['imagen'] ?? 'assets/img/default.png') ?>"
                                     alt="Foto de <?= htmlspecialchars($emp['nombres']) ?>"
                                     class="w-full h-40 object-cover">

                                <div class="p-3 flex-1">
                                    <p class="font-bold text-center"><?= htmlspecialchars($emp['nombres'] . ' ' . $emp['apellidos']) ?></p>
                                    <p class="text-sm text-gray-600 text-center"><?= htmlspecialchars($emp['puesto'] ?? 'Sin puesto') ?></p>
                                    <p class="text-sm text-gray-600 text-center">Salario: $<?= number_format((float)$emp['salario'], 2) ?></p>
                                    <p class="text-sm text-gray-600 text-center">Nacimiento: <?= htmlspecialchars($emp['fecha_nac']) ?></p>
                                </div>

                                <div class="flex gap-2 p-3 pt-0">
                                    <a href="editarEmpleado.php?id=<?= (int)$emp['id'] ?>"
                                       class="flex-1 bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-bold py-1 px-3 rounded-md text-center">
                                        Editar
                                    </a>
                                    <button type="button"
                                            onclick="abrirModalEliminar(<?= (int)$emp['id'] ?>, '<?= htmlspecialchars($emp['nombres'].' '.$emp['apellidos'], ENT_QUOTES) ?>')"
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

    <!-- Modal de confirmación para eliminar -->
    <div id="modalEliminar" class="hidden fixed inset-0 bg-black/50 items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-lg p-6 max-w-sm w-full mx-4">
            <h3 class="text-lg font-bold text-center mb-2">¿Eliminar empleado?</h3>
            <p class="text-center text-gray-600 mb-6">
                Estás a punto de eliminar a <span id="nombreEmpleadoEliminar" class="font-bold"></span>.
                Esta acción no se puede deshacer.
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
            document.getElementById('nombreEmpleadoEliminar').textContent = nombre;
            document.getElementById('linkConfirmarEliminar').href =
                '../../controllers/eliminarEmpleado.php?id=' + id;

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

    <?php include_once __DIR__ . '/includes/header.php'; ?>
</body>
</html>