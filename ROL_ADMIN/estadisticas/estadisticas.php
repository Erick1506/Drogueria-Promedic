<?php
// =====================
// 1. Conexión a la base de datos
// =====================
$servername = "localhost:3307";
$username = "root";
$password = "";
$dbname = "promedicch";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// =====================
// 2. Consulta de productos para el dropdown
// =====================
$productosQuery = "SELECT Id_Producto, Nombre_Producto FROM producto";
$productosResult = mysqli_query($conn, $productosQuery);
if (!$productosResult) {
    die("Error en la consulta de productos: " . mysqli_error($conn));
}

// Inicializar datos para la gráfica de ventas semanales
$labels = ["Semana 1", "Semana 2", "Semana 3", "Semana 4", "Semana 5"];
$ventasData = [0, 0, 0, 0, 0];

// =====================
// 3. Si se selecciona un producto, obtenemos sus ventas por semana
// =====================
if (isset($_GET['producto'])) {
    // Sanitizar la entrada (buena práctica de seguridad)
    $productoSeleccionado = mysqli_real_escape_string($conn, $_GET['producto']);

    // Consulta para obtener ventas agrupadas por semana (del mes actual)
    $ventasQuery = "
        SELECT 
            WEEK(Fecha_Venta, 1) - WEEK(DATE_FORMAT(NOW(), '%Y-%m-01'), 1) + 1 AS Semana, 
            SUM(Cantidad) AS Total_Ventas
        FROM comprobante
        WHERE Id_Producto = '$productoSeleccionado'
          AND MONTH(Fecha_Venta) = MONTH(CURRENT_DATE())
          AND YEAR(Fecha_Venta) = YEAR(CURRENT_DATE())
        GROUP BY Semana
        ORDER BY Semana
    ";
    $ventasResult = mysqli_query($conn, $ventasQuery);
    if (!$ventasResult) {
        die("Error en la consulta de ventas: " . mysqli_error($conn));
    }

    // Llenar el array de ventas con los valores obtenidos
    while ($fila = mysqli_fetch_assoc($ventasResult)) {
        $semanaIndex = intval($fila['Semana']) - 1;
        if ($semanaIndex >= 0 && $semanaIndex < count($ventasData)) {
            $ventasData[$semanaIndex] = intval($fila['Total_Ventas']);
        }
    }
}

// =====================
// 4. Categorías y clasificaciones
// =====================
$sql_categorias = "SELECT Id_Categoria, Nombre_Categoria FROM categoria";
$result_categorias = $conn->query($sql_categorias);

if (isset($_GET['categoria_id']) && is_numeric($_GET['categoria_id'])) {
    $categoria_id = intval($_GET['categoria_id']);
    $sql_clasificaciones = "SELECT Id_Clasificacion, Nombre_Clasificacion 
                            FROM clasificacion 
                            WHERE Id_Categoria = $categoria_id";
    $result_clasificaciones = $conn->query($sql_clasificaciones);
} else {
    $categoria_id = null;
    $result_clasificaciones = null;
}

if (isset($_GET['clasificacion_id']) && is_numeric($_GET['clasificacion_id'])) {
    $clasificacion_id = intval($_GET['clasificacion_id']);
    $sql_productos_clasificacion = "SELECT Id_Producto, Nombre_Producto, Cantidad_Stock 
                                    FROM producto 
                                    WHERE Id_Clasificacion = $clasificacion_id";
    $result_productos_clasificacion = $conn->query($sql_productos_clasificacion);
    $productos_clasificacion = [];
    while ($fila = $result_productos_clasificacion->fetch_assoc()) {
        $productos_clasificacion[] = $fila;
    }
} else {
    $productos_clasificacion = [];
}

// =====================
// 5. Todos los productos (por si lo necesitas en otro lado)
// =====================
$sql_todos_productos = "SELECT Nombre_Producto, Cantidad_Stock FROM producto";
$result_todos_productos = $conn->query($sql_todos_productos);
$todos_productos = [];
while ($row = $result_todos_productos->fetch_assoc()) {
    $todos_productos[] = $row;
}

// =====================
// 6. Productos más vendidos (top 5)
// =====================
$sql_productos_vendidos = "
    SELECT p.Nombre_Producto, SUM(c.Cantidad) as Total_Ventas 
    FROM comprobante c 
    INNER JOIN producto p ON c.Id_Producto = p.Id_Producto 
    GROUP BY c.Id_Producto 
    ORDER BY Total_Ventas DESC 
    LIMIT 5
