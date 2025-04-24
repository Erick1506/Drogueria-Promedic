<?php
$conn = new mysqli("localhost:3307", "root", "", "promedicch");
$id = $_GET['clasificacion'];
$result = $conn->query("SELECT Id_Producto, Nombre_Producto FROM producto WHERE Id_Clasificacion = '$id'");
$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}
echo json_encode($data);
?>
