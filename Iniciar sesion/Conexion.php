<?php

// Configuración de la base de datos
$host = "localhost:3307"; // Cambia esto si tu base de datos está en otro servidor
$usuario = "root"; // Usuario de la base de datos
$contraseña = ""; // Contraseña del usuario (deja vacío si no tiene)
$base_de_datos = "promedicch"; // Nombre de la base de datos

// Crear la conexión
$mysqli = new mysqli($host, $usuario, $contraseña, $base_de_datos);

// Verificar la conexión
if ($mysqli->connect_error) {
    die("Error de conexión: " . $mysqli->connect_error);
}

?>
