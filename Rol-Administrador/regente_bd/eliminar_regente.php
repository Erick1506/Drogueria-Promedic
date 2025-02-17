<?php
include 'conexion_regente.php'; // Conexión a la base de datos

// Verificar si la solicitud es POST y la acción es eliminar
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'eliminar') {
    // Verificar que el ID del regente a eliminar esté presente y no esté vacío
    $id = $_POST['id_eliminar_regente'] ?? null;

    if ($id) {
        // Preparar la consulta SQL para eliminar el regente
        $stmt = $pdo->prepare('DELETE FROM regente WHERE Id_Regente = ?');
        $stmt->execute([$id]); // Ejecutar la consulta con el ID proporcionado

        // Redirigir a la página de interfaz_regente.php después de eliminar
        header('Location: interfaz_regente.php');
        exit; // Asegurarse de que el script se detiene después de la redirección
    } else {
        // Mensaje de error si el ID no es válido
        echo "ID de regente no proporcionado o no válido.";
    }
}
?>
