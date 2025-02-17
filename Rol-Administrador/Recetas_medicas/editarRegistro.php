<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost:3307";
$username = "root";
$password = "";
$dbname = "promedicch";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Error de conexión a la base de datos']));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identificacionActual = $_POST['Identificacion_Actual'];
    $nuevaIdentificacion = $_POST['Nueva_Identificacion'];
    $nombre = $_POST['Nombre_Paciente'];
    $fecha = $_POST['Fecha_Insercion'];
    $imagen_destino = null;

    if (isset($_FILES['Imagen']) && $_FILES['Imagen']['error'] === 0) {
        $imagen_tmp = $_FILES['Imagen']['tmp_name'];
        $imagen_nombre = basename($_FILES['Imagen']['name']);
        $imagen_destino = 'uploads/' . $imagen_nombre;

        if (!is_dir('uploads')) {
            mkdir('uploads', 0777, true);
        }

        if (!move_uploaded_file($imagen_tmp, $imagen_destino)) {
            die(json_encode(['success' => false, 'message' => 'Error al mover la imagen.']));
        }
    }

    $sql = "UPDATE formula_medica 
            SET Identificacion_Paciente = ?, Nombre_Paciente = ?, Fecha_Insercion = ?";
    if ($imagen_destino) {
        $sql .= ", Imagen = ?";
    }
    $sql .= " WHERE Identificacion_Paciente = ?";

    $stmt = $conn->prepare($sql);
    if ($imagen_destino) {
        $stmt->bind_param("sssss", $nuevaIdentificacion, $nombre, $fecha, $imagen_destino, $identificacionActual);
    } else {
        $stmt->bind_param("ssss", $nuevaIdentificacion, $nombre, $fecha, $identificacionActual);
    }

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'imagen_destino' => $imagen_destino]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar el registro.']);
    }
    $stmt->close();
}
$conn->close();
?>
