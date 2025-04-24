<?php
include 'conexion.php';

// Consulta de productos con estado de producto
$sql_productos = "SELECT p.Id_Producto, p.Nombre_Producto, p.Cantidad_Stock, p.Precio, e.Tipo_Estado_Producto 
                  FROM producto p 
                  INNER JOIN Estado_Producto e ON p.Id_Estado_Producto = e.Id_Estado_Producto";
$result_productos = $conn->query($sql_productos);

// Consulta de transacciones
$sql_transacciones = "SELECT 
                        t.Id_Transacciones, 
                        p.Nombre_Producto AS Producto, 
                        CASE 
                            WHEN tt.Id_Tipo_Transaccion = 1 THEN 'Entrada' 
                            WHEN tt.Id_Tipo_Transaccion = 2 THEN 'Salida' 
                            ELSE 'Desconocido' 
                        END AS Tipo, 
                        t.Cantidad, 
                        t.Fecha_Transaccion 
                      FROM transacciones t 
                      INNER JOIN producto p ON t.Id_Producto = p.Id_Producto 
                      INNER JOIN tipo_transaccion tt ON t.Id_Tipo_Transaccion = tt.Id_Tipo_Transaccion 
                      ORDER BY t.Fecha_Transaccion DESC";
$result_transacciones = $conn->query($sql_transacciones);

// Código para generar PDF
if (isset($_POST['generar_pdf'])) {
    require('fpdf/fpdf.php'); // Asegúrate de que la librería esté en la carpeta correcta

    $pdf = new FPDF();
    $pdf->AddPage();

    // Título
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(200, 10, 'Reporte de Inventario', 0, 1, 'C');

    // Título de la tabla
    $pdf->SetFont('Arial', 'B', 14); // Tamaño de fuente ajustado
    $pdf->Cell(200, 10, 'Inventario actual', 0, 1, 'C');

    // Inventario Actual
    $pdf->Ln(10);
    $pdf->SetFont('Arial', 'B', 10); // Tamaño de fuente ajustado para las celdas
    $pdf->Cell(30, 10, 'ID Producto', 1, 0, 'C');
    $pdf->Cell(60, 10, 'Nombre del Producto', 1, 0, 'C');
    $pdf->Cell(25, 10, 'Stock', 1, 0, 'C');
    $pdf->Cell(30, 10, 'Precio', 1, 0, 'C');
    $pdf->Cell(35, 10, 'Estado', 1, 1, 'C');

    // Datos de productos
    $pdf->SetFont('Arial', '', 10); // Ajuste de fuente para los datos
    if ($result_productos->num_rows > 0) {
        while ($row = $result_productos->fetch_assoc()) {
            $pdf->Cell(30, 10, $row['Id_Producto'], 1, 0, 'C');
            $pdf->Cell(60, 10, $row['Nombre_Producto'], 1, 0, 'C');
            $pdf->Cell(25, 10, $row['Cantidad_Stock'], 1, 0, 'C');
            $pdf->Cell(30, 10, number_format($row['Precio'], 2), 1, 0, 'C');
            $pdf->Cell(35, 10, $row['Tipo_Estado_Producto'], 1, 1, 'C');
        }
    } else {
        $pdf->Cell(180, 10, 'No hay productos en el inventario', 1, 1, 'C');
    }

    // Título de transacciones
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(200, 10, 'Historial de transacciones', 0, 1, 'C');

    // Historial de Transacciones
    $pdf->Ln(10);
    $pdf->SetFont('Arial', 'B', 10); // Ajuste de tamaño de fuente
    $pdf->Cell(30, 10, 'ID Transacción', 1, 0, 'C');
    $pdf->Cell(50, 10, 'Producto', 1, 0, 'C');
    $pdf->Cell(30, 10, 'Tipo', 1, 0, 'C');
    $pdf->Cell(25, 10, 'Cantidad', 1, 0, 'C');
    $pdf->Cell(40, 10, 'Fecha', 1, 1, 'C');

    // Datos de transacciones
    $pdf->SetFont('Arial', '', 10); // Ajuste de fuente
    if ($result_transacciones->num_rows > 0) {
        while ($row = $result_transacciones->fetch_assoc()) {
            $pdf->Cell(30, 10, $row['Id_Transacciones'], 1, 0, 'C');
            $pdf->Cell(50, 10, $row['Producto'], 1, 0, 'C');
            $pdf->Cell(30, 10, $row['Tipo'], 1, 0, 'C');
            $pdf->Cell(25, 10, $row['Cantidad'], 1, 0, 'C');
            $pdf->Cell(40, 10, $row['Fecha_Transaccion'], 1, 1, 'C');
        }
    } else {
        $pdf->Cell(180, 10, 'No hay transacciones registradas', 1, 1, 'C');
    }

    // Salvar y descargar el PDF
    $pdf->Output('D', 'reporte_inventario.pdf');
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Inventario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-image: url('cuadro.jpg');
            background-size: cover;
            background-attachment: fixed;
            background-position: center;
            background-repeat: no-repeat;
            min-height: 100vh;
        }

        .table {
            background-color: rgba(255, 255, 255, 0.9);
            border: 1px solid #ddd;
        }

        .pdf-btn {
            margin-bottom: 20px;
            padding: 10px 30px;
            background-color: #dc3545;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .pdf-btn:hover {
            background-color: #c82333;
        }

        .btn-back {
            margin-top: -3px;
            background-color: #28a745;
            color: white;
            padding: 10px 30px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
        }

        .btn-back:hover {
            background-color: #218838;
        }

        .container {
            max-width: 1000px;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">
        <h2>Promedic</h2>
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav me-auto mb-3 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="http://localhost/PROMEDIC/ROL_ADMIN/Crud/">Inicio</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="http://localhost/PROMEDIC/ROL_ADMIN/Generar%20informes/Informes.php">Informes específicos</a>
          </li>
          <form method="POST" action="">
          <li class="nav-item">
            <button type="submit" name="generar_pdf" class="pdf-btn">
                <i class="bi bi-file-earmark-pdf"></i> Generar PDF
            </button>
          </li>
          </form>
        </ul>
      </div>
    </div>
</nav>

<div class="container mt-5">
    <h1 class="text-center mb-4">Reporte de Inventario</h1>
    
    <!-- Sección de Inventario -->
    <h3>Inventario Actual</h3>
    <table class="table table-bordered table-hover mb-5">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nombre del Producto</th>
                <th>Cantidad Actual</th>
                <th>Precio</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result_productos->num_rows > 0) {
                while ($row = $result_productos->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['Id_Producto']}</td>
                            <td>{$row['Nombre_Producto']}</td>
                            <td>{$row['Cantidad_Stock']}</td>
                            <td>{$row['Precio']}</td>
                            <td>{$row['Tipo_Estado_Producto']}</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No hay productos disponibles.</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- Sección de Transacciones -->
    <h3>Historial de Transacciones</h3>
    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID Transacción</th>
                <th>Producto</th>
                <th>Tipo</th>
                <th>Cantidad</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result_transacciones->num_rows > 0) {
                while ($row = $result_transacciones->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['Id_Transacciones']}</td>
                            <td>{$row['Producto']}</td>
                            <td>{$row['Tipo']}</td>
                            <td>{$row['Cantidad']}</td>
                            <td>{$row['Fecha_Transaccion']}</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No hay transacciones disponibles.</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <a href="http://localhost/PROMEDIC/ROL_ADMIN/Generar%20informes/Informes.php" class="btn-back">Volver</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
