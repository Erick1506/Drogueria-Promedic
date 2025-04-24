<?php
include 'conexion_regente.php'; // Conexión a la base de datos

// Obtener el ID del regente que se desea editar
$id = $_GET['id'] ?? null;

if ($id) {
    // Preparar la consulta SQL para obtener los datos del regente
    $stmt = $pdo->prepare('SELECT * FROM regente WHERE Id_Regente = ?');
    $stmt->execute([$id]);
    $regente = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verificar si el regente existe
    if (!$regente) {
        echo "Regente no encontrado.";
        exit;
    }
} else {
    echo "ID de regente no proporcionado.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Regente</title>
    <link rel="stylesheet" href="e.css">
</head>
<body>
    
    
    <div class="container">
        <div class="container-form">
            <h2>Actualizar Regente</h2>
            <form action="actualizar_regente.php" method="POST">
                <!-- Campo oculto para almacenar el ID del regente -->
                <input type="hidden" name="id_regente" value="<?php echo htmlspecialchars($regente['Id_Regente']); ?>">

                <div class="container-input">
                    <input type="text" name="nuevo_nombre" placeholder="Nuevo nombre" value="<?php echo htmlspecialchars($regente['Nombre']); ?>" required>
                </div>
                <div class="container-input">
                    <input type="text" name="nuevo_apellido" placeholder="Nuevo apellido" value="<?php echo htmlspecialchars($regente['Apellido']); ?>" required>
                </div>
                <div class="container-input">
                    <input type="text" name="nuevo_dni" placeholder="Nuevo DNI" value="<?php echo htmlspecialchars($regente['DNI']); ?>" required>
                </div>
                <div class="container-input">
                    <input type="text" name="nueva_fecha_contratacion" placeholder="Nueva fecha de contratación" value="<?php echo htmlspecialchars($regente['Fecha_Contratacion']); ?>" required>
                </div>
                <div class="container-input">
                    <input type="text" name="nueva_licencia" placeholder="Nueva licencia" value="<?php echo htmlspecialchars($regente['Licencia']); ?>" required>
                </div>
                <div class="container-input">
                    <input type="email" name="nuevo_correo" placeholder="Nuevo correo" value="<?php echo htmlspecialchars($regente['Correo']); ?>" required>
                </div>
                <div class="container-input">
                    <input type="text" name="nuevo_telefono" placeholder="Nuevo teléfono" value="<?php echo htmlspecialchars($regente['Telefono']); ?>">
                </div>
                <div class="container-input">
                    <input type="text" name="nuevo_turno" placeholder="Nuevo turno" value="<?php echo htmlspecialchars($regente['Id_Turno']); ?>">
                </div>

                <button type="submit" name="accion" value="actualizar" class="button">Actualizar</button>
            </form>
        </div>
    </div>
</body>
</html>
