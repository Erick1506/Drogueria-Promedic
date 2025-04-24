<?php
// Conexión a la base de datos
include('Conexion.php');

// Verificar si la conexión está definida
if (!isset($mysqli)) {
    die("Error: La conexión a la base de datos no se ha establecido correctamente.");
}

// Inicializar variables
$tokenValido = false;
$mensajeError = "";

// Validar si el token existe en la URL
if (isset($_GET['token']) && !empty($_GET['token'])) {
    $token = $_GET['token'];

    try {
        // Verificar que el token sea válido y no haya expirado para regente
        $queryRegente = "SELECT * FROM regente WHERE token_recuperacion = ? AND token_expiracion > NOW()";
        $stmtRegente = $mysqli->prepare($queryRegente);
        $stmtRegente->bind_param("s", $token);
        $stmtRegente->execute();
        $resultRegente = $stmtRegente->get_result();

        // Verificar que el token sea válido y no haya expirado para administrador
        $queryAdmin = "SELECT * FROM administrador WHERE token_recuperacion = ? AND token_expiracion > NOW()";
        $stmtAdmin = $mysqli->prepare($queryAdmin);
        $stmtAdmin->bind_param("s", $token);
        $stmtAdmin->execute();
        $resultAdmin = $stmtAdmin->get_result();

        // Comprobar si el token es válido para alguno de los usuarios
        if ($resultRegente->num_rows > 0 || $resultAdmin->num_rows > 0) {
            $tokenValido = true;
        } else {
            $mensajeError = "El enlace de restablecimiento es inválido o ha expirado.";
        }
    } catch (Exception $e) {
        $mensajeError = "Error al verificar el token: " . $e->getMessage();
    }
} else {
    $mensajeError = "No se proporcionó un token válido.";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña</title>
    <link rel="stylesheet" href="ASS.css">
</head>
<body>
<section>
    <div class="contenedor">
        <div class="formulario">
            <?php if ($tokenValido): ?>
                <!-- Formulario para restablecer la contraseña -->
                <form action="actualizar_contraseña.php" method="POST">
                    <h2>Restablecer Contraseña</h2>
                    <div class="input-contenedor">
                        <i class="fa-solid fa-lock"></i>
                        <input type="password" name="nueva_contraseña" id="newPassword" required>
                        <label for="newPassword">Nueva Contraseña</label>
                    </div>
                    <!-- El token se pasa como un campo oculto -->
                    <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                    <button type="submit">Restablecer</button>
                </form>
            <?php else: ?>
                <!-- Mensaje de error -->
                <h2>Error</h2>
                <p><?php echo htmlspecialchars($mensajeError); ?></p>
                <a href="http://localhost/PROMEDIC/Iniciar_sesion/solicitar_recuperacion.php">Volver a solicitar restablecimiento</a>
            <?php endif; ?>
        </div>
    </div>
</section>
<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.1.1/crypto-js.min.js"></script>
</body>
</html>
