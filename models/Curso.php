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
        try {
            $sql = "INSERT INTO curso (nombre_curso, descripcion) VALUES (:nombre_curso, :descripcion)";
            $stmt = $this->conexion->prepare($sql);
            return $stmt->execute([
                ':nombre_curso' => $datos['nombre_curso'],
                ':descripcion'  => $datos['descripcion']
            ]);
        } catch (PDOException $e) {
            echo "<div style='background-color: #ffcccc; color: #990000; padding: 15px; font-family: monospace;'>";
            echo "<strong>¡Error en Curso->agregar()!</strong><br>";
            echo "Mensaje: " . $e->getMessage();
            echo "</div>";
            exit();
        }
    }

    public function obtenerTodos(): array
    {
        try {
            $stmt = $this->conexion->query("SELECT * FROM curso ORDER BY id DESC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<div style='background-color: #ffcccc; color: #990000; padding: 15px; font-family: monospace;'>";
            echo "<strong>¡Error en Curso->obtenerTodos()!</strong><br>";
            echo "Mensaje: " . $e->getMessage();
            echo "</div>";
            exit();
        }
    }

    public function obtenerPorId(int $id)
    {
        try {
            $sql = "SELECT * FROM curso WHERE id = :id LIMIT 1";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<div style='background-color: #ffcccc; color: #990000; padding: 15px; font-family: monospace;'>";
            echo "<strong>¡Error en Curso->obtenerPorId()!</strong><br>";
            echo "Mensaje: " . $e->getMessage();
            echo "</div>";
            exit();
        }
    }
}