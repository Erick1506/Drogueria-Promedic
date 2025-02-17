<?php 
include('../ROL_ADMIN/Crud/Conexion.php');

session_start();
session_regenerate_id(true);

// Verificar si el usuario ya ha iniciado sesión y redirigirlo a su área correspondiente
if (isset($_SESSION['regente_id'])) {
    header("Location: http://localhost/PROMEDIC/ROL_REGENTE/Crud/");
    exit();
} elseif (isset($_SESSION['admin_id'])) {
    header("Location: http://localhost/PROMEDIC/ROL_ADMIN/Crud/index.php");
    exit();
}

// Bloquear caché del navegador para evitar que se pueda regresar con "Atrás"
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = isset($_POST['correo']) ? trim($_POST['correo']) : '';
    $contraseña = isset($_POST['contraseña']) ? trim($_POST['contraseña']) : '';

    if (!empty($correo) && !empty($contraseña)) {
        $stmtRegente = $conn->prepare("SELECT * FROM regente WHERE Correo = ?");
        $stmtRegente->bind_param("s", $correo);
        $stmtRegente->execute();
        $resultadoRegente = $stmtRegente->get_result();
        $regente = $resultadoRegente->fetch_assoc();
        $stmtRegente->close();

        if ($regente) {
            if (password_verify($contraseña, $regente['Contraseña_Encriptada'])) {
                $_SESSION['regente_id'] = $regente['Id_Regente'];
                $_SESSION['nombre_regente'] = $regente['Nombre'];
                $_SESSION['correo_regente'] = $regente['Correo'];
                header("Location: http://localhost/PROMEDIC/ROL_REGENTE/Crud/");
                exit();
            } else {
                $mensaje = "❌ ¡Ups! Contraseña incorrecta.";
            }
        } else {
            $stmtAdmin = $conn->prepare("SELECT * FROM administrador WHERE Correo = ?");
            $stmtAdmin->bind_param("s", $correo);
            $stmtAdmin->execute();
            $resultadoAdmin = $stmtAdmin->get_result();
            $admin = $resultadoAdmin->fetch_assoc();
            $stmtAdmin->close();

            if ($admin) {
                if (password_verify($contraseña, $admin['Contraseña'])) {
                    $_SESSION['admin_id'] = $admin['Id_Administrador'];
                    $_SESSION['nombre_admin'] = $admin['Nombre'];
                    $_SESSION['correo_admin'] = $admin['Correo'];
                    header("Location: http://localhost/PROMEDIC/ROL_ADMIN/Crud/index.php");
                    exit();
                } else {
                    $mensaje = "❌ ¡Ups! Contraseña incorrecta.";
                }
            } else {
                $mensaje = "❌ ¡Ups! El correo no está registrado.";
            }
        }
    } else {
        $mensaje = "⚠ ¡Ups! Debes completar todos los campos.";
    }
}
?>

<!-- Bloquear el botón "Atrás" con JavaScript -->
<script>
    history.pushState(null, null, document.URL);
    window.addEventListener("popstate", function() {
        history.pushState(null, null, document.URL);
        window.location.replace("http://localhost/PROMEDIC/Iniciar_sesion/iniciar_sesion.php");
    });
</script>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión</title>
    <link rel="stylesheet" href="ESS.css">
    <script src="https://kit.fontawesome.com/a2dd6045c4.js" crossorigin="anonymous"></script>

    <script>
        // Evitar que el usuario pueda retroceder a http://localhost/PROMEDIC/
        history.pushState(null, null, location.href);
        window.onpopstate = function () {
            history.pushState(null, null, location.href);
        };

        // Si el usuario intenta ir a http://localhost/PROMEDIC/, forzarlo a esta página
        setInterval(function() {
            if (window.location.href === "http://localhost/PROMEDIC/") {
                window.location.href = "http://localhost/PROMEDIC/Iniciar_sesion/iniciar_sesion.php";
            }
        }, 100);

        // Validar formulario antes de enviarlo
        function validarFormulario(event) {
            event.preventDefault();

            let correo = document.getElementById("email").value.trim();
            let contraseña = document.getElementById("password").value.trim();
            let mensajeError = document.getElementById("mensajeError");

            if (correo === "" || contraseña === "") {
                mensajeError.innerText = "⚠ ¡Ups! Debes completar todos los campos.";
                mensajeError.style.display = "block";
                return;
            }

            event.target.submit();
        }

        // Ocultar el mensaje de error cuando el usuario escriba
        function ocultarError() {
            document.getElementById("mensajeError").style.display = "none";
        }
    </script>

</head>
<body>
    <section>
        <div class="contenedor">
            <div class="formulario">
                <form id="loginForm" method="POST" action="" onsubmit="validarFormulario(event)">
                    <h2>Iniciar Sesión</h2>

                    <!-- Mensaje de error dinámico -->
                    <p id="mensajeError" class="error" style="display: <?php echo empty($mensaje) ? 'none' : 'block'; ?>;">
                        <?php echo htmlspecialchars($mensaje); ?>
                    </p>

                    <div class="input-contenedor">
                        <i class="fa-solid fa-envelope"></i>
                        <input type="email" id="email" name="correo" required oninput="ocultarError()">
                        <label for="email">Correo</label>
                    </div>

                    <div class="input-contenedor">
                        <i class="fa-solid fa-lock"></i>
                        <input type="password" id="password" name="contraseña" required oninput="ocultarError()">
                        <label for="password">Contraseña</label>
                    </div>

                    <button type="submit">Acceder</button>

                    <div class="registrar">
                        <p>Recuperar <a href="solicitar_recuperacion.php">Contraseña</a></p>
                    </div>
                </form>
            </div>
        </div>
    </section>
</body>
</html>