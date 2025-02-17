<?php
// Conexión a la base de datos
$servername = "localhost:3307";
$username = "root";
$password = "";
$dbname = "promedicch";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Consulta para obtener los turnos
$sql = "SELECT Id_Turno, Turno FROM turno_regente"; // Ajusta "Nombre_Turno" según tu esquema
$result = $conn->query($sql);

// Generar opciones
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<option value='" . $row['Id_Turno'] . "'>" . $row['Turno'] . "</option>";
    }
} else {
    echo "<option value=''>No hay turnos disponibles</option>";
}

$conn->close();
?>
