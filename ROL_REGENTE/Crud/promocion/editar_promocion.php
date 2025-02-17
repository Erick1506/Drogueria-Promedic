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

// Recibir datos del formulario
$Id_Promocion = $_POST['Id_Promocion'];
$Id_Tipo_Promocion = $_POST['Id_Tipo_Promocion'];
$Fecha_Fin = $_POST['Fecha_Fin'];

// Actualizar la promoción
$sql = "UPDATE Promocion SET Id_Tipo_Promocion = ?, Fecha_Fin = ? WHERE Id_Promocion = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("isi", $Id_Tipo_Promocion, $Fecha_Fin, $Id_Promocion);

if ($stmt->execute()) {
    header("Location: promocion.php?msg=Promoción actualizada correctamente");
} else {
    echo "Error: " . $conn->error;
}

$stmt->close();
$conn->close();
?>
