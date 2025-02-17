<?php
include "conexion.php";

$query = isset($_GET['query']) ? $_GET['query'] : '';

// Realiza la consulta a la base de datos
$sql = "SELECT * FROM producto WHERE Nombre_Producto LIKE ? OR Id_Producto LIKE ?";
$stmt = $conn->prepare($sql);
$searchTerm = "%$query%";
$stmt->bind_param("ss", $searchTerm, $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

$productos = [];
while ($row = $result->fetch_assoc()) {
    $productos[] = $row;
}

echo json_encode($productos);
?>