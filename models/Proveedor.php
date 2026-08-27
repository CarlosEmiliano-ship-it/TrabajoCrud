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
        $stmt = $this->conexion->query("SELECT * FROM proveedor ORDER BY nombre_proveedor ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}