<?php
$data = json_decode(file_get_contents("php://input"), true);

// Conectar a la base de datos
include 'conexion.php'; 

$id_categoria = $data['id_categoria'];

// Verificar si la categoría tiene clasificaciones asociadas
$query_check = "SELECT COUNT(*) AS count FROM clasificacion WHERE Id_Categoria = ?";
$stmt_check = $conn->prepare($query_check);
$stmt_check->bind_param("i", $id_categoria);
$stmt_check->execute();
$stmt_check->bind_result($count);
$stmt_check->fetch();
$stmt_check->close();

if ($count > 0) {
    // Si la categoría tiene clasificaciones asociadas, no se puede eliminar
    echo json_encode(["success" => false, "message" => "No se puede eliminar la categoría porque tiene clasificaciones asociadas."]);
} else {
    // Si no tiene clasificaciones, proceder con la eliminación
    $query_delete = "DELETE FROM categoria WHERE Id_Categoria = ?";
    $stmt_delete = $conn->prepare($query_delete);
    $stmt_delete->bind_param("i", $id_categoria);

    if ($stmt_delete->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "Error al eliminar la categoría."]);
    }

    $stmt_delete->close();
}

$conn->close();
?>
