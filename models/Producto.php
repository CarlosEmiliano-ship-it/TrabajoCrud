<?php
class Producto
{
    private PDO $conexion;

    public function __construct(PDO $conexion)
    {
        $this->conexion = $conexion;
    }

    public function agregar(array $datos): bool
    {
        $sql = "INSERT INTO producto (nombre_producto, fecha_creacion, fecha_caducidad, precio, stock, proveedor_id)
                VALUES (:nombre_producto, :fecha_creacion, :fecha_caducidad, :precio, :stock, :proveedor_id)";

        $stmt = $this->conexion->prepare($sql);

        return $stmt->execute([
            ':nombre_producto'  => $datos['nombre_producto'],
            ':fecha_creacion'   => $datos['fecha_creacion'],
            ':fecha_caducidad'  => $datos['fecha_caducidad'],
            ':precio'           => $datos['precio'],
            ':stock'            => $datos['stock'],
            ':proveedor_id'     => $datos['proveedor_id'],
        ]);
    }

    public function obtenerTodos(): array
    {
        $sql = "SELECT p.*, pr.nombre_proveedor AS proveedor_nombre 
                FROM producto p
                INNER JOIN proveedor pr ON p.proveedor_id = pr.id
                ORDER BY p.id DESC";
        $stmt = $this->conexion->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId(int $id)
    {
        $sql = "SELECT * FROM producto WHERE id = :id";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function actualizar(int $id, array $datos): bool
    {
        $sql = "UPDATE producto 
                SET nombre_producto = :nombre_producto, 
                    fecha_creacion = :fecha_creacion, 
                    fecha_caducidad = :fecha_caducidad, 
                    precio = :precio, 
                    stock = :stock, 
                    proveedor_id = :proveedor_id 
                WHERE id = :id";

        $stmt = $this->conexion->prepare($sql);

        return $stmt->execute([
            ':id'               => $id,
            ':nombre_producto'  => $datos['nombre_producto'],
            ':fecha_creacion'   => $datos['fecha_creacion'],
            ':fecha_caducidad'  => $datos['fecha_caducidad'],
            ':precio'           => $datos['precio'],
            ':stock'            => $datos['stock'],
            ':proveedor_id'     => $datos['proveedor_id'],
        ]);
    }

    public function eliminar(int $id): bool
    {
        $sql = "DELETE FROM producto WHERE id = :id";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}