<?php
$conn = new mysqli("localhost:3307", "root", "", "promedicch");
$id = $_GET['categoria'];
$result = $conn->query("SELECT Id_Clasificacion, Nombre_Clasificacion FROM clasificacion WHERE Id_Categoria = '$id'");
$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}
echo json_encode($data);
?>
