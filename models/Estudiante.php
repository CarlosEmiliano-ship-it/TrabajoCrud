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
        $sql = "INSERT INTO estudiante (nombre_completo, fecha_nacimiento, fecha_salida, curso_id)
                VALUES (:nombre_completo, :fecha_nacimiento, :fecha_salida, :curso_id)";

        $stmt = $this->conexion->prepare($sql);

        return $stmt->execute([
            ':nombre_completo'  => $datos['nombre_completo'],
            ':fecha_nacimiento' => $datos['fecha_nacimiento'],
            ':fecha_salida'     => !empty($datos['fecha_salida']) ? $datos['fecha_salida'] : null,
            ':curso_id'         => !empty($datos['id_curso']) ? $datos['id_curso'] : null,
        ]);
    }

    public function obtenerTodos(): array
{
    $sql = "SELECT e.*, c.nombre_curso AS curso_nombre 
            FROM estudiante e
            LEFT JOIN curso c ON e.curso_id = c.id
            ORDER BY e.id DESC";
    $stmt = $this->conexion->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    public function obtenerPorId(int $id)
    {
        $sql = "SELECT * FROM estudiante WHERE id = :id LIMIT 1";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function actualizar(int $id, array $datos): bool
    {
        $sql = "UPDATE estudiante 
                SET nombre_completo = :nombre_completo, 
                    fecha_nacimiento = :fecha_nacimiento, 
                    fecha_salida = :fecha_salida, 
                    curso_id = :curso_id 
                WHERE id = :id";

        $stmt = $this->conexion->prepare($sql);

        return $stmt->execute([
            ':id'               => $id,
            ':nombre_completo'  => $datos['nombre_completo'],
            ':fecha_nacimiento' => $datos['fecha_nacimiento'],
            ':fecha_salida'     => !empty($datos['fecha_salida']) ? $datos['fecha_salida'] : null,
            ':curso_id'         => !empty($datos['id_curso']) ? $datos['id_curso'] : null,
        ]);
    }

    public function eliminar(int $id): bool
    {
        $sql = "DELETE FROM estudiante WHERE id = :id";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}