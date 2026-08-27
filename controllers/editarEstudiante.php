<?php
require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/../models/Estudiante.php';
require_once __DIR__ . '/../models/Curso.php';

$estudianteModel = new Estudiante($conexion);
$cursoModel = new Curso($conexion);

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$estudiante = $estudianteModel->obtenerPorId($id);
$cursos = $cursoModel->obtenerTodos();

if (!$estudiante) {
    header("Location: ../views/estudiantes/agregar.php?status=error&msg=estudiante_no_encontrado");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Estudiante</title>
    <link rel="stylesheet" href="../assets/css/src/output.css">
</head>
<body class="bg-gray-100 min-h-screen flex flex-col justify-between">
    <?php include_once __DIR__ . "/../views/includes/header.php"; ?>

    <main class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8 py-4 w-full flex-1">
        <div class="bg-zinc-200 shadow-lg p-6 max-w-lg mx-auto rounded-lg">
            <h2 class="uppercase text-xl font-bold text-center mb-6">Editar Estudiante</h2>
            
            <?php if (isset($_GET['status']) && $_GET['status'] === 'error'): ?>
                <div class="bg-red-100 text-red-800 text-center p-3 rounded-md mb-4">
                    Error: <?= htmlspecialchars($_GET['msg'] ?? 'No se pudo actualizar') ?>
                </div>
            <?php endif; ?>

            <form action="actualizarEstudiante.php" method="POST" class="flex flex-col gap-4">
                <input type="hidden" name="id" value="<?= (int)$estudiante['id'] ?>">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre Completo:</label>
                    <input type="text" name="nombre_completo" value="<?= htmlspecialchars($estudiante['nombre_completo'] ?? '') ?>"
                           placeholder="Nombre completo" class="w-full border border-gray-300 rounded-md p-2 bg-white" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de Nacimiento:</label>
                    <input type="date" name="fecha_nacimiento" value="<?= htmlspecialchars($estudiante['fecha_nacimiento'] ?? '') ?>"
                           class="w-full border border-gray-300 rounded-md p-2 bg-white" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de Salida (Opcional):</label>
                    <input type="date" name="fecha_salida" value="<?= htmlspecialchars($estudiante['fecha_salida'] ?? '') ?>"
                           class="w-full border border-gray-300 rounded-md p-2 bg-white">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Curso:</label>
                    <select name="id_curso" class="w-full border border-gray-300 rounded-md p-2 bg-white" required>
                        <option value="">-- Seleccionar Curso --</option>
                        <?php foreach ($cursos as $c): ?>
                            <option value="<?= (int)$c['id'] ?>" <?= (($estudiante['id_curso'] ?? $estudiante['curso_id'] ?? 0) == $c['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($c['nombre_curso']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="flex gap-3 mt-4">
                    <button type="submit" class="flex-1 bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-xl cursor-pointer">
                        Guardar cambios
                    </button>
                    <a href="../views/estudiantes/agregar.php" class="flex-1 bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-4 rounded-xl text-center">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </main>

    <?php include_once __DIR__ . "/../views/includes/footer.php"; ?>
</body>
</html>