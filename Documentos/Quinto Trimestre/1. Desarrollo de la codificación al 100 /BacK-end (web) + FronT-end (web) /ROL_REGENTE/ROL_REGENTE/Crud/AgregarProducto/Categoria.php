<?php
include 'conexion.php';

// los datos por POST
$nombre = $_POST['nombre'];
$descripcion = $_POST['descripcion'];

// Preparar la consulta para insertar los datos en la base de datos
$sql = "INSERT INTO categoria (Nombre_Categoria, Descripcion_Categoria) VALUES (?, ?)";
$stmt = $conn->prepare($sql);

// Verificar si la consulta se preparó correctamente
if ($stmt === false) {
    echo "Error al preparar la consulta: " . $conn->error;
    exit();
}

// Enlazar los parámetros
$stmt->bind_param("ss", $nombre, $descripcion);

// Ejecutar la consulta y verificar si la inserción fue exitosa
if ($stmt->execute()) {
    echo "Categoría agregada con éxito";
} else {
    echo "Error al agregar categoría: " . $conn->error;
}

// Cerrar la declaración
$stmt->close();

// Cerrar la conexión
$conn->close();
?>
