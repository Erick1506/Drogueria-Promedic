<?php

session_start();

// Verificación de sesión admin
if (!isset($_SESSION['admin_id'])) {
    $_SESSION['error_mensaje'] = "Ups, no tienes permisos para acceder a esta página.";
    header("Location: http://localhost/PROMEDIC/Iniciar_sesion/iniciar_sesion.php");
    exit();
}

// Desactivar errores (puedes activarlos con E_ALL durante pruebas)
error_reporting(0);

// Conexión a la base de datos
$conn = new mysqli("localhost:3307", "root", "", "promedicch");

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Incluir la librería TCPDF
require_once('tcpdf/tcpdf.php');

// Obtener datos del formulario
$fecha_inicio = $_POST['fecha_inicio'];
$fecha_fin = $_POST['fecha_fin'];
$filtro = $_POST['tipo_informe'];
$categoria_id = $_POST['categoria_id'] ?? null;
$clasificacion_id = $_POST['clasificacion_id'] ?? null;
$producto_id = $_POST['producto_id'] ?? null;

// Crear PDF
$pdf = new TCPDF();
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 12);

$pdf->MultiCell(0, 10, "Estimado Administrador de la Droguería PROMEDIC CH", 0, 'C');
$pdf->MultiCell(0, 10, "A continuación, se presenta el informe correspondiente al periodo comprendido entre $fecha_inicio y $fecha_fin.", 0, 'C');
$pdf->Ln(10);

