<?php

class Estudiante
{
    private PDO $conexion;

    public function __construct(PDO $conexion)
    {
        $this->conexion = $conexion;
    }

    public function agregar(array $datos): bool
    {
        $sql = "INSERT INTO estudiante (nombre_completo, fecha_nacimiento, fecha_salida, id_curso)
                VALUES (:nombre_completo, :fecha_nacimiento, :fecha_salida, :id_curso)";

        $stmt = $this->conexion->prepare($sql);

        return $stmt->execute([
            ':nombre_completo'  => $datos['nombre_completo'],
            ':fecha_nacimiento' => $datos['fecha_nacimiento'],
            ':fecha_salida'     => $datos['fecha_salida'],
            ':id_curso'         => $datos['id_curso'],
        ]);
    }

    public function obtenerTodos(): array
    {
        $sql = "SELECT e.*, c.nombre_curso AS curso_nombre 
                FROM estudiante e
                INNER JOIN curso c ON e.curso_id = c.id
                ORDER BY e.id DESC";
        $stmt = $this->conexion->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function eliminar(int $id): bool
    {
        $sql = "DELETE FROM estudiante WHERE id = :id";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}