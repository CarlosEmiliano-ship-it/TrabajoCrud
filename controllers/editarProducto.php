<?php
require_once __DIR__ . "/../config/conexion.php";
require_once __DIR__ . "/../models/Producto.php";
require_once __DIR__ . "/../models/Proveedor.php";

$productoModel = new Producto($conexion);
$proveedorModel = new Proveedor($conexion);

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$producto = $productoModel->obtenerPorId($id);

if (!$producto) {
    header("Location: " . BASE_URL . "views/productos/agregar.php?status=error&msg=producto_no_encontrado");
    exit;
}

$proveedores = $proveedorModel->obtenerTodos();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/src/output.css">
</head>
<body>
    <?php include_once __DIR__ . "/../views/includes/header.php"; ?>

    <main class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8 py-4">
        <div class="bg-zinc-200 shadow-lg p-4 max-w-lg mx-auto rounded-lg">
            <h2 class="uppercase text-xl font-bold text-center py-4">Editar Producto</h2>
            
            <?php if (isset($_GET['status']) && $_GET['status'] === 'error'): ?>
                <div class="bg-red-100 text-red-800 text-center p-3 rounded-md mb-4">
                    Error: <?= htmlspecialchars($_GET['msg'] ?? 'No se pudo actualizar') ?>
                </div>
            <?php endif; ?>

            <form action="actualizarProducto.php" method="POST" class="flex flex-col gap-4">
                <input type="hidden" name="id" value="<?= (int)$producto['id'] ?>">

                <label class="text-sm text-gray-600 -mb-2">Nombre del producto:</label>
                <input type="text" name="nombre_producto" value="<?= htmlspecialchars($producto['nombre_producto']) ?>"
                       placeholder="Nombre del producto" class="border border-gray-300 rounded-md p-2" required>

                <label class="text-sm text-gray-600 -mb-2">Fecha de Creación/Ingreso:</label>
                <input type="date" name="fecha_creacion" value="<?= htmlspecialchars($producto['fecha_creacion']) ?>"
                       class="border border-gray-300 rounded-md p-2" required>

                <label class="text-sm text-gray-600 -mb-2">Fecha de Caducidad (Opcional):</label>
                <input type="date" name="fecha_caducidad" value="<?= htmlspecialchars($producto['fecha_caducidad'] ?? '') ?>"
                       class="border border-gray-300 rounded-md p-2">

                <label class="text-sm text-gray-600 -mb-2">Precio ($):</label>
                <input type="number" step="0.01" name="precio" value="<?= htmlspecialchars($producto['precio']) ?>"
                       placeholder="Precio" class="border border-gray-300 rounded-md p-2" required>

                <label class="text-sm text-gray-600 -mb-2">Stock:</label>
                <input type="number" name="stock" value="<?= htmlspecialchars($producto['stock'] ?? 0) ?>"
                       placeholder="Stock" class="border border-gray-300 rounded-md p-2" required>

                <label class="text-sm text-gray-600 -mb-2">Proveedor:</label>
                <select name="proveedor_id" class="border border-gray-300 rounded-md p-2" required>
                    <option value="">-- Seleccionar Proveedor --</option>
                    <?php foreach ($proveedores as $prov): ?>
                        <option value="<?= (int)$prov['id'] ?>" <?= ($producto['proveedor_id'] == $prov['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($prov['nombre_proveedor']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <div class="flex gap-2 mt-2">
                    <button type="submit" class="flex-1 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-xl cursor-pointer">
                        Guardar cambios
                    </button>
                    <a href="<?= BASE_URL ?>views/productos/agregar.php" class="flex-1 bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-4 rounded-xl text-center">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </main>

    <?php include_once __DIR__ . "/../views/includes/footer.php"; ?>
</body>
</html>