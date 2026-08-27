<?php
require_once __DIR__ . '/../../config/conexion.php';
require_once __DIR__ . '/../../models/Producto.php';
require_once __DIR__ . '/../../models/Proveedor.php';

$productoModel = new Producto($conexion);
$proveedorModel = new Proveedor($conexion);

$productos = $productoModel->obtenerTodos();
$proveedores = $proveedorModel->obtenerTodos();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos y Proveedores</title>
    <link rel="stylesheet" href="../../assets/css/src/output.css">
</head>
<body>
    <?php include_once "../includes/header.php"; ?>

    <main class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8 py-4 hws">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-2">

            <!-- Columna izquierda: formulario -->
            <div class="bg-zinc-200 backdrop:blur-xl shadow-lg p-4 w-full">
                <h2 class="uppercase text-xl font-bold text-center py-4">Agregar Producto</h2>

                <?php if (isset($_GET['status'])): ?>
                    <?php if ($_GET['status'] === 'success'): ?>
                        <div class="bg-green-100 text-green-800 text-center p-3 rounded-md mb-4">Operación realizada correctamente.</div>
                    <?php elseif ($_GET['status'] === 'deleted'): ?>
                        <div class="bg-green-100 text-green-800 text-center p-3 rounded-md mb-4">Producto eliminado correctamente.</div>
                    <?php elseif ($_GET['status'] === 'error'): ?>
                        <div class="bg-red-100 text-red-800 text-center p-3 rounded-md mb-4">
                            Error: <?= htmlspecialchars($_GET['msg'] ?? 'No se pudo completar la operación') ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

                <form action="../../controllers/controllerProductos.php" method="POST" class="flex flex-col gap-4 max-w-sm mx-auto">
                    <input type="text" name="nombre_producto" placeholder="Nombre del producto" class="border border-gray-300 rounded-md p-2" required>
                    
                    <label class="text-sm text-gray-600 -mb-2">Fecha de Creación/Ingreso:</label>
                    <input type="date" name="fecha_creacion" class="border border-gray-300 rounded-md p-2" required>
                    
                    <label class="text-sm text-gray-600 -mb-2">Fecha de Caducidad (Opcional):</label>
                    <input type="date" name="fecha_caducidad" class="border border-gray-300 rounded-md p-2">
                    
                    <input type="number" step="0.01" name="precio" placeholder="Precio ($)" class="border border-gray-300 rounded-md p-2" required>
                    
                    <input type="number" name="stock" placeholder="Cantidad en stock" class="border border-gray-300 rounded-md p-2" required>

                    <select name="proveedor_id" class="border border-gray-300 rounded-md p-2" required>
                        <option value="">-- Seleccionar Proveedor --</option>
                        <?php foreach ($proveedores as $prov): ?>
                            <option value="<?= (int)$prov['id'] ?>"><?= htmlspecialchars($prov['nombre_proveedor']) ?></option>
                        <?php endforeach; ?>
                    </select>

                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 cursor-pointer text-white font-bold py-2 px-4 rounded-xl">Agregar Producto</button>
                </form>
            </div>

            <!-- Columna derecha: listado -->
            <div class="bg-zinc-200 backdrop:blur-xl shadow-lg p-4 w-full">
                <h2 class="uppercase text-xl font-bold text-center py-4">Inventario de Productos</h2>

                <?php if (empty($productos)): ?>
                    <p class="text-center text-gray-500 py-6">Aún no hay productos registrados.</p>
                <?php else: ?>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 max-h-[700px] overflow-y-auto pr-1">
                        <?php foreach ($productos as $prod): ?>
                            <div class="bg-white rounded-xl shadow flex flex-col overflow-hidden">
                                <div class="p-4 flex-1">
                                    <p class="font-bold text-center text-lg text-green-700"><?= htmlspecialchars($prod['nombre_producto']) ?></p>
                                    <p class="text-sm text-gray-800 font-semibold text-center mt-2">Proveedor: <?= htmlspecialchars($prod['proveedor_nombre']) ?></p>
                                    <p class="text-sm text-gray-900 text-center font-bold mt-1">Precio: $<?= number_format((float)$prod['precio'], 2) ?></p>
                                    <p class="text-xs text-gray-600 text-center">Stock: <?= (int)$prod['stock'] ?> unidades</p>
                                    <p class="text-xs text-gray-500 text-center mt-2">Caduca: <?= $prod['fecha_caducidad'] ? htmlspecialchars($prod['fecha_caducidad']) : 'N/A' ?></p>
                                </div>

                                <div class="flex gap-2 p-3 pt-0">
                                    <a href="../../controllers/editarProducto.php?id=<?= (int)$prod['id'] ?>"
                                       class="flex-1 bg-amber-500 hover:bg-amber-600 text-white text-sm font-bold py-1 px-3 rounded-md text-center cursor-pointer">
                                        Editar
                                    </a>
                                    <button type="button"
                                            onclick="abrirModalEliminar(<?= (int)$prod['id'] ?>, '<?= htmlspecialchars($prod['nombre_producto'], ENT_QUOTES) ?>')"
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
            <h3 class="text-lg font-bold text-center mb-2">¿Eliminar producto?</h3>
            <p class="text-center text-gray-600 mb-6">
                Estás a punto de eliminar <span id="nombreProductoEliminar" class="font-bold"></span>.
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
            document.getElementById('nombreProductoEliminar').textContent = nombre;
            document.getElementById('linkConfirmarEliminar').href = '../../controllers/eliminarProducto.php?id=' + id;
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