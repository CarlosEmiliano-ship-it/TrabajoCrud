<?php

class Tarea
{
    private PDO $conexion;

    public function __construct(PDO $conexion)
    {
        $this->conexion = $conexion;
    }

    public function agregar(array $datos): bool
    {
        $sql = "INSERT INTO tarea (titulo, descripcion, fecha_limite, id_empleado, estado)
                VALUES (:titulo, :descripcion, :fecha_limite, :id_empleado, :estado)";

        $stmt = $this->conexion->prepare($sql);

        return $stmt->execute([
            ':titulo'       => $datos['titulo'],
            ':descripcion'  => $datos['descripcion'],
            ':fecha_limite' => $datos['fecha_limite'],
            ':id_empleado'  => $datos['id_empleado'],
            ':estado'       => $datos['estado'],
        ]);
    }

    public function obtenerTodas(): array
    {
        $sql = "SELECT t.*, CONCAT(e.nombres, ' ', e.apellidos) AS empleado_nombre 
                FROM tarea t
                INNER JOIN empleado e ON t.id_empleado = e.id
                ORDER BY t.id DESC";
        $stmt = $this->conexion->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId(int $id): array|false
    {
        $sql = "SELECT * FROM tarea WHERE id = :id LIMIT 1";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function actualizar(int $id, array $datos): bool
    {
        $sql = "UPDATE tarea
                SET titulo = :titulo,
                    descripcion = :descripcion,
                    fecha_limite = :fecha_limite,
                    id_empleado = :id_empleado,
                    estado = :estado
                WHERE id = :id";

        $stmt = $this->conexion->prepare($sql);

        return $stmt->execute([
            ':titulo'       => $datos['titulo'],
            ':descripcion'  => $datos['descripcion'],
            ':fecha_limite' => $datos['fecha_limite'],
            ':id_empleado'  => $datos['id_empleado'],
            ':estado'       => $datos['estado'],
            ':id'           => $id,
        ]);
    }

    public function eliminar(int $id): bool
    {
        $sql = "DELETE FROM tarea WHERE id = :id";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}