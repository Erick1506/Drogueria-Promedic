<?php
// conexion.php

// Datos de conexión
$servername = "localhost";        // O la IP o dominio de tu servidor MySQL
$username   = "root";       //  usuario de MySQL
$password   = "";      //  contraseña de MySQL
$dbname     = "promedicch";    // El nombre de tu base de datos
$port       = 3307;

// Crear la conexión usando mysqli
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$conn->set_charset("utf8");

?>
