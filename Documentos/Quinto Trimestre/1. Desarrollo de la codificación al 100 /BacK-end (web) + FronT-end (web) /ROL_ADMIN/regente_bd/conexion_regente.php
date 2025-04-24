<?php
// Configuración de la conexión a la base de datos
$host = 'localhost:3307'; 
$dbname = 'promedicch'; 
$username = 'root'; 
$password = ''; 

try {
    // Conectar a la base de datos usando PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Configurar PDO para que muestre los errores
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Si la conexión falla, mostrar un mensaje de error
    echo 'Conexión fallida: ' . $e->getMessage();
    exit;
}
?>
