<?php
// Incluir la conexión a la base de datos
include('conexion.php');

// Configurar las cabeceras para permitir el acceso CORS
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST, GET");  // Asegúrate de permitir el método GET en el listado


// Consulta SQL para obtener los productos
$sql = "SELECT 
            Id_Producto, 
            Nombre_Producto, 
            Descripcion_Producto, 
            Precio, 
            Codigo_Barras, 
            Costo_Adquisicion, 
            Peso, 
            Cantidad_Stock, 
            Cantidad_Minima, 
            Cantidad_Maxima, 
            Fecha_Vencimiento, 
            Id_Categoria, 
            Id_Clasificacion, 
            Id_Marca, 
            Id_Proveedor, 
            Id_Estado_Producto 
        FROM producto";
$result = $conn->query($sql);

$productos = array(); // Aquí almacenamos los productos

if ($result->num_rows > 0) {
    // Agregar cada producto a un array
    while ($row = $result->fetch_assoc()) {
        $productos[] = $row;  // Agregar producto al array
    }

    // Devolver los productos como una respuesta JSON
    echo json_encode($productos);
} else {
    // Si no hay productos
    echo json_encode(["message" => "No se encontraron productos"]);
}

// Cerrar la conexión a la base de datos
$conn->close();
?>