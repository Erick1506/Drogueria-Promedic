<?php
$conn = new mysqli("localhost:3307", "root", "", "promedicch");
$idClasificacion = $_GET['id_clasificacion'];
$result = $conn->query("SELECT * FROM Producto WHERE Id_Clasificacion = $idClasificacion");
$rows = [];
while ($row = $result->fetch_assoc()) {
    $rows[] = $row;
}
echo json_encode($rows);
?>
