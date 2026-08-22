<?php

class Empleado
{
    private $conexion;

    public function __construct(PDO $conexion)
    {
        $this->conexion = $conexion;
    }

    public function agregar(array $datos): bool
    {
        $sql = "INSERT INTO empleado (nombres, apellidos, fecha_nac, salario, puesto, imagen)
                VALUES (:nombres, :apellidos, :fecha_nac, :salario, :puesto, :imagen)";

        $stmt = $this->conexion->prepare($sql);

        return $stmt->execute([
            ':nombres'   => $datos['nombres'],
            ':apellidos' => $datos['apellidos'],
            ':fecha_nac' => $datos['fecha_nac'],
            ':salario'   => $datos['salario'],
            ':puesto'    => $datos['puesto'],
            ':imagen'    => $datos['imagen'],
        ]);
    }

    public function obtenerTodos(): array
    {
        $stmt = $this->conexion->query("SELECT * FROM empleado ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId(int $id)
    {
        $sql = "SELECT * FROM empleado WHERE id = :id LIMIT 1";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([':id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function actualizar(int $id, array $datos): bool
    {
        $sql = "UPDATE empleado
                SET nombres = :nombres,
                    apellidos = :apellidos,
                    fecha_nac = :fecha_nac,
                    salario = :salario,
                    puesto = :puesto,
                    imagen = :imagen
                WHERE id = :id";

        $stmt = $this->conexion->prepare($sql);

        return $stmt->execute([
            ':nombres'   => $datos['nombres'],
            ':apellidos' => $datos['apellidos'],
            ':fecha_nac' => $datos['fecha_nac'],
            ':salario'   => $datos['salario'],
            ':puesto'    => $datos['puesto'],
            ':imagen'    => $datos['imagen'],
            ':id'        => $id,
        ]);
    }

    public function eliminar(int $id): bool
    {
        $sql = "DELETE FROM empleado WHERE id = :id";
        $stmt = $this->conexion->prepare($sql);

        return $stmt->execute([':id' => $id]);
    }
}