<?php
$paginaActual = basename($_SERVER['PHP_SELF']);
?>

<header class="mx-auto px-2 sm:px-6 lg:px-8 py-4">
    <nav class="bg-amber-500">
        <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
            <div class="relative flex items-center justify-between h-16">
                <div class="flex-1 flex items-center justify-center sm:items-stretch sm:justify-start">
                    <div class="flex items-center">
                        <img class="block lg:hidden h-8 w-auto" src="<?= BASE_URL ?>assets/img/img/si.webp" alt="Logo">
                        <img class="hidden lg:block h-8 w-auto" src="<?= BASE_URL ?>assets/img/img/si.webp" alt="Logo">
                    </div>
                    <div class="hidden sm:block sm:ml-6">
                        <div class="flex space-x-4">
                            <a href="<?= BASE_URL ?>index.php"
                               class="px-3 py-2 rounded-md text-sm font-medium <?= $paginaActual === 'index.php' ? 'bg-gray-700 text-white' : 'text-white hover:bg-gray-700 hover:text-white' ?>">
                                Tareas
                            </a>
                            <a href="<?= BASE_URL ?>views/empleados/agregar.php"
                               class="px-3 py-2 rounded-md text-sm font-medium <?= $paginaActual === 'agregar.php' ? 'bg-gray-700 text-white' : 'text-white hover:bg-gray-700 hover:text-white' ?>">
                                Empleados
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8 py-4 bg-zinc-200 shadow-lg p-4">
        <h1 class="uppercase text-2xl font-bold text-center py-4">Control de tareas en PHP</h1>
        <p class="text-center text-ellipsis py-5">Gestión de asignaciones por empleado</p>
    </div>
</header>