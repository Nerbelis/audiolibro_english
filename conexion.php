<?php
$host = "localhost";
$usuario = "root";
<<<<<<< HEAD
$password = "";
$base_datos = "audiolibro_english";

// Crear la conexión
$conexion = new mysqli('localhost', 'root', '', 'audiolibro_english', 3308);
=======
$password = "Heberto1951";
$base_datos = "audiolibro_english";

// Crear la conexión
$conexion = new mysqli($host, $usuario, $password, $base_datos);
>>>>>>> f03d1d4dd3d53859c742270dc79d05937766b1a3

// Verificar la conexión
if ($conexion->connect_error) {
    die("Error de conexión a la base de datos: " . $conexion->connect_error);
}

// Establecer el juego de caracteres a UTF-8
$conexion->set_charset("utf8");
?>