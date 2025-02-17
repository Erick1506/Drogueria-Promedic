<?php
// Incluir la conexión a la base de datos
include 'conexion.php';

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos enviados por POST
    $marca = trim($_POST['Marca_Producto']); // Asegúrate de que el nombre coincida con el del formulario

    // Verificar que el campo no esté vacío
    if (empty($marca)) {
        echo "Por favor, completa el campo de la marca.";
        exit();
    }

    // Preparar la consulta para insertar los datos en la base de datos
    $sql = "INSERT INTO Marca (Marca_Producto) VALUES (?)";
    $stmt = $conn->prepare($sql);

    // Verificar si la consulta se preparó correctamente
    if ($stmt === false) {
        echo "Error al preparar la consulta: " . $conn->error;
        exit();
    }

    // Enlazar los parámetros
    $stmt->bind_param("s", $marca);

    // Ejecutar la consulta y verificar si la inserción fue exitosa
    if ($stmt->execute()) {
        echo "Marca agregada con éxito";
    } else {
        echo "Error al agregar marca: " . $stmt->error;
    }


    
    // Cerrar la declaración
    $stmt->close();
}

// Cerrar la conexión
$conn->close();
?>