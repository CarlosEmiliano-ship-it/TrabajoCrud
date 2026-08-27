<?php
require_once __DIR__ . "/../../config/conexion.php";
require_once __DIR__ . "/../../models/Proveedor.php";

$proveedorModel = new Proveedor($conexion);
$proveedores = $proveedorModel->obtenerTodos();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Proveedores</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/src/output.css">
</head>
<body>
    <?php include_once __DIR__ . "/../includes/header.php"; ?>

    <main class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8 py-4 hws">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

            <!-- Columna izquierda: formulario -->
            <div class="bg-zinc-200 shadow-lg p-4 w-full rounded-lg">
                <h2 class="uppercase text-xl font-bold text-center py-4">Agregar Proveedor</h2>

                <?php if (isset($_GET['status'])): ?>
                    <?php if ($_GET['status'] === 'success'): ?>
                        <div class="bg-green-100 text-green-800 text-center p-3 rounded-md mb-4">Proveedor agregado correctamente.</div>
                    <?php elseif ($_GET['status'] === 'updated'): ?>
                        <div class="bg-green-100 text-green-800 text-center p-3 rounded-md mb-4">Proveedor actualizado correctamente.</div>
                    <?php elseif ($_GET['status'] === 'deleted'): ?>
                        <div class="bg-green-100 text-green-800 text-center p-3 rounded-md mb-4">Proveedor eliminado correctamente.</div>
                    <?php elseif ($_GET['status'] === 'error'): ?>
                        <div class="bg-red-100 text-red-800 text-center p-3 rounded-md mb-4">
                            Error: <?= htmlspecialchars($_GET['msg'] ?? 'No se pudo completar la operación') ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

                <p class="text-center py-2">Formulario para registrar un nuevo proveedor</p>
                <form action="<?= BASE_URL ?>controllers/controllerProveedores.php" method="POST" class="flex flex-col gap-4 max-w-sm mx-auto">
                    <input type="text" name="nombre_proveedor" placeholder="Nombre del proveedor" class="border border-gray-300 rounded-md p-2" required>
                    <input type="text" name="contacto" placeholder="Nombre del contacto" class="border border-gray-300 rounded-md p-2" required>
                    <input type="text" name="telefono" placeholder="Teléfono" class="border border-gray-300 rounded-md p-2" required>

                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 cursor-pointer text-white font-bold py-2 px-4 rounded-xl">Agregar Proveedor</button>
                </form>
            </div>

            <!-- Columna derecha: listado -->
            <div class="bg-zinc-200 shadow-lg p-4 w-full rounded-lg">
                <h2 class="uppercase text-xl font-bold text-center py-4">Listado de Proveedores</h2>

                <?php if (empty($proveedores)): ?>
                    <p class="text-center text-gray-500 py-6">Aún no hay proveedores registrados.</p>
                <?php else: ?>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 max-h-[700px] overflow-y-auto pr-1">
                        <?php foreach ($proveedores as $prov): ?>
                            <div class="bg-white rounded-xl shadow flex flex-col overflow-hidden p-4">
                                <div class="flex-1 mb-4">
                                    <p class="font-bold text-center text-lg text-blue-700"><?= htmlspecialchars($prov['nombre_proveedor']) ?></p>
                                    <p class="text-sm text-gray-800 font-semibold text-center mt-2">Contacto: <?= htmlspecialchars($prov['contacto']) ?></p>
                                    <p class="text-xs text-gray-600 text-center mt-1">Teléfono: <?= htmlspecialchars($prov['telefono']) ?></p>
                                </div>

                                <div class="flex gap-2">
                                    <a href="<?= BASE_URL ?>controllers/editarProveedor.php?id=<?= (int)$prov['id'] ?>"
                                       class="flex-1 bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-bold py-1 px-3 rounded-md text-center">
                                        Editar
                                    </a>
                                    <button type="button"
                                            onclick="abrirModalEliminar(<?= (int)$prov['id'] ?>, '<?= htmlspecialchars($prov['nombre_proveedor'], ENT_QUOTES) ?>')"
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
            <h3 class="text-lg font-bold text-center mb-2">¿Eliminar proveedor?</h3>
            <p class="text-center text-gray-600 mb-6">
                Estás a punto de eliminar a <span id="nombreEliminar" class="font-bold"></span>.
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
            document.getElementById('linkConfirmarEliminar').href = '<?= BASE_URL ?>controllers/eliminarProveedor.php?id=' + id;
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