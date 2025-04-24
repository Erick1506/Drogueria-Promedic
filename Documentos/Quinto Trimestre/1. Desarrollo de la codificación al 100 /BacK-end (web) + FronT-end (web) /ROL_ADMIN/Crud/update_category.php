<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');
include 'conexion.php';

// Recibir los datos enviados desde JavaScript
$json_input = file_get_contents('php://input');

// Verificar si los datos fueron recibidos
if (empty($json_input)) {
    error_log('No se recibió ningún dato en la solicitud.');
    echo json_encode(['success' => false, 'message' => 'No se recibieron datos']);
    exit;
}

// Decodificar el JSON recibido
$data = json_decode($json_input, true);

// Verificar si la decodificación fue exitosa
if ($data === null) {
    error_log('Error al decodificar JSON: ' . json_last_error_msg());
    echo json_encode(['success' => false, 'message' => 'Error al procesar los datos JSON']);
    exit;
}

// Verificar si los campos necesarios están presentes
if (isset($data['id_categoria'], $data['descripcion_categoria'], $data['nombre_categoria'])) {
    $id_categoria = $data['id_categoria'];
    $descripcion_categoria = $data['descripcion_categoria'];
    $nombre_categoria = $data['nombre_categoria'];

    // Actualizar la categoría en la base de datos
    $query = "UPDATE categoria SET Nombre_Categoria = ?, Descripcion_Categoria = ? WHERE Id_Categoria = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssi", $nombre_categoria, $descripcion_categoria, $id_categoria);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar la categoría: ' . $stmt->error]);
    }

    $stmt->close();
} else {
    error_log('Faltan datos en la solicitud.');
    echo json_encode(['success' => false, 'message' => 'Datos no enviados correctamente']);
}

$conn->close();
?>
