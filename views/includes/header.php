<?php
// Obtenemos la URL actual para saber en qué módulo estamos
$uriActual = $_SERVER['REQUEST_URI'];
?>

<header class="mx-auto px-2 sm:px-6 lg:px-8 py-4">
    <nav class="bg-amber-500 rounded-t-lg">
        <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
            <div class="relative flex items-center justify-between h-16">
                <div class="flex-1 flex items-center justify-center sm:items-stretch sm:justify-start">
                    <div class="flex items-center">
                        <img class="block lg:hidden h-8 w-auto" src="<?= BASE_URL ?>assets/img/img/sis.jpg" alt="Logo">
                    </div>
                    <div class="hidden sm:block sm:ml-6">
                        <div class="flex space-x-4">
                            <a href="<?= BASE_URL ?>index.php" class="px-3 py-2 rounded-md text-sm font-medium <?= strpos($uriActual, 'index.php') !== false || $uriActual == '/' ? 'bg-gray-700 text-white' : 'text-white hover:bg-gray-700 hover:text-white' ?>">
                                Tareas
                            </a>
                            <a href="<?= BASE_URL ?>views/empleados/agregar.php" class="px-3 py-2 rounded-md text-sm font-medium <?= strpos($uriActual, '/empleados/') !== false ? 'bg-gray-700 text-white' : 'text-white hover:bg-gray-700 hover:text-white' ?>">
                                Empleados
                            </a>
                            <a href="<?= BASE_URL ?>views/cursos/agregar.php" class="px-3 py-2 rounded-md text-sm font-medium <?= strpos($uriActual, '/cursos/') !== false ? 'bg-gray-700 text-white' : 'text-white hover:bg-gray-700 hover:text-white' ?>">
                                Cursos
                            </a>
                            <a href="<?= BASE_URL ?>views/estudiantes/agregar.php" class="px-3 py-2 rounded-md text-sm font-medium <?= strpos($uriActual, '/estudiantes/') !== false ? 'bg-gray-700 text-white' : 'text-white hover:bg-gray-700 hover:text-white' ?>">
                                Estudiantes
                            </a>
                            <a href="<?= BASE_URL ?>views/proveedores/agregar.php" class="px-3 py-2 rounded-md text-sm font-medium <?= strpos($uriActual, '/proveedores/') !== false ? 'bg-gray-700 text-white' : 'text-white hover:bg-gray-700 hover:text-white' ?>">
                                Proveedores
                            </a>
                            <a href="<?= BASE_URL ?>views/productos/agregar.php" class="px-3 py-2 rounded-md text-sm font-medium <?= strpos($uriActual, '/productos/') !== false ? 'bg-gray-700 text-white' : 'text-white hover:bg-gray-700 hover:text-white' ?>">
                                Productos
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8 py-4 bg-zinc-200 shadow-lg p-4 rounded-b-lg">
        <h1 class="uppercase text-2xl font-bold text-center py-4">Sistema de Gestión</h1>
        <p class="text-center text-ellipsis py-2 text-gray-600">Administración general de registros</p>
    </div>
</header>