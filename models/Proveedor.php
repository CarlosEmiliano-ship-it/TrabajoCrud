<?php

class Proveedor
{
    private PDO $conexion;

    public function __construct(PDO $conexion)
    {
        $this->conexion = $conexion;
    }

    public function obtenerTodos(): array
    {
        try {
            $stmt = $this->conexion->query("SELECT * FROM proveedor ORDER BY nombre_proveedor ASC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<div style='background-color: #ffcccc; color: #990000; padding: 15px; font-family: monospace;'>";
            echo "<strong>¡Error en Proveedor->obtenerTodos()!</strong><br>";
            echo "Mensaje: " . $e->getMessage();
            echo "</div>";
            exit();
        }
    }
}