<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registros de Fórmulas Médicas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="Registro.css" rel="stylesheet">
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
        <input type="text" id="buscar" class="form-control mb-3" placeholder="Buscar por nombre">
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

    <script>
        const registrosContainer = document.getElementById('registros');
        let registros = JSON.parse(localStorage.getItem('registros')) || [];

        // Mostrar registros
        function mostrarRegistros() {
            registrosContainer.innerHTML = registros.length > 0
                ? registros.map((receta, index) => `
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body text-center">
                                <h5 class="card-title">${receta.Nombre_Paciente}</h5>
                                <p class="card-text">Identificación: ${receta.Identificacion_Paciente}</p>
                                <p class="card-text">Fecha: ${receta.Fecha_Insercion}</p>
                                <img src="${receta.Imagen}" alt="Imagen de fórmula médica" class="card-img-top">
                                <button onclick="abrirModalEditar(${index})" class="btn btn-warning mt-2">Editar</button>
                            </div>
                        </div>
                    </div>
                `).join('')
                : `<p class="text-center text-muted">No hay registros disponibles</p>`;
        }

      

        // Abrir modal para editar
        function abrirModalEditar(index) {
            const receta = registros[index];
            // Llenamos los campos con los datos actuales de la receta
            document.getElementById('editIdentificacion').value = receta.Identificacion_Paciente;
            document.getElementById('editNombre').value = receta.Nombre_Paciente;
            document.getElementById('editFecha').value = receta.Fecha_Insercion;
            document.getElementById('editarForm').onsubmit = (e) => guardarCambios(e, index);
            // Abrir el modal
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
            formData.append('Identificacion_Paciente', identificacion);
            formData.append('Nombre_Paciente', nombre);
            formData.append('Fecha_Insercion', fecha);
            formData.append('Id_Administrador', 1); // Si hay un administrador, agregar su ID aquí (si aplica)
            if (imagen) formData.append('Imagen', imagen);

            try {
                const response = await fetch('editarRegistro.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();
                if (result.success) {
                    // Actualizar el registro en localStorage
                    if (imagen) {
                        registros[index].Imagen = result.imagen_destino;  // Actualizamos la imagen si se subió una nueva
                    }
                    registros[index].Identificacion_Paciente = identificacion;
                    registros[index].Nombre_Paciente = nombre;
                    registros[index].Fecha_Insercion = fecha;

                    localStorage.setItem('registros', JSON.stringify(registros));
                    bootstrap.Modal.getInstance(document.getElementById('editarModal')).hide();
                    mostrarRegistros();
                    alert('Registro actualizado con éxito');
                } else {
                    alert('Error al actualizar el registro: ' + result.message);
                }
            } catch (error) {
                console.error(error);
                alert('No se pudo actualizar el registro. Intenta más tarde.');
            }
        }

        // Obtener registros al cargar
        mostrarRegistros();
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
