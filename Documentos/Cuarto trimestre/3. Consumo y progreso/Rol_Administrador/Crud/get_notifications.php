<?php
session_start();

// Conexión a la base de datos
include 'Conexion.php';

// Consulta para obtener los productos
$sql = "SELECT * FROM producto";
$result = $conn->query($sql);

$notificaciones = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Verificar si la fecha de vencimiento tiene el formato correcto
        if (DateTime::createFromFormat('Y-m-d', $row['Fecha_Vencimiento']) !== false) {
            $fechaVencimiento = new DateTime($row['Fecha_Vencimiento']);
            $fechaActual = new DateTime(); // Fecha actual

            // Comparar la fecha de vencimiento con la fecha actual
            if ($fechaVencimiento < $fechaActual) {
                $notificaciones[] = "El producto '{$row['Nombre_Producto']}' ha vencido.";
            }
        } else {
            $notificaciones[] = "⚠️ Error al leer la fecha de vencimiento del producto '{$row['Nombre_Producto']}'.";
        }

        // Verificar si está por debajo de la cantidad mínima
        if ($row['Cantidad_Stock'] < $row['Cantidad_Minima']) {
            $notificaciones[] = "El producto '{$row['Nombre_Producto']}' está por debajo de la cantidad mínima.";
        }

        // Verificar si ha alcanzado la cantidad máxima
        if ($row['Cantidad_Stock'] > $row['Cantidad_Maxima']) {
            $notificaciones[] = "El producto '{$row['Nombre_Producto']}' ha superado la cantidad máxima.";
        }

        // 🔍 Depuración (descomenta para ver las fechas y valores en pantalla)
        /*
        echo "Producto: {$row['Nombre_Producto']}<br>";
        echo "Fecha Vencimiento: " . $fechaVencimiento->format('Y-m-d') . "<br>";
        echo "Fecha Actual: " . $fechaActual->format('Y-m-d') . "<br>";
        echo ($fechaVencimiento < $fechaActual) ? "❌ VENCIDO<br>" : "✅ NO vencido<br>";
        echo "<hr>";
        */
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
