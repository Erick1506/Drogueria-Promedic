<?php
// Iniciar sesión si no está iniciada
session_start();

// Verificar si hay sesión activa de regente
if (!isset($_SESSION['regente_id'])) {
    // Si no hay sesión activa de regente, redirigir al inicio de sesión
    $_SESSION['error_mensaje'] = "Ups, no tienes permisos para acceder a esta página.";
    header("Location: http://localhost/PROMEDIC/Iniciar_sesion/iniciar_sesion.php");
    exit();
}

// Evitar que el navegador guarde la página en caché para evitar regresar con "Atrás"
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");

// Verificar que no se intente acceder a una página de administrador
if (isset($_SESSION['admin_id'])) {
    // Redirigir a la página de administrador
    header("Location: http://localhost/PROMEDIC/ROL_ADMIN/Crud/");
    exit();
}

// Si el usuario decide cerrar sesión desde la URL, destruir la sesión
if (isset($_GET['cerrar_sesion'])) {
    session_unset();  // Elimina todas las variables de sesión
    session_destroy(); // Destruye la sesión
    header("Location: http://localhost/PROMEDIC/Iniciar_sesion/iniciar_sesion.php"); // Redirige al login
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inserción de Fórmulas Médicas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #ffffff;
            display: flex;
            flex-direction: column;
            background-image: url('./cuadro.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 100vh;
        }
        .button-group {
            display: flex;
            justify-content: center;
            gap: 10px;
        }
        .btn-custom {
            width: 150px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">
                        <h3>Inserción Fórmulas Médicas</h3>
                    </div>
                    <div class="card-body">
                        <form id="registroForm" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="identificacion" class="form-label">Número de Identificación</label>
                                <input type="text" class="form-control" id="identificacion" placeholder="Ingresa tu identificación" required>
                            </div>
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre Completo</label>
                                <input type="text" class="form-control" id="nombre" placeholder="Ingresa tu nombre completo" required>
                            </div>
                            <div class="mb-3">
                                <label for="fecha" class="form-label">Fecha de Inserción</label>
                                <input type="date" class="form-control" id="fecha" name="fecha" required>
                            </div>
                            <div class="mb-3">
                                <label for="imagen" class="form-label">Subir Imagen</label>
                                <input type="file" class="form-control" id="imagen" accept="image/*" required>
                            </div>

                            <!-- Campo oculto para el id del administrador -->
                            <input type="hidden" id="Id_Administrador" value="1"> 

                            <div class="button-group">
                                <button type="submit" class="btn btn-primary btn-custom">Registrar</button>
                                <button type="button" class="btn btn-secondary btn-custom" onclick="window.history.back()">Cancelar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('registroForm').addEventListener('submit', function(event) {
            event.preventDefault();

            const identificacion = document.getElementById('identificacion').value;
            const nombre = document.getElementById('nombre').value;
            const fecha = document.getElementById('fecha').value; // Fecha en formato yyyy-mm-dd
            const imagen = document.getElementById('imagen').files[0];
            const idadministrador = document.getElementById('Id_Administrador').value;

            // Crear un objeto FormData
            const formData = new FormData();
            formData.append('identificacion', identificacion);
            formData.append('nombre', nombre);
            formData.append('fecha', fecha);
            formData.append('imagen', imagen);
            formData.append('Id_Administrador', idadministrador);

            // Enviar datos al servidor
            fetch('Formulario.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("Registro exitoso!");
                    window.location.href = 'Registros.php';
                } else {
                    alert("Error: " + data.message);
                }
            })
            .catch(error => {
                alert("Error al enviar los datos: " + error);
            });
        });
    </script>
</body>
</html>
