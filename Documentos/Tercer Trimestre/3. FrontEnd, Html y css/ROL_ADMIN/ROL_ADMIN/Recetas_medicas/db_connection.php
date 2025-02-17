<?php
$servername = "localhost"; // Dirección del servidor de la base de datos
$username = "root";         // Nombre de usuario para conectarse a la base de datos
$password = "";             // Contraseña para el usuario de la base de datos
$database = "proyectobasededatos"; // Nombre de la base de datos a la que te conectas

// Crear conexión
$conn = new mysqli($servername, $username, $password, $database);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error); // Termina el script si hay un error de conexión
} else {
    echo "Conexión exitosa"; // Mensaje de éxito
}
?>
