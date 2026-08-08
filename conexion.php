<?php
$host = "localhost";
$usuario = "root";
$password = "Heberto1951";
$base_datos = "audiolibro_english";

// Crear la conexión
$conexion = new mysqli($host, $usuario, $password, $base_datos);

// Verificar la conexión
if ($conexion->connect_error) {
    die("Error de conexión a la base de datos: " . $conexion->connect_error);
}

// Establecer el juego de caracteres a UTF-8
$conexion->set_charset("utf8");
?>