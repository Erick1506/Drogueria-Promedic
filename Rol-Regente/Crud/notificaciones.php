<?php
session_start(); // Iniciar sesión para manejar las notificaciones

// Inicializar la variable de notificaciones si no existe
if (!isset($_SESSION['notificaciones'])) {
    $_SESSION['notificaciones'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Agregar una notificación
    if (isset($_POST['mensaje']) && !empty($_POST['mensaje'])) {
        $mensaje = $_POST['mensaje'];
        $_SESSION['notificaciones'][] = $mensaje; // Guardar en la sesión
        echo json_encode(['status' => 'success', 'mensaje' => 'Notificación agregada.']);
    } else {
        echo json_encode(['status' => 'error', 'mensaje' => 'El mensaje no puede estar vacío.']);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Eliminar una notificación
    parse_str(file_get_contents("php://input"), $_DELETE);
    $mensaje = $_DELETE['mensaje'];

    // Comprobar si el mensaje existe en las notificaciones de la sesión
    if (($key = array_search($mensaje, $_SESSION['notificaciones'])) !== false) {
        unset($_SESSION['notificaciones'][$key]);
        $_SESSION['notificaciones'] = array_values($_SESSION['notificaciones']); // Reindexar la sesión
        echo json_encode(['status' => 'success', 'mensaje' => 'Notificación eliminada.']);
    } else {
        echo json_encode(['status' => 'error', 'mensaje' => 'Notificación no encontrada.']);
    }
} else {
    // Obtener todas las notificaciones
    echo json_encode($_SESSION['notificaciones']);
}
?>