// Generar el informe según filtro
if ($filtro == 'producto' && $producto_id) {
    $query = "SELECT p.Id_Producto, p.Nombre_Producto, p.Descripcion_Producto, p.Precio, p.Cantidad_Stock, 
                     p.Codigo_Barras, p.Peso, c.Nombre_Categoria, cl.Nombre_Clasificacion, 
                     m.Marca_Producto AS Nombre_Marca, pr.Nombre_Proveedor,
                     IFNULL(prm.Descuento, 'No') AS Promocion_Activa, prm.Fecha_Inicio, prm.Fecha_Fin
              FROM producto p
              JOIN categoria c ON p.Id_Categoria = c.Id_Categoria
              JOIN clasificacion cl ON p.Id_Clasificacion = cl.Id_Clasificacion
              JOIN marca m ON p.Id_Marca = m.Id_Marca
              JOIN proveedor pr ON p.Id_Proveedor = pr.Id_Proveedor
              LEFT JOIN promocion prm ON p.Id_Producto = prm.Id_Producto
              WHERE p.Id_Producto = $producto_id";

    $result = $conn->query($query);
    if ($row = $result->fetch_assoc()) {
        $pdf->MultiCell(0, 10, 
            "Producto: {$row['Nombre_Producto']}\nDescripción: {$row['Descripcion_Producto']}\nPrecio: {$row['Precio']}\nStock: {$row['Cantidad_Stock']}\nCódigo de Barras: {$row['Codigo_Barras']}\nPeso: {$row['Peso']}", 1, 'L');
        $pdf->MultiCell(0, 10, 
            "Categoría: {$row['Nombre_Categoria']}\nClasificación: {$row['Nombre_Clasificacion']}\nMarca: {$row['Nombre_Marca']}\nProveedor: {$row['Nombre_Proveedor']}", 1, 'L');
        $pdf->MultiCell(0, 10, 
            "Promoción Activa: {$row['Promocion_Activa']}\nFecha Inicio: {$row['Fecha_Inicio']}\nFecha Fin: {$row['Fecha_Fin']}", 1, 'L');

        // Ventas
        $queryVentas = "SELECT SUM(t.Cantidad) AS Total_Vendido 
                        FROM transacciones t
                        WHERE t.Id_Producto = {$row['Id_Producto']} 
                        AND t.Id_Tipo_Transaccion = 2 
                        AND t.Fecha_Transaccion BETWEEN '$fecha_inicio' AND '$fecha_fin'";
        $ventasResult = $conn->query($queryVentas);
        $ventas = $ventasResult->fetch_assoc();

        // Entradas
        $queryEntradas = "SELECT SUM(t.Cantidad) AS Total_Entradas 
                          FROM transacciones t
                          WHERE t.Id_Producto = {$row['Id_Producto']} 
                          AND t.Id_Tipo_Transaccion = 1 
                          AND t.Fecha_Transaccion BETWEEN '$fecha_inicio' AND '$fecha_fin'";
        $entradasResult = $conn->query($queryEntradas);
        $entradas = $entradasResult->fetch_assoc();

        $pdf->MultiCell(0, 10, 
            "Total Vendido (Salidas): " . ($ventas['Total_Vendido'] ?? 0) . "\nTotal Entradas: " . ($entradas['Total_Entradas'] ?? 0), 1, 'L');
    }

} elseif ($filtro == 'categoria' && $categoria_id) {
    $query = "SELECT Id_Categoria, Nombre_Categoria, Descripcion_Categoria FROM categoria WHERE Id_Categoria = $categoria_id";
    $result = $conn->query($query);
    if ($row = $result->fetch_assoc()) {
        $pdf->MultiCell(0, 10, 
            "Categoría: {$row['Nombre_Categoria']}\nDescripción: {$row['Descripcion_Categoria']}", 1, 'L');

        $queryProductos = "SELECT p.Id_Producto, p.Nombre_Producto, p.Precio, p.Cantidad_Stock 
                           FROM producto p 
                           WHERE p.Id_Categoria = {$row['Id_Categoria']}";
        $productosResult = $conn->query($queryProductos);
        while ($producto = $productosResult->fetch_assoc()) {
            $queryVentas = "SELECT SUM(t.Cantidad) AS Total_Vendido 
                            FROM transacciones t
                            WHERE t.Id_Producto = {$producto['Id_Producto']} 
                            AND t.Id_Tipo_Transaccion = 2 
                            AND t.Fecha_Transaccion BETWEEN '$fecha_inicio' AND '$fecha_fin'";
            $ventas = $conn->query($queryVentas)->fetch_assoc();

            $queryEntradas = "SELECT SUM(t.Cantidad) AS Total_Entradas 
                              FROM transacciones t
                              WHERE t.Id_Producto = {$producto['Id_Producto']} 
                              AND t.Id_Tipo_Transaccion = 1 
                              AND t.Fecha_Transaccion BETWEEN '$fecha_inicio' AND '$fecha_fin'";
            $entradas = $conn->query($queryEntradas)->fetch_assoc();

            $pdf->MultiCell(0, 10, 
                "Producto: {$producto['Nombre_Producto']}\nPrecio: {$producto['Precio']}\nStock: {$producto['Cantidad_Stock']}\nTotal Vendido: " . ($ventas['Total_Vendido'] ?? 0) . "\nTotal Entradas: " . ($entradas['Total_Entradas'] ?? 0), 1, 'L');
        }
    }

} elseif ($filtro == 'clasificacion' && $clasificacion_id) {
    $query = "SELECT Id_Clasificacion, Nombre_Clasificacion FROM clasificacion WHERE Id_Clasificacion = $clasificacion_id";
    $result = $conn->query($query);
    if ($row = $result->fetch_assoc()) {
        $pdf->MultiCell(0, 10, "Clasificación: {$row['Nombre_Clasificacion']}", 1, 'L');

        $queryProductos = "SELECT p.Id_Producto, p.Nombre_Producto, p.Precio, p.Cantidad_Stock 
                           FROM producto p 
                           WHERE p.Id_Clasificacion = {$row['Id_Clasificacion']}";
        $productosResult = $conn->query($queryProductos);
        while ($producto = $productosResult->fetch_assoc()) {
            $queryVentas = "SELECT SUM(t.Cantidad) AS Total_Vendido 
                            FROM transacciones t
                            WHERE t.Id_Producto = {$producto['Id_Producto']} 
                            AND t.Id_Tipo_Transaccion = 2 
                            AND t.Fecha_Transaccion BETWEEN '$fecha_inicio' AND '$fecha_fin'";
            $ventas = $conn->query($queryVentas)->fetch_assoc();

            $queryEntradas = "SELECT SUM(t.Cantidad) AS Total_Entradas 
                              FROM transacciones t
                              WHERE t.Id_Producto = {$producto['Id_Producto']} 
                              AND t.Id_Tipo_Transaccion = 1 
                              AND t.Fecha_Transaccion BETWEEN '$fecha_inicio' AND '$fecha_fin'";
            $entradas = $conn->query($queryEntradas)->fetch_assoc();

            $pdf->MultiCell(0, 10, 
                "Producto: {$producto['Nombre_Producto']}\nPrecio: {$producto['Precio']}\nStock: {$producto['Cantidad_Stock']}\nTotal Vendido: " . ($ventas['Total_Vendido'] ?? 0) . "\nTotal Entradas: " . ($entradas['Total_Entradas'] ?? 0), 1, 'L');
        }
    }
}

// Cerrar conexión
$conn->close();

// Salida del PDF
$pdf->Output('informe.pdf', 'D');
?>
