<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

$servername = "localhost:3307";
$username = "root";
$password = "";
$dbname = "promedicch";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Conexión fallida: " . $conn->connect_error]);
    exit;
}

if (isset($_FILES["imagen"]) && $_FILES["imagen"]["error"] == 0) {
    $target_dir = "Imagenes/";
    $target_file = $target_dir . basename($_FILES["imagen"]["name"]);

    if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $target_file)) {
        $identificacion = $_POST['identificacion'];
        $nombre = $_POST['nombre'];
        $fecha = $_POST['fecha'];
        $imagen = $target_file;
        $Id_Administrador = $_POST['Id_Administrador'];

        $stmt = $conn->prepare("INSERT INTO Formula_Medica (Nombre_Paciente, Identificacion_Paciente, Imagen, Fecha_Insercion, Id_Administrador) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $nombre, $identificacion, $imagen, $fecha, $Id_Administrador);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Registro exitoso."]);
        } else {
            echo json_encode(["success" => false, "message" => "Error al ejecutar la consulta: " . $stmt->error]);
        }

        $stmt->close();
    } else {
        echo json_encode(["success" => false, "message" => "Error al cargar la imagen."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "No se ha enviado la imagen."]);
}

$conn->close();
?>
