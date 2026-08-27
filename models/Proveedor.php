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

    public function agregar($nombre_proveedor, $contacto, $telefono): bool
    {
        try {
            $sql = "INSERT INTO proveedor (nombre_proveedor, contacto, telefono) VALUES (?, ?, ?)";
            $stmt = $this->conexion->prepare($sql);
            return $stmt->execute([$nombre_proveedor, $contacto, $telefono]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function obtenerPorId(int $id)
    {
        try {
            $sql = "SELECT * FROM proveedor WHERE id = :id LIMIT 1";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }

   public function actualizar(int $id, array $datos): bool
    {
        try {
            $sql = "UPDATE proveedor 
                    SET nombre_proveedor = ?, contacto = ?, telefono = ? 
                    WHERE id = ?";
            $stmt = $this->conexion->prepare($sql);
            return $stmt->execute([
                $datos['nombre_proveedor'], 
                $datos['contacto'], 
                $datos['telefono'], 
                $id
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function eliminar(int $id): bool
    {
        try {
            $sql = "DELETE FROM proveedor WHERE id = :id";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
}