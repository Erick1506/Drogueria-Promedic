<?php
include 'conexion_proveedor.php'; // Conexión a la base de datos

// Obtener el ID del proveedor que se desea editar
$id = $_GET['id'];

// Preparar la consulta SQL para obtener los datos del proveedor
$stmt = $pdo->prepare('SELECT * FROM proveedor WHERE Id_Proveedor = ?');
$stmt->execute([$id]);
$proveedor = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Proveedor</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <div class="container">
        <div class="container-form">
            <h2>Actualizar Proveedor</h2>
            <form action="actualizar_proveedor.php" method="POST">
                <!-- Campo oculto para almacenar el ID del proveedor -->
                <input type="hidden" name="id_proveedor" value="<?php echo htmlspecialchars($proveedor['Id_Proveedor']); ?>">

                <div class="container-input">
                    <input type="text" name="nuevo_nombre" placeholder="Nuevo nombre" value="<?php echo htmlspecialchars($proveedor['Nombre_Proveedor']); ?>" required>
                </div>
                <div class="container-input">
                    <input type="text" name="nueva_direccion" placeholder="Nueva dirección" value="<?php echo htmlspecialchars($proveedor['Direccion_Proveedor']); ?>" required>
                </div>
                <div class="container-input">
                    <input type="email" name="nuevo_correo" placeholder="Nuevo correo" value="<?php echo htmlspecialchars($proveedor['Correo']); ?>" required>
                </div>
                <div class="container-input">
                    <input type="text" name="nuevo_telefono" placeholder="Nuevo teléfono" value="<?php echo htmlspecialchars($proveedor['Telefono']); ?>">
                </div>
                <div class="container-input">
                    <input type="text" name="nuevo_administrador" placeholder="ID Administrador" value="<?php echo htmlspecialchars($proveedor['Id_Administrador']); ?>">
                </div>

                <button type="submit" name="accion" value="actualizar" class="button">Actualizar</button>
            </form>
        </div>
    </div>
</body>
</html>
