<?php
include 'conexion.php';

// Habilitar reporte de errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

$errores = [];


// Obtener Regentes
$sqlRegentes = "SELECT Id_Regente, Nombre FROM regente";
$resultRegentes = $conn->query($sqlRegentes);
$regentes = [];
if ($resultRegentes) {
    if ($resultRegentes->num_rows > 0) {
        while ($row = $resultRegentes->fetch_assoc()) {
            $regentes[] = $row;
        }
    } else {
        $regentes = null; // No hay regentes disponibles
    }
} else {
    $errores[] = "Error al consultar regentes: " . $conn->error;
}

// Retornar todos los datos como JSON
header('Content-Type: application/json');
$response = [
    'regentes' => $regentes,
    'errores' => $errores
];

echo json_encode($response, JSON_PRETTY_PRINT);

// Cerrar la conexión
$conn->close();
?>
