<?php

// Permitir CORS para todas las solicitudes
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Methods: GET'); // Permitir LOS METODOS
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");
header('Access-Control-Allow-Headers: Content-Type, Accept'); // Permitir los encabezados necesarios

require("conexion.php");

function obtenerDatos($conn, $tabla, $idColumna, $nombreColumna, $categoriaColumna = null) {
    $lista = [];
    $sql = "SELECT $idColumna, $nombreColumna" . ($categoriaColumna ? ", $categoriaColumna" : "") . " FROM $tabla";
    $resultado = $conn->query($sql);
    if ($resultado) {
        while ($fila = $resultado->fetch_assoc()) {
            $item = [
                'id' => $fila[$idColumna],
                'nombre' => $fila[$nombreColumna]
            ];
            if ($categoriaColumna) {
                $item['Id_Categoria'] = $fila[$categoriaColumna];
            }
            $lista[] = $item;
        }
    }
    return $lista;
}

// Llama a la función con el campo de categoría
$datos = [
    "categorias"        => obtenerDatos($conn, "categoria", "Id_Categoria", "Nombre_Categoria"),
    "clasificaciones"   => obtenerDatos($conn, "clasificacion", "Id_Clasificacion", "Nombre_Clasificacion", "Id_Categoria"),
    "proveedores"       => obtenerDatos($conn, "proveedor", "Id_Proveedor", "Nombre_Proveedor"),
    "marcas"            => obtenerDatos($conn, "marca", "Id_Marca", "Marca_Producto"),
    "estados_producto"  => obtenerDatos($conn, "estado_producto", "Id_Estado_Producto", "Tipo_Estado_Producto"),
    "tipos_promocion"   => obtenerDatos($conn, "tipo_promocion", "Id_Tipo_Promocion", "Tipo_Promocion")
];

echo json_encode($datos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

$conn->close();
?>