<?php
// Manejar la carga de la imagen
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
    $imagen = $_FILES['imagen'];
    $nombreImagen = basename($imagen['name']);
    $rutaDestino = "imagenes/" . $nombreImagen; // Asegúrate de que la carpeta se llama "imagenes"

    // Mueve la imagen a la carpeta deseada
    if (move_uploaded_file($imagen['tmp_name'], $rutaDestino)) {
        // Aquí puedes guardar los datos en la base de datos
        $sql = "INSERT INTO tu_tabla (identificacion, nombre, fecha, imagen) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        
        // Verifica si la preparación de la consulta fue exitosa
        if ($stmt === false) {
            die('Error en la preparación de la consulta: ' . htmlspecialchars($conn->error));
        }

        $stmt->bind_param("ssss", $identificacion, $nombre, $fecha, $rutaDestino);

        // Intenta ejecutar la consulta
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Error al insertar en la base de datos: ' . htmlspecialchars($stmt->error)]);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'error' => 'Error al mover el archivo.']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Error en la carga de la imagen: ' . $_FILES['imagen']['error']]);
}

?>

