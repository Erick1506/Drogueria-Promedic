<?php
// Conexión a la base de datos
$servername = "localhost:3307";
$username = "root";
$password = "";
$database = "promedicch";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Verificar si se recibió un ID válido
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];
    
    // Preparar la consulta para eliminar la promoción
    $sql = "DELETE FROM Promocion WHERE Id_Promocion = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        $msg = "Promoción eliminada correctamente.";
    } else {
        $msg = "Error al eliminar la promoción.";
    }
    
    $stmt->close();
} else {
    $msg = "ID de promoción no válido.";
}

$conn->close();

// Redireccionar a la página de promociones con un mensaje
header("Location: promocion.php?msg=" . urlencode($msg));
exit();
?>
