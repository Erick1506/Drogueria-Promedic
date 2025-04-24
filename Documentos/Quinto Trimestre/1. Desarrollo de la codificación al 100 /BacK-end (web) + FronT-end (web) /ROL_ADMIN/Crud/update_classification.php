<?php
$data = json_decode(file_get_contents("php://input"), true);

// Conectar a la base de datos
include 'conexion.php'; 

$id_clasificacion = $data['id_clasificacion'];
$nombre_clasificacion = $data['nombre_clasificacion']; // Agregamos el nombre
$descripcion_clasificacion = $data['descripcion_clasificacion']; // Descripción

// Preparar la consulta para actualizar tanto el nombre como la descripción
$query = "UPDATE clasificacion SET Nombre_Clasificacion = ?, Descripcion_Clasificacion = ? WHERE Id_Clasificacion = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ssi", $nombre_clasificacion, $descripcion_clasificacion, $id_clasificacion);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false]);
}

$stmt->close();
$conn->close();
?>
