<?php
// Conexión a la base de datos
include 'conexion.php'; 



// Obtener categorías
$categorias = $conn->query("SELECT Nombre_Categoria, Descripcion_Categoria FROM categoria");
$categorias_array = [];
if ($categorias) {
    while ($categoria = $categorias->fetch_assoc()) {
        $categorias_array[] = $categoria;
    }
}

// Obtener clasificaciones
$clasificaciones = $conn->query("SELECT Nombre_Clasificacion, Descripcion_Clasificacion FROM clasificacion");
$clasificaciones_array = [];
if ($clasificaciones) {
    while ($clasificacion = $clasificaciones->fetch_assoc()) {
        $clasificaciones_array[] = $clasificacion;
    }
}

// Cerrar conexión
$conn->close();

// Devolver datos en formato JSON
echo json_encode([
    'categorias' => $categorias_array,
    'clasificaciones' => $clasificaciones_array
]);