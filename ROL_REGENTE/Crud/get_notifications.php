<?php
session_start();

// Conexión a la base de datos
include 'Conexion.php'; 

// Consulta para obtener los productos
$sql = "SELECT * FROM producto";
$result = $conn->query($sql);

$notificaciones = [];

if ($result->num_rows > 0) {
    // Procesar cada producto
    while ($row = $result->fetch_assoc()) {
        // Verificar si el producto está vencido
        if (strtotime($row['Fecha_Vencimiento']) < time()) {
            $notificaciones[] = "El producto '{$row['Nombre_Producto']}' ha vencido.";
        }
        // Verificar si está por debajo de la cantidad mínima
        if ($row['Cantidad_Stock'] < $row['Cantidad_Minima']) {
            $notificaciones[] = "El producto '{$row['Nombre_Producto']}' está por debajo de la cantidad mínima.";
        }
        // Verificar si ha alcanzado la cantidad máxima
        if ($row['Cantidad_Stock'] > $row['Cantidad_Maxima']) {
            $notificaciones[] = "El producto '{$row['Nombre_Producto']}' ha superado la cantidad máxima.";
        }
    }
} else {
    $notificaciones[] = "No hay productos en la base de datos.";
}

// Cerrar la conexión
$conn->close();

// Devolver las notificaciones como JSON
header('Content-Type: application/json');
echo json_encode($notificaciones);
?>