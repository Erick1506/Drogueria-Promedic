<?php
// Conexión a la base de datos
$servername = "localhost:3307";
$username = "root";
$password = "";
$database = "promedicch";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Consulta para obtener promociones con datos del producto y tipo de promoción
$sql = "SELECT 
            p.Id_Promocion, 
            prod.Nombre_Producto, 
            prod.Precio, 
            prod.Descripcion_Producto, 
            prod.Cantidad_Stock, 
            p.Fecha_Inicio, 
            p.Fecha_Fin, 
            tp.Tipo_Promocion
        FROM Promocion p
        JOIN Producto prod ON p.Id_Producto = prod.Id_Producto
        JOIN Tipo_Promocion tp ON p.Id_Tipo_Promocion = tp.Id_Tipo_Promocion";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Promociones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .promo-card {
            border: 2px solid #007bff;
            border-radius: 8px;
            padding: 15px;
            margin: 15px 0;
            background-color: #fff;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h1 class="text-center text-primary mb-4">Gestión de Promociones</h1>

        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="promo-card">
                    <h4 class="text-primary">Producto: <?php echo htmlspecialchars($row['Nombre_Producto']); ?></h4>
                    <p><strong>Precio:</strong> $<?php echo number_format($row['Precio'], 2); ?></p>
                    <p><strong>Descripción:</strong> <?php echo htmlspecialchars($row['Descripcion_Producto']); ?></p>
                    <p><strong>Cantidad en stock:</strong> <?php echo $row['Cantidad_Stock']; ?></p>
                    <hr>
                    <form action="editar_promocion.php" method="POST" class="mb-3">
                        <input type="hidden" name="Id_Promocion" value="<?php echo $row['Id_Promocion']; ?>">
                        <div class="mb-3">
                            <label for="Tipo_Promocion" class="form-label">Tipo de Promoción</label>
                            <select name="Id_Tipo_Promocion" class="form-select" required>
                                <option value="1" <?php echo ($row['Tipo_Promocion'] == '2 por 1') ? 'selected' : ''; ?>>2 por 1</option>
                                <option value="2" <?php echo ($row['Tipo_Promocion'] == 'Descuento') ? 'selected' : ''; ?>>Descuento</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="Fecha_Fin" class="form-label">Fecha Fin</label>
                            <input type="date" name="Fecha_Fin" class="form-control" value="<?php echo $row['Fecha_Fin']; ?>" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                        <a href="eliminar_promocion.php?id=<?php echo $row['Id_Promocion']; ?>" class="btn btn-danger">Eliminar</a>
                    </form>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-center">No hay promociones disponibles.</p>
        <?php endif; ?>
    </div>
</body>
</html>

<?php
$conn->close();
?>