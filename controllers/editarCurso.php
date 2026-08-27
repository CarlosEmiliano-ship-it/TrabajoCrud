<?php
require_once __DIR__ . "/../config/conexion.php";
require_once __DIR__ . "/../models/Curso.php";

$cursoModel = new Curso($conexion);
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$curso = $cursoModel->obtenerPorId($id);

if (!$curso) {
    header("Location: " . BASE_URL . "views/cursos/agregar.php?status=error&msg=curso_no_encontrado");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Curso</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/src/output.css">
</head>
<body>
    <?php include_once __DIR__ . "/../views/includes/header.php"; ?>

    <main class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8 py-4">
        <div class="bg-zinc-200 shadow-lg p-4 max-w-lg mx-auto rounded-lg">
            <h2 class="uppercase text-xl font-bold text-center py-4">Editar Curso</h2>
            
            <?php if (isset($_GET['status']) && $_GET['status'] === 'error'): ?>
                <div class="bg-red-100 text-red-800 text-center p-3 rounded-md mb-4">
                    Error: <?= htmlspecialchars($_GET['msg'] ?? 'No se pudo actualizar') ?>
                </div>
            <?php endif; ?>

            <form action="actualizarCurso.php" method="POST" class="flex flex-col gap-4">
                <input type="hidden" name="id" value="<?= (int)$curso['id'] ?>">

                <input type="text" name="nombre_curso" value="<?= htmlspecialchars($curso['nombre_curso']) ?>"
                       placeholder="Nombre del curso" class="border border-gray-300 rounded-md p-2" required>

                <textarea name="descripcion" placeholder="Descripción"
                          class="border border-gray-300 rounded-md p-2" rows="3"><?= htmlspecialchars($curso['descripcion'] ?? '') ?></textarea>

                <div class="flex gap-2">
                    <button type="submit" class="flex-1 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-xl cursor-pointer">
                        Guardar cambios
                    </button>
                    <a href="<?= BASE_URL ?>views/cursos/agregar.php" class="flex-1 bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-4 rounded-xl text-center">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </main>

    <?php include_once __DIR__ . "/../views/includes/footer.php"; ?>
</body>
</html>