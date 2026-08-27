<?php

class Curso
{
    private $conexion;

    public function __construct(PDO $conexion)
    {
        $this->conexion = $conexion;
    }

    public function agregar(array $datos): bool
    {
        $sql = "INSERT INTO curso (nombre_curso, descripcion) VALUES (:nombre_curso, :descripcion)";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([
            ':nombre_curso' => $datos['nombre_curso'],
            ':descripcion'  => $datos['descripcion']
        ]);
    }

    public function obtenerTodos(): array
    {
        $stmt = $this->conexion->query("SELECT * FROM curso ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId(int $id)
    {
        $sql = "SELECT * FROM curso WHERE id = :id LIMIT 1";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}