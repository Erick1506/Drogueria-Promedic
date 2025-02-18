<?php
// Configuración de la base de datos
$servername = "localhost:3307";
$username = "root"; 
$password = ""; 
$dbname = "promedicch";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Conexión fallida: " . $conn->connect_error]));
}

// Configurar UTF-8
$conn->set_charset("utf8");



// Consulta para obtener los registros
$sql = "SELECT Identificacion_Paciente, Nombre_Paciente, Fecha_Insercion, Imagen FROM Formula_Medica";
$result = $conn->query($sql);

// Comprobar si hay resultados
if ($result->num_rows > 0) {
    $registros = [];
    while ($row = $result->fetch_assoc()) {
        $registros[] = $row;
    }
    echo json_encode($registros);
} else {
    echo json_encode(["success" => false, "message" => "No se encontraron registros"]);
}

$conn->close();
?>
