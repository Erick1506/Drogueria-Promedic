<?php
// Configuración de la conexión a la base de datos
$servername = "localhost:3307";
$username = "root"; 
$password = ""; 
$dbname = "promedicch";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Error al conectar a la base de datos.']));
}

// Configurar UTF-8
$conn->set_charset("utf8");

// Verificar si se ha enviado la identificación
if (!empty($_POST['Identificacion_Paciente'])) {
    $identificacion = trim($_POST['Identificacion_Paciente']); // Eliminar espacios en blanco
    
    // Preparar la consulta para eliminar el registro
    $sql = "DELETE FROM Formula_Medica WHERE Identificacion_Paciente = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $identificacion);
    
    // Ejecutar la consulta
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo json_encode(['success' => true, 'message' => 'Registro eliminado correctamente.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'No se encontró un registro con esa identificación.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al eliminar el registro.']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Identificación no proporcionada.']);
}

$conn->close();
?>
