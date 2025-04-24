<?php
$data = json_decode(file_get_contents("php://input"), true);

// Conectar a la base de datos
include 'conexion.php'; 

$id_clasificacion = $data['id_clasificacion'];

// Verificar si la clasificación tiene productos asociados
$query_check = "SELECT COUNT(*) AS count FROM producto WHERE Id_Clasificacion = ?";
$stmt_check = $conn->prepare($query_check);
$stmt_check->bind_param("i", $id_clasificacion);
$stmt_check->execute();
$stmt_check->bind_result($count);
$stmt_check->fetch();
$stmt_check->close();

if ($count > 0) {
    // Si la clasificación tiene productos asociados, no se puede eliminar
    echo json_encode(["success" => false, "message" => "No se puede eliminar la clasificación porque tiene productos asociados."]);
} else {
    // Si no tiene productos, proceder con la eliminación
    $query_delete = "DELETE FROM clasificacion WHERE Id_Clasificacion = ?";
    $stmt_delete = $conn->prepare($query_delete);
    $stmt_delete->bind_param("i", $id_clasificacion);

    if ($stmt_delete->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "Error al eliminar la clasificación."]);
    }

    $stmt_delete->close();
}

$conn->close();
?>
