<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña</title>
    <link rel="stylesheet" href="ASS.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>
    <section>
        <div class="contenedor">
            <div class="formulario">
                <form action="enviar_correo.php" method="POST">
                    <h2>Recuperar Contraseña</h2>

                    <div class="input-contenedor">
                        <i class="fa-solid fa-envelope"></i>
                        <!-- Campo de correo electrónico -->
                        <input type="email" name="email" id="email" required placeholder="ejemplo@dominio.com">
                        <label for="email">Correo electrónico</label>
                    </div>

                    <!-- Botón de envío -->
                    <button type="submit">Enviar enlace de recuperación</button>
                </form>
            </div>
        </div>
    </section>
</body>
</html>

