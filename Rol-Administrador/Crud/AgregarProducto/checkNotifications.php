<?php
// Verificar si el formulario ha sido enviado usando POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos enviados a través de POST
    $nombre_producto = isset($_POST['nombre_producto']) ? trim($_POST['nombre_producto']) : '';
    $cantidad_stock = isset($_POST['cantidad_stock']) ? (int) $_POST['cantidad_stock'] : 0;
    $cantidad_minima = isset($_POST['cantidad_minima']) ? (int) $_POST['cantidad_minima'] : 0;
    $fecha_vencimiento = isset($_POST['fecha_vencimiento']) ? $_POST['fecha_vencimiento'] : '';

    // Crear un array para las notificaciones
    $notifications = [];

    // Verificar si el stock es bajo
    if ($cantidad_stock <= $cantidad_minima) {
        $notifications[] = "El producto '{$nombre_producto}' tiene bajo stock.";
    }

    // Verificar si el producto está por vencer (menos de 7 días para la fecha de vencimiento)
    if (strtotime($fecha_vencimiento) < strtotime('+7 days')) {
        $notifications[] = "El producto '{$nombre_producto}' está por vencer.";
    }

    // Almacenar las notificaciones en la URL para simular una respuesta GET
    $notification_message = implode(" ", $notifications); // Unir todas las notificaciones en un solo mensaje

    // Redirigir al mismo formulario con las notificaciones como parámetro GET
    header("Location: formulario.php?notification=" . urlencode($notification_message));
    exit;
}
?>
