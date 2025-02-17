<?php
include 'conexion_proveedor.php'; // Conexión a la base de datos

// Verificar si la solicitud es POST y la acción es eliminar
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['accion'] === 'eliminar') {
    // Obtener el ID del proveedor que se quiere eliminar
    $id = $_POST['id_eliminar_proveedor'];

    // Preparar la consulta SQL para eliminar el proveedor
    $stmt = $pdo->prepare('DELETE FROM proveedor WHERE Id_Proveedor = ?');
    $stmt->execute([$id]); // Ejecutar la consulta con el ID proporcionado

    // Redirigir a la página de interfaz_proveedor.php después de eliminar
    header('Location: interfaz_proveedor.php');
    exit; // Asegurarse de que el script se detiene después de la redirección
}
?>
