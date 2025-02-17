<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

$conexion = new mysqli('localhost', 'root', '', 'promedicch');

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Agregar notificación
    $data = json_decode(file_get_contents('php://input'), true);
    if (isset($data['mensaje'])) {
        $mensaje = $conexion->real_escape_string($data['mensaje']);
        $fecha_creacion = date('Y-m-d H:i:s');

        $sql = "INSERT INTO notificaciones (mensaje, fecha_creacion) VALUES ('$mensaje', '$fecha_creacion')";
        if ($conexion->query($sql) === TRUE) {
            echo json_encode(['status' => 'success', 'id' => $conexion->insert_id, 'fecha_creacion' => $fecha_creacion]);
        } else {
            echo json_encode(['status' => 'error', 'mensaje' => 'Error al agregar notificación']);
        }
    } else {
        echo json_encode(['status' => 'error', 'mensaje' => 'Mensaje no proporcionado']);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Eliminar notificación
    $data = json_decode(file_get_contents('php://input'), true);
    if (isset($data['id'])) {
        $id = (int)$data['id']; // Asegúrate de que el ID sea un número entero
        $sql = "DELETE FROM notificaciones WHERE id = $id";
        if ($conexion->query($sql) === TRUE) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'mensaje' => 'Error al eliminar notificación']);
        }
    } else {
        echo json_encode(['status' => 'error', 'mensaje' => 'ID no proporcionado']);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Obtener todas las notificaciones
    $sql = "SELECT * FROM notificaciones ORDER BY fecha_creacion DESC";
    $resultado = $conexion->query($sql);

    if ($resultado->num_rows > 0) {
        $notificaciones = [];
        while ($fila = $resultado->fetch_assoc()) {
            $notificaciones[] = [
                'id' => $fila['id'],
                'mensaje' => $fila['mensaje'],
                'fecha_creacion' => $fila['fecha_creacion']
            ];
        }
        echo json_encode($notificaciones);
    } else {
        echo json_encode([]);
    }
}

$conexion->close();
?>