";
$result_productos_vendidos = $conn->query($sql_productos_vendidos);
$productos_vendidos = [];
while ($row = $result_productos_vendidos->fetch_assoc()) {
    $productos_vendidos[] = $row;
}

// =====================
// 7. Productos menos vendidos (bottom 5)
// =====================
$sql_productos_menos_vendidos = "
    SELECT p.Nombre_Producto, SUM(c.Cantidad) as Total_Ventas 
    FROM comprobante c 
    INNER JOIN producto p ON c.Id_Producto = p.Id_Producto 
    GROUP BY c.Id_Producto 
    ORDER BY Total_Ventas ASC 
    LIMIT 5
";
$result_productos_menos_vendidos = $conn->query($sql_productos_menos_vendidos);
$productos_menos_vendidos = [];
while ($row = $result_productos_menos_vendidos->fetch_assoc()) {
    $productos_menos_vendidos[] = $row;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Panel de Estadísticas - Promedic</title>
    <!-- Bootstrap CSS -->
    <link 
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" 
        rel="stylesheet"
    >
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Fondo que utiliza la imagen "cuadro.jpg" */
        body {
            background-image: url('cuadro.jpg'); /* Asegúrate de que la ruta sea correcta */
            background-size: cover;
            background-attachment: fixed;
            background-position: center;
            background-repeat: no-repeat;
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }
    </style>
</head>
<body>

<!-- Navbar superior -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid">
    <!-- Marca/Logo -->
    <a class="navbar-brand" href="#">
      <h2 class="m-0">Promedic</h2>
    </a>
    <!-- Botón para menú colapsable en pantallas pequeñas -->
    <button 
      class="navbar-toggler" 
      type="button" 
      data-bs-toggle="collapse" 
      data-bs-target="#navbarNav" 
      aria-controls="navbarNav" 
      aria-expanded="false" 
      aria-label="Toggle navigation"
    >
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Menú de navegación -->
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <!-- Opción Inicio -->
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="http://localhost/PROMEDIC/ROL_REGENTE/Crud/">Inicio</a>
        </li>
      
        <!-- Opción Estadísticas -->
        <li class="nav-item">
          <a class="nav-link" href="http://localhost/PROMEDIC/informes/reporte.php">Generar informes</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- Contenedor principal para el panel de estadísticas -->
<div class="container my-4">
    <h1 class="mb-4 text-center">Panel de Estadísticas</h1>
    <p class="text-muted text-center">
        Bienvenido(a) a nuestro panel de estadísticas. Aquí podrás visualizar información clave 
        sobre el desempeño de tus productos, las ventas semanales y el stock disponible. 
        Utiliza los filtros de la parte superior para refinar la información que se muestra.
    </p>

    <!-- Filtros de categoría, clasificación y producto -->
    <div class="row mb-4">
        <!-- Selección de categoría -->
        <div class="col-md-4">
            <label for="categoria" class="form-label fw-bold">Selecciona una categoría:</label>
            <select id="categoria" class="form-select" 
                    onchange="window.location.href='?categoria_id=' + this.value">
                <option value="">-- Seleccione --</option>
                <?php 
                if($result_categorias) $result_categorias->data_seek(0); 
                while ($row = $result_categorias->fetch_assoc()) { 
                ?>
                    <option value="<?php echo $row['Id_Categoria']; ?>" 
                            <?php if ($categoria_id == $row['Id_Categoria']) echo 'selected'; ?>>
                        <?php echo $row['Nombre_Categoria']; ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <!-- Selección de clasificación (depende de la categoría) -->
        <div class="col-md-4">
            <label for="clasificacion" class="form-label fw-bold">Selecciona una clasificación:</label>
            <select id="clasificacion" class="form-select"
                    onchange="window.location.href='?categoria_id=<?php echo $categoria_id; ?>&clasificacion_id=' + this.value">
                <option value="">-- Seleccione --</option>
                <?php 
                if ($result_clasificaciones) {
                    $result_clasificaciones->data_seek(0);
                    while ($row = $result_clasificaciones->fetch_assoc()) { 
                ?>
                    <option value="<?php echo $row['Id_Clasificacion']; ?>"
                            <?php if (isset($clasificacion_id) && $clasificacion_id == $row['Id_Clasificacion']) echo 'selected'; ?>>
                        <?php echo $row['Nombre_Clasificacion']; ?>
                    </option>
                <?php 
                    }
                } 
                ?>
            </select>
        </div>

        <!-- Selección de producto -->
        <div class="col-md-4">
            <form method="GET" action="">
                <label for="producto" class="form-label fw-bold">Selecciona un producto:</label>
                <select name="producto" id="producto" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Selecciona --</option>
                    <?php 
                    if($productosResult) mysqli_data_seek($productosResult, 0);
                    while ($producto = mysqli_fetch_assoc($productosResult)) { 
                    ?>
                        <option value="<?= $producto['Id_Producto'] ?>" 
                            <?= (isset($_GET['producto']) && $_GET['producto'] == $producto['Id_Producto']) ? 'selected' : '' ?>>
                            <?= $producto['Nombre_Producto'] ?>
                        </option>
                    <?php } ?>
                </select>
            </form>
        </div>
    </div>
    
    <!-- Sección de gráficas -->
    <div class="row">
        <!-- Gráfica de ventas semanales -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title text-center fw-bold">Ventas Semanales</h5>
                    <p class="card-text text-muted text-center">
                        Muestra la cantidad vendida por semana del producto seleccionado, 
                        correspondiente al mes actual. Si no seleccionas un producto, no se mostrará información.
                    </p>
                    <canvas id="ventasChart"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Gráfica de productos más vendidos -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title text-center fw-bold">Productos Más Vendidos</h5>
                    <p class="card-text text-muted text-center">
                        Lista de los 5 productos que han alcanzado mayores ventas totales en la base de datos.
                    </p>
                    <canvas id="productosVendidosChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <!-- Gráfica de productos menos vendidos -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title text-center fw-bold">Productos Menos Vendidos</h5>
                    <p class="card-text text-muted text-center">
                        Lista de los 5 productos con las ventas más bajas.
                    </p>
                    <canvas id="productosMenosVendidosChart"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Gráfica de stock por clasificación -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title text-center fw-bold">Stock de Productos</h5>
                    <p class="card-text text-muted text-center">
                        Muestra la cantidad de stock disponible de los productos en la clasificación seleccionada.
                    </p>
                    <canvas id="productosClasificacionChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <p class="text-center text-muted mt-4">
        *La información mostrada se actualiza en tiempo real según los datos del sistema.
    </p>
</div> <!-- Fin container principal -->

<!-- Script para inicializar las gráficas con Chart.js -->
<script>
    // 1. Ventas semanales
    const labels = <?php echo json_encode($labels); ?>;
    const data = <?php echo json_encode($ventasData); ?>;
    const ctx = document.getElementById('ventasChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Ventas semanales',
                data: data,
                fill: false,
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.5)',
                borderWidth: 2,
                pointRadius: 5,
                pointBackgroundColor: 'rgba(75, 192, 192, 1)',
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // 2. Productos más vendidos (Top 5)
    const ctx2 = document.getElementById('productosVendidosChart').getContext('2d');
    new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode(array_column($productos_vendidos, 'Nombre_Producto')); ?>,
            datasets: [{
                label: 'Cantidad Vendida',
                data: <?php echo json_encode(array_column($productos_vendidos, 'Total_Ventas')); ?>,
                backgroundColor: '#4CAF50'
            }]
        },
        options: {
            responsive: true,
            indexAxis: 'y',
            scales: {
                x: { beginAtZero: true }
            }
        }
    });

    // 3. Productos menos vendidos (Bottom 5)
    const ctx3 = document.getElementById('productosMenosVendidosChart').getContext('2d');
    new Chart(ctx3, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode(array_column($productos_menos_vendidos, 'Nombre_Producto')); ?>,
            datasets: [{
                label: 'Cantidad Vendida',
                data: <?php echo json_encode(array_column($productos_menos_vendidos, 'Total_Ventas')); ?>,
                backgroundColor: '#FF5733'
            }]
        },
        options: {
            responsive: true,
            indexAxis: 'y',
            scales: {
                x: { beginAtZero: true }
            }
        }
    });

    // 4. Stock de productos por clasificación
    const ctx4 = document.getElementById('productosClasificacionChart').getContext('2d');
    new Chart(ctx4, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode(array_column($productos_clasificacion, 'Nombre_Producto')); ?>,
            datasets: [{
                label: 'Stock',
                data: <?php echo json_encode(array_column($productos_clasificacion, 'Cantidad_Stock')); ?>,
                backgroundColor: '#36A2EB'
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>

<!-- Bootstrap JS (para la interactividad del navbar) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
