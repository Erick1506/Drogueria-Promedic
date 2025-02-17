<?php
// Permitir solicitudes CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Conexión a la base de datos
$servername = "localhost";
$username = "root"; 
$password = "";
$dbname = "promedicch"; 

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Error al conectar con la base de datos: " . $conn->connect_error);
}

// Consulta para obtener los productos
$sql = "SELECT id_producto, Nombre_Producto, Fecha_Vencimiento, Cantidad_Stock, Cantidad_Minima, Cantidad_Maxima FROM producto";
$result = $conn->query($sql);

$notificaciones = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Convertir la fecha de vencimiento a formato timestamp
        $fecha_vencimiento = strtotime($row['Fecha_Vencimiento']);
        $fecha_actual = time();

        // Verificar si el producto está vencido
        if ($fecha_vencimiento < $fecha_actual) {
            $notificaciones[] = [
                'id' => $row['id_producto'],
                'nombre' => $row['Nombre_Producto'],
                'estado' => ['El producto ha vencido.'],
                'Fecha_Vencimiento' => $row['Fecha_Vencimiento']
            ];
        }
        // Verificar si está por debajo de la cantidad mínima
        if ($row['Cantidad_Stock'] < $row['Cantidad_Minima']) {
            $notificaciones[] = [
                'id' => $row['id_producto'],
                'nombre' => $row['Nombre_Producto'],
                'estado' => ['El producto está por debajo de la cantidad mínima.'],
                'Fecha_Vencimiento' => $row['Fecha_Vencimiento']
            ];
        }
        // Verificar si ha alcanzado la cantidad máxima
        if ($row['Cantidad_Stock'] > $row['Cantidad_Maxima']) {
            $notificaciones[] = [
                'id' => $row['id_producto'],
                'nombre' => $row['Nombre_Producto'],
                'estado' => ['El producto ha superado la cantidad máxima.'],
                'Fecha_Vencimiento' => $row['Fecha_Vencimiento']
            ];
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
