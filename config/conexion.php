<?php

$dsn = 'mysql:host=localhost;dbname=tarea';
$username = 'root';
$password = '';

try{
    $conexion = new PDO($dsn, $username, $password); 
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Conexion exitosa con la base de datos";
    }catch(PDOException $e){
        echo "Error de conexion: " . $e->getMessage();
}