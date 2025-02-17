<?php
include 'conexion.php';

// Habilitar reporte de errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Array para almacenar posibles errores
$errores = [];

// Obtener proveedores
$sqlProveedores = "SELECT Id_Proveedor, Nombre_Proveedor FROM Proveedor";
$resultProveedores = $conn->query($sqlProveedores);
$proveedores = [];
if ($resultProveedores) {
    if ($resultProveedores->num_rows > 0) {
        while ($row = $resultProveedores->fetch_assoc()) {
            $proveedores[] = $row;
        }
    } else {
        $proveedores = null; // No hay proveedores disponibles
    }
} else {
    $errores[] = "Error al consultar proveedores: " . $conn->error;
}

// Obtener categorías
$sqlCategorias = "SELECT Id_Categoria, Nombre_Categoria FROM categoria";
$resultCategorias = $conn->query($sqlCategorias);
$categorias = [];
if ($resultCategorias) {
    if ($resultCategorias->num_rows > 0) {
        while ($row = $resultCategorias->fetch_assoc()) {
            $categorias[] = $row;
        }
    } else {
        $categorias = null; // No hay categorías disponibles
    }
} else {
    $errores[] = "Error al consultar categorías: " . $conn->error;
}

// Obtener clasificaciones
$sqlClasificaciones = "SELECT Id_Clasificacion, Nombre_Clasificacion FROM clasificacion";
$resultClasificaciones = $conn->query($sqlClasificaciones);

$clasificaciones = [];
if ($resultClasificaciones) {
    if ($resultClasificaciones->num_rows > 0) {
        while ($row = $resultClasificaciones->fetch_assoc()) {
            $clasificaciones[] = $row;
        }
    } else {
        $clasificaciones = null; // No hay clasificaciones disponibles
    }
} else {
    $errores[] = "Error al consultar clasificaciones: " . $conn->error;
}

// Obtener marcas
$sqlMarcas = "SELECT Id_Marca, Marca_Producto FROM marca";
$resultMarcas = $conn->query($sqlMarcas);
$marcas = [];
if ($resultMarcas) {
    if ($resultMarcas->num_rows > 0) {
        while ($row = $resultMarcas->fetch_assoc()) {
            $marcas[] = $row;
        }
    } else {
        $marcas = null; // No hay marcas disponibles
    }
} else {
    $errores[] = "Error al consultar marcas: " . $conn->error;
}

// Obtener Estado Producto
$sqlEstado_Producto = "SELECT Id_Estado_Producto, Tipo_Estado_Producto FROM estado_producto";
$resultEstado_Producto = $conn->query($sqlEstado_Producto);
$Estado_Producto = [];

if ($resultEstado_Producto) {
    if ($resultEstado_Producto->num_rows > 0) {
        // Si hay resultados, llenar el arreglo de Estados de Producto
        while ($row = $resultEstado_Producto->fetch_assoc()) {
            $Estado_Producto[] = $row;
        }
    } else {
        // No hay estados de productos disponibles
        $Estado_Producto = null;
    }
} else {
    // Si hubo un error en la consulta
    $errores[] = "Error al consultar estados de productos: " . $conn->error;
}

// Obtener Tipos de Promoción
$sqlTiposPromocion = "SELECT Id_Tipo_Promocion, Tipo_Promocion FROM Tipo_Promocion";
$resultTiposPromocion = $conn->query($sqlTiposPromocion);
$tiposPromocion = [];

if ($resultTiposPromocion) {
    if ($resultTiposPromocion->num_rows > 0) {
        // Si hay resultados, llenar el arreglo de Tipos de Promoción
        while ($row = $resultTiposPromocion->fetch_assoc()) {
            $tiposPromocion[] = $row;
        }
    } else {
        // No hay tipos de promoción disponibles
        $tiposPromocion = null;
    }
} else {
    // Si hubo un error en la consulta
    $errores[] = "Error al consultar tipos de promoción: " . $conn->error;
}

// Retornar todos los datos como JSON
header('Content-Type: application/json');
$response = [
    'proveedores' => $proveedores,
    'categorias' => $categorias,
    'clasificaciones' => $clasificaciones,
    'marcas' => $marcas,
    'estados' => $Estado_Producto,
    'promociones' => $tiposPromocion,
    'errores' => $errores
];

echo json_encode($response, JSON_PRETTY_PRINT);

// Cerrar la conexión
$conn->close();
?>
