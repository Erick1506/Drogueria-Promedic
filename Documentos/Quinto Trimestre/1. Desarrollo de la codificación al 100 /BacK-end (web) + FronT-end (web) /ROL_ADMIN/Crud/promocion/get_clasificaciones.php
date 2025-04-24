<?php
$conn = new mysqli("localhost:3307", "root", "", "promedicch");
$idCategoria = $_GET['id_categoria'];
$result = $conn->query("SELECT * FROM Clasificacion WHERE Id_Categoria = $idCategoria");
$rows = [];
while ($row = $result->fetch_assoc()) {
    $rows[] = $row;
}
echo json_encode($rows);
?>
