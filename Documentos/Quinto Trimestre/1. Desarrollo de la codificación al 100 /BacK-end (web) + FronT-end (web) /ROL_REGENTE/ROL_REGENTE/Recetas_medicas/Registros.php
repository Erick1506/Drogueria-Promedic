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
    <title>Registros de Fórmulas Médicas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="Registro.css" rel="stylesheet">
    <style>
        .btn {
            font-size: 1.2rem; /* Aumentar el tamaño de los botones */
            padding: 10px 20px; /* Añadir relleno para hacer los botones más grandes */
            width: 100%; /* Hacer los botones del 100% del ancho del contenedor */
        }

        .card-body {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
        }

        .button-container {
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .card {
            height: 100%;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"><h2>Promedic</h2></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="../Crud/index.php">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../Recetas_medicas/Formularioo.php">Registrar fórmula Médica</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h3 class="text-center">Registros de Fórmulas Médicas</h3>
        <input type="text" id="buscar" class="form-control mb-3" placeholder="Buscar por nombre" oninput="filtrarRegistros()">
        <div id="registros" class="row"></div>
    </div>

    <!-- Modal para editar -->
    <div class="modal fade" id="editarModal" tabindex="-1" aria-labelledby="editarModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editarModalLabel">Editar Registro</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editarForm" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="editIdentificacion" class="form-label">Número de Identificación</label>
                            <input type="text" class="form-control" id="editIdentificacion" required>
                        </div>
                        <div class="mb-3">
                            <label for="editNombre" class="form-label">Nombre Completo</label>
                            <input type="text" class="form-control" id="editNombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="editFecha" class="form-label">Fecha de Inserción</label>
                            <input type="date" class="form-control" id="editFecha" required>
                        </div>
                        <div class="mb-3">
                            <label for="editImagen" class="form-label">Subir Nueva Imagen</label>
                            <input type="file" class="form-control" id="editImagen" accept="image/*">
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Script -->
    <script>
        const registrosContainer = document.getElementById('registros');
        const buscarInput = document.getElementById('buscar');
        let registros = [];

        // Obtener registros desde el servidor
        async function cargarRegistros() {
            const response = await fetch('getRegistros.php');
            const data = await response.json();
            if (data.success !== false) {
                registros = data;
                mostrarRegistros();
            } else {
                alert('No se pudieron cargar los registros');
            }
        }

        // Mostrar registros en la página
        function mostrarRegistros() {
            registrosContainer.innerHTML = registros.length > 0
                ? registros.map((receta, index) => `
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-body text-center">
                                <h5 class="card-title">${receta.Nombre_Paciente}</h5>
                                <p class="card-text">Identificación: ${receta.Identificacion_Paciente}</p>
                                <p class="card-text">Fecha: ${receta.Fecha_Insercion}</p>
                                <img src="${receta.Imagen}" alt="Imagen de fórmula médica" class="card-img-top">
                                <div class="button-container mt-3">
                                    <button onclick="abrirModalEditar(${index})" class="btn btn-warning">Editar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                `).join('')
                : `<p class="text-center text-muted">No hay registros disponibles</p>`;
        }

        // Función de búsqueda
        function filtrarRegistros() {
            const filtro = buscarInput.value.toLowerCase();
            const registrosFiltrados = registros.filter(registro => registro.Nombre_Paciente.toLowerCase().includes(filtro));
            mostrarRegistrosFiltrados(registrosFiltrados);
        }

        // Mostrar registros filtrados
        function mostrarRegistrosFiltrados(registrosFiltrados) {
            registrosContainer.innerHTML = registrosFiltrados.length > 0
                ? registrosFiltrados.map((receta, index) => `
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-body text-center">
                                <h5 class="card-title">${receta.Nombre_Paciente}</h5>
                                <p class="card-text">Identificación: ${receta.Identificacion_Paciente}</p>
                                <p class="card-text">Fecha: ${receta.Fecha_Insercion}</p>
                                <img src="${receta.Imagen}" alt="Imagen de fórmula médica" class="card-img-top">
                                <div class="button-container mt-3">
                                    <button onclick="abrirModalEditar(${index})" class="btn btn-warning">Editar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                `).join('')
                : `<p class="text-center text-muted">No se encontraron resultados</p>`;
        }

        // Abrir modal para editar
        function abrirModalEditar(index) {
            const receta = registros[index];
            document.getElementById('editIdentificacion').value = receta.Identificacion_Paciente;
            document.getElementById('editNombre').value = receta.Nombre_Paciente;
            document.getElementById('editFecha').value = receta.Fecha_Insercion;
            document.getElementById('editarForm').onsubmit = (e) => guardarCambios(e, index);
            new bootstrap.Modal(document.getElementById('editarModal')).show();
        }

        // Guardar los cambios realizados en el modal
        async function guardarCambios(e, index) {
            e.preventDefault();

            const identificacion = document.getElementById('editIdentificacion').value;
            const nombre = document.getElementById('editNombre').value;
            const fecha = document.getElementById('editFecha').value;
            const imagen = document.getElementById('editImagen').files[0];

            const formData = new FormData();
            formData.append('Identificacion_Actual', registros[index].Identificacion_Paciente);
            formData.append('Nueva_Identificacion', identificacion);
            formData.append('Nombre_Paciente', nombre);
            formData.append('Fecha_Insercion', fecha);
            if (imagen) formData.append('Imagen', imagen);

            try {
                const response = await fetch('editarRegistro.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();
                if (result.success) {
                    registros[index].Identificacion_Paciente = identificacion;
                    registros[index].Nombre_Paciente = nombre;
                    registros[index].Fecha_Insercion = fecha;
                    if (imagen) {
                        registros[index].Imagen = result.imagen_destino;
                    }
                    mostrarRegistros();
                    bootstrap.Modal.getInstance(document.getElementById('editarModal')).hide();
                    alert('Registro actualizado con éxito');
                } else {
                    alert('Error al actualizar el registro: ' + result.message);
                }
            } catch (error) {
                console.error(error);
                alert('No se pudo actualizar el registro. Intenta más tarde.');
            }
        }

        // Función para eliminar registro
        async function eliminarRegistro(identificacion) {
            const confirmacion = confirm('¿Estás seguro de que deseas eliminar este registro?');
            if (!confirmacion) return;

            const formData = new FormData();
            formData.append('Identificacion_Paciente', identificacion);

            try {
                const response = await fetch('eliminarRegistro.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();
                if (result.success) {
                    registros = registros.filter(registro => registro.Identificacion_Paciente !== identificacion);
                    mostrarRegistros();
                    alert('Registro eliminado con éxito');
                } else {
                    alert('Error al eliminar el registro: ' + result.message);
                }
            } catch (error) {
                console.error(error);
                alert('No se pudo eliminar el registro. Intenta más tarde.');
            }
        }

        // Cargar los registros al iniciar
        cargarRegistros();
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
