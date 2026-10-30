<?php
$host = "localhost";
$usuario = "root";
$password = "";
$base_datos = "audiolibro_english";

// Crear la conexión
$conexion = new mysqli('localhost', 'root', '', 'audiolibro_english', 3308);

// Verificar la conexión
if ($conexion->connect_error) {
    die("Error de conexión a la base de datos: " . $conexion->connect_error);
}

// Establecer el juego de caracteres a UTF-8
$conexion->set_charset("utf8");
?>