<?php 
// Iniciar la sesión
session_start();

// Destruir todas las variables de sesión
$_SESSION = [];

// Borrar la cookie de sesión si existe
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, 
        $params["path"], $params["domain"], 
        $params["secure"], $params["httponly"]
    );
}

// Destruir la sesión completamente
session_destroy();

// Evitar que el usuario pueda regresar con el botón "Atrás" del navegador
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Redirigir a la página de inicio de sesión después de un pequeño retraso
echo "<script>
    setTimeout(function() {
        window.location.href = 'http://localhost/PROMEDIC/Iniciar_sesion/iniciar_sesion.php';
    }, 100);
    
    // Bloquear el botón de atrás
    history.pushState(null, null, location.href);
    window.onpopstate = function () {
        history.go(1);
    };
</script>";
exit();
?>