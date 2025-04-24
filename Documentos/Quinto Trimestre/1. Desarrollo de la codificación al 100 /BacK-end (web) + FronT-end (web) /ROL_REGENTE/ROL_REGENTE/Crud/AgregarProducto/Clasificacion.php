<?php
include 'conexion.php'; // Asegúrate de que este archivo esté bien configurado para conectar con la base de datos

// Verificar si se recibieron los datos correctos por POST
if (isset($_POST['nombre']) && isset($_POST['descripcion']) && isset($_POST['idCategoria'])) {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $idCategoria = $_POST['idCategoria'];

    // Preparar la consulta para insertar los datos en la base de datos
    $sql = "INSERT INTO clasificacion (Nombre_Clasificacion, Descripcion_Clasificacion, Id_Categoria) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    // Verificar si la consulta se preparó correctamente
    if ($stmt === false) {
        echo "Error al preparar la consulta: " . $conn->error;
        exit();
    }

    // Enlazar los parámetros
    $stmt->bind_param("ssi", $nombre, $descripcion, $idCategoria);

    // Ejecutar la consulta y verificar si la inserción fue exitosa
    if ($stmt->execute()) {
        echo "Clasificación agregada con éxito";
    } else {
        echo "Error al agregar clasificación: " . $conn->error;
    }

    // Cerrar la declaración
    $stmt->close();
} else {
    echo "Faltan datos requeridos";
}

// Cerrar la conexión
$conn->close();
?>
