<?php
session_start();

// Verificar si hay sesión activa y el tipo de usuario
if (!isset($_SESSION['regente_id'])) {
    // Si no hay sesión activa de regente, redirigir al inicio de sesión
    $_SESSION['error_mensaje'] = "Ups, no tienes permisos para acceder a esta página.";
    header("Location: http://localhost/PROMEDIC/Iniciar_sesion/iniciar_sesion.php");
    exit();
}

// Evitar que el usuario regrese con el botón "Atrás" (a través de caché)
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Verificar que no se intente acceder a una página de administrador
if (isset($_SESSION['admin_id'])) {
    header("Location: http://localhost/PROMEDIC/ROL_ADMIN/Crud/");
    exit();
}

// Continuar con el contenido del índice del regente
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Promedic</title>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" href="css/Dise.css">
  <style>
    .btn-custom {
      color: #fff;
      background-color: #007bff;
      border-color: #007bff;
    }

    .btn-custom:hover {
      color: #fff;
      background-color: #0056b3;
      border-color: #0056b3;
    }

    .promotion-icon,
    .provider-icon {
      cursor: pointer;
      color: #007bff;
    }

    .promotion-icon:hover,
    .provider-icon:hover {
      color: #0056b3;
    }
  </style>
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">
        <h2>Promedic</h2>
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="#">Inicio</a>
          </li>
         
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Productos
            </a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="http://localhost/PROMEDIC/ROL_REGENTE/Crud/promocion/promocion.php">Promociones</a></li>
              <li><a class="dropdown-item" href="http://localhost/PROMEDIC/ROL_REGENTE/Crud/AgregarProducto/Agregar.php">Agregar Producto</a></li>
            </ul>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Fórmulas médicas
            </a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="../Recetas_medicas/Registros.php">Fórmulas registradas</a></li>
            </ul>
          </li>
        </ul>
        <nav class="navbar navbar-light" style="background-color: #ffffff;">
          <div class="container-fluid">

<!-- Modal para Editar Producto -->
<div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProductModalLabel">Editar Producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editProductForm">
                    <input type="hidden" name="id_producto">
                    <div class="mb-3">
                        <label for="editNombreProducto" class="form-label">Nombre del Producto</label>
                        <input type="text" class="form-control" id="editNombreProducto" name="nombre_producto" required>
                    </div>
                    <div class="mb-3">
                        <label for="editDescripcionProducto" class="form-label">Descripción</label>
                        <textarea class="form-control" id="editDescripcionProducto" name="descripcion_producto" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="editPrecio" class="form-label">Precio</label>
                        <input type="number" class="form-control" id="editPrecio" name="precio" required step="0.01">
                    </div>
                    <div class="mb-3">
                        <label for="editCantidadStock" class="form-label">Cantidad en Stock</label>
                        <input type="number" class="form-control" id="editCantidadStock" name="cantidad_stock" required>
                    </div>
                    <div class="mb-3">
                        <label for="editCantidadMinima" class="form-label">Cantidad Mínima</label>
                        <input type="number" class="form-control" id="editCantidadMinima" name="cantidad_minima" required>
                    </div>
                    <div class="mb-3">
                        <label for="editCantidadMaxima" class="form-label">Cantidad Máxima</label>
                        <input type="number" class="form-control" id="editCantidadMaxima" name="cantidad_maxima" required>
                    </div>
                    <div class="mb-3">
                        <label for="editCostoAdquisicion" class="form-label">Costo de Adquisición</label>
                        <input type="number" class="form-control" id="editCostoAdquisicion" name="costo_adquisicion" required step="0.01">
                    </div>
                    <div class="mb-3">
                        <label for="editPeso" class="form-label">Peso</label>
                        <input type="text" class="form-control" id="editPeso" name="peso" required>
                    </div>
                    <div class="mb-3">
                        <label for="editFechaVencimiento" class="form-label">Fecha de Vencimiento</label>
                        <input type="date" class="form-control" id="editFechaVencimiento" name="fecha_vencimiento" required>
                    </div>
                    <div class="mb-3">
                        <label for="editCodigoBarras" class="form-label">Código de Barras</label>
                        <input type="text" class="form-control" id="editCodigoBarras" name="codigo_barras" required>
                    </div>
                    <div class="mb-3">
                        <label for="editIdMarca" class="form-label">Marca</label>
                        <select class="form-select" id="editIdMarca" name="id_marca" required>
                        <!-- Opciones de marcas -->
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editIdEstadoProducto" class="form-label">Estado del Producto</label>
                        <select class="form-select" id="editIdEstadoProducto" name="id_estado_producto" required>
                            <!-- Opciones de estados -->
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editIdCategoria" class="form-label">Categoría</label>
                        <select class="form-select" id="editIdCategoria" name="id_categoria" required>
                            <!-- Opciones de categorías -->
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editIdClasificacion" class="form-label">Clasificación</label>
                        <select class="form-select" id="editIdClasificacion" name="id_clasificacion" required>
                            <!-- Opciones de clasificaciones -->
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editIdProveedor" class="form-label">Proveedor</label>
                        <select class="form-select" id="editIdProveedor" name="id_proveedor" required>
                            <!-- Opciones de proveedores -->
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Actualizar Producto</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
// Script para manejar la edición
document.addEventListener('DOMContentLoaded', function () {
    // Manejar el evento de clic en el botón de editar
    document.querySelectorAll('.btn-edit').forEach(button => {
        button.addEventListener('click', function () {
            const productId = this.getAttribute('data-id'); // Obtiene el ID del producto

            // Realizar la solicitud para obtener los datos del producto
            fetch(`editar.php?id=${productId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data && !data.error) {
                        // Rellenar el formulario de edición con los datos obtenidos
                        document.querySelector('input[name="id_producto"]').value = data.producto.Id_Producto || '';
                        document.getElementById('editNombreProducto').value = data.producto.Nombre_Producto || '';
                        document.getElementById('editDescripcionProducto').value = data.producto.Descripcion_Producto || '';
                        document.getElementById('editPrecio').value = data.producto.Precio || '';
                        document.getElementById('editCantidadStock').value = data.producto.Cantidad_Stock || '';
                        document.getElementById('editCantidadMinima').value = data.producto.Cantidad_Minima || '';
                        document.getElementById('editCantidadMaxima').value = data.producto.Cantidad_Maxima || '';
                        document.getElementById('editCostoAdquisicion').value = data.producto.Costo_Adquisicion || '';
                        document.getElementById('editPeso').value = data.producto.Peso || '';
                        document.getElementById('editFechaVencimiento').value = data.producto.Fecha_Vencimiento || '';
                        document.getElementById('editCodigoBarras').value = data.producto.Codigo_Barras || '';
                        document.getElementById('editIdMarca').value = data.producto.Id_Marca || '';
                        document.getElementById('editIdEstadoProducto').value = data.producto.Id_Estado_Producto || '';
                        document.getElementById('editIdCategoria').value = data.producto.Id_Categoria || '';
                        document.getElementById('editIdClasificacion').value = data.producto.Id_Clasificacion || '';
                        document.getElementById('editIdProveedor').value = data.producto.Id_Proveedor || '';

                        // Actualizar las opciones de los select con los datos obtenidos
                        actualizarSelect('editIdCategoria', data.categorias);
                        actualizarSelect('editIdMarca', data.marcas);
                        actualizarSelect('editIdProveedor', data.proveedores);
                        actualizarSelect('editIdEstadoProducto', data.estados);
                        actualizarSelect('editIdClasificacion', data.clasificaciones);

                        // Mostrar el modal de edición
                        $('#editProductModal').modal('show');
                    } else {
                        console.error(data.error || 'No se encontraron datos para el producto.');
                    }
                })
                .catch(error => console.error('Error al obtener el producto:', error));
        });
    });

    // Función para actualizar los select
    function actualizarSelect(idSelect, opciones) {
        const select = document.getElementById(idSelect);
 select.innerHTML = ''; // Limpiar opciones previas

        // Verificamos si hay opciones disponibles
        if (opciones && opciones.length > 0) {
            opciones.forEach(opcion => {
                const option = document.createElement('option');
                // Asignamos el valor y el texto según el tipo de select
                if (idSelect === 'editIdMarca') {
                    option.value = opcion.Id_Marca; // Asignar el ID de la marca
                    option.text = opcion.Marca_Producto; // Asignar el nombre de la marca
                } else if (idSelect === 'editIdEstadoProducto') {
                    option.value = opcion.Id_Estado_Producto; // Asignar el ID del estado
                    option.text = opcion.Tipo_Estado_Producto; // Asignar el nombre del estado
                } else if (idSelect === 'editIdCategoria') {
                    option.value = opcion.Id_Categoria; // Asignar el ID de la categoría
                    option.text = opcion.Nombre_Categoria; // Asignar el nombre de la categoría
                } else if (idSelect === 'editIdClasificacion') {
                    option.value = opcion.Id_Clasificacion; // Asignar el ID de la clasificación
                    option.text = opcion.Nombre_Clasificacion; // Asignar el nombre de la clasificación
                } else if (idSelect === 'editIdProveedor') {
                    option.value = opcion.Id_Proveedor; // Asignar el ID del proveedor
                    option.text = opcion.Nombre_Proveedor; // Asignar el nombre del proveedor
                }
                select.appendChild(option);
            });
        } else {
            // Si no hay opciones, puedes agregar una opción por defecto
            const option = document.createElement('option');
            option.text = 'No hay opciones disponibles';
            option.disabled = true;
            select.appendChild(option);
        }
    }

    // Manejar el envío del formulario de edición
    document.getElementById('editProductForm').addEventListener('submit', function (event) {
        event.preventDefault(); // Prevenir el envío normal del formulario

        const formData = new FormData(this);

        // Realizar la solicitud para actualizar los datos del producto
        fetch('editar.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            alert(data); // Mostrar mensaje de éxito o error
            $('#editProductModal').modal('hide'); // Ocultar modal
            console.log('Recargando la página...'); // Log para verificar
            location.reload(); // Recargar la página
        })
        .catch(error => {
            console.error('Error al actualizar el producto:', error);
        });
    });
});
</script>
           <!-- Botón de Notificación -->
<button type="button" class="btn btn-outline-primary position-relative" data-bs-toggle="modal" data-bs-target="#exampleModal">
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bell" viewBox="0 0 16 16">
        <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2M8 1.918l-.797.161A4 4 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4 4 0 0 0-3.203-3.92zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5 5 0 0 1 13 6c0 .88.32 4.2 1.22 6" />
    </svg>
    <span id="notificationCount" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="display: none;">0</span>
</button>

<!-- Modal para mostrar notificaciones -->  
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Notificaciones</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      
      <div class="modal-body" id="notificationBody">
        <p>Cargando notificaciones...</p>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-tercero" id="goToDetailsBtn">Ir a detalles</button>
      </div>
    </div>
  </div>
</div>

<!-- Script para cargar y mostrar notificaciones -->
<script>
  document.getElementById('goToDetailsBtn').addEventListener('click', () => {
    window.location.href = 'http://localhost:3001'; // Cambia esta URL si es necesario
  });

  const notificationCount = document.getElementById('notificationCount');

  const obtenerTexto = (notification) => {
    if (typeof notification === 'object' && notification !== null && notification.mensaje) {
      return notification.mensaje;
    }
    return notification;
  };

  const loadNotifications = () => {
    const notificationBody = document.getElementById('notificationBody');
    notificationBody.innerHTML = '<p>Cargando notificaciones...</p>';

    Promise.all([
      fetch('get_notifications.php').then(res => res.json()),
      fetch('notificaciones.php').then(res => res.json())
    ])
      .then(([getNotifications, userNotifications]) => {
        notificationBody.innerHTML = '';
        let totalNotifications = 0;

        getNotifications.forEach(notification => {
          const mensaje = obtenerTexto(notification);
          const item = document.createElement('p');

          let iconColor;
          if (mensaje.includes('ha vencido')) {
            iconColor = 'red';
          } else if (mensaje.includes('por debajo de la cantidad mínima')) {
            iconColor = 'orange';
          } else if (mensaje.includes('superado la cantidad máxima')) {
            iconColor = 'green';
          } else {
            iconColor = 'yellow';
          }

          item.innerHTML = `<span class="bi bi-bell-fill" style="font-size: 1.2rem; color: ${iconColor};"></span> ${mensaje}`;
          item.className = "system-notification";
          notificationBody.appendChild(item);
          totalNotifications++;
        });

        userNotifications.forEach(notification => {
          if (notification) {
            const mensaje = obtenerTexto(notification);
            const item = document.createElement('p');
            item.innerHTML = `<span class="bi bi-exclamation-triangle-fill" style="font-size: 1.2rem; color: orange;"></span> AVISO A REGENTE: ${mensaje}`;
            item.className = "user-notification";
            notificationBody.appendChild(item);
            totalNotifications++;
          }
        });

        updateNotificationCount(totalNotifications);
      })
      .catch(error => {
        console.error('Error al cargar las notificaciones:', error);
        notificationBody.innerHTML = '<p>Error al cargar notificaciones.</p>';
      });
  };

  const updateNotificationCount = (totalNotifications) => {
    if (notificationCount) {
      notificationCount.textContent = totalNotifications;
      notificationCount.style.display = totalNotifications > 0 ? 'inline-block' : 'none';
    }

    if (!document.getElementById('notificationBody').innerHTML.trim()) {
      document.getElementById('notificationBody').innerHTML = '<p>No hay notificaciones.</p>';
    }
  };

  document.getElementById('exampleModal').addEventListener('show.bs.modal', loadNotifications);
</script>


            <!-- APII -->


            <!-- Icono de Lupa -->
            <div class="text-end m-3">
              <i class="bi bi-search cursor-pointer" style="font-size: 1.5rem;" data-bs-toggle="modal" data-bs-target="#searchModal"></i>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="searchModalLabel">Buscar Producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <input type="text" id="productName" class="form-control" placeholder="Ingrese el nombre del producto" />
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="searchProduct">Buscar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                  </div>
                </div>
              </div>
            </div>
            <!-- Contenedor para mostrar resultados -->
            <div id="productResults" class="m-3"></div>


<!-- Barra de búsqueda -->
<form class="d-flex mx-2" id="searchForm" onsubmit="return false;">
    <input class="form-control me-2" type="search" placeholder="Buscar por ID o nombre" aria-label="Search" id="searchInput">
    <button class="btn btn-custom" type="button" id="searchButton">Buscar</button>
</form>

<script>
document.getElementById("searchButton").addEventListener("click", function() {
    realizarBusqueda();
});

// Realizar búsqueda mientras escribes
document.getElementById("searchInput").addEventListener("input", function() {
    realizarBusqueda();
});

// Limpiar los resultados cuando el campo de búsqueda esté vacío
document.getElementById("searchInput").addEventListener("focus", function() {
    const searchInput = document.getElementById("searchInput").value.trim();
    if (searchInput === "") {
        // Si el campo está vacío, muestra todos los productos
        mostrarTodosLosProductos();
    }
});

function realizarBusqueda() {
    const searchInput = document.getElementById("searchInput").value.trim();
    console.log("Texto de búsqueda:", searchInput); // DEPURACIÓN

    // Si el campo de búsqueda está vacío, recarga la página y muestra todos los productos
    if (!searchInput) {
        // Limpiar los resultados previos y recargar la página
        location.href = "http://localhost/PROMEDIC/ROL_REGENTE/Crud/"; // Redirige a la URL base
        return;
    }

    fetch(`buscar_producto.php?query=${encodeURIComponent(searchInput)}`)        .then(response => {
            console.log("Respuesta recibida:", response);
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            console.log("Datos recibidos:", data); // DEPURACIÓN
            if (data.length === 0) {
                alert("No se encontraron productos.");
                mostrarTodosLosProductos(); // Si no hay productos, mostramos todos
            } else {
                mostrarResultados(data);
            }
        })
        .catch(error => {
            console.error('Error al buscar productos:', error);
        });
}

// Función para mostrar los productos en la tabla
function mostrarResultados(productos) {
    const tbody = document.querySelector("tbody");
    tbody.innerHTML = ""; // Limpiar la tabla

    // Si no hay productos, mostramos un mensaje
    if (productos.length === 0) {
        tbody.innerHTML = "<tr><td colspan='11'>No se encontraron productos</td></tr>";
        return;
    }

    productos.forEach(producto => {
        const row = document.createElement("tr");

        // Usamos los valores de Marca y Estado (ya provistos por PHP)
        const nombreMarca = producto.Nombre_Marca || "No disponible";
        const nombreEstado = producto.Nombre_Estado || "No disponible";

        // Crear una fila con los datos del producto
        row.innerHTML = `
            <td>${producto.Id_Producto}</td>
            <td>${producto.Nombre_Producto}</td>
            <td>${producto.Descripcion_Producto}</td>
            <td>${producto.Precio} COP</td>
            <td>${producto.Cantidad_Stock}</td>
            <td>${producto.Peso}</td>
            <td>${producto.Fecha_Vencimiento}</td>
            <td>${producto.Codigo_Barras}</td>
            <td>${nombreMarca}</td>
            <td>${nombreEstado}</td>
            <td>
                <button class='btn btn-edit' data-id='${producto.Id_Producto}'>Editar</button>
            </td>
        `;
        
        tbody.appendChild(row);
    });
}

// Función para mostrar todos los productos si no se realiza una búsqueda
function mostrarTodosLosProductos() {
    fetch("todos_los_productos.php") // Asegúrate de tener esta ruta para obtener todos los productos
        .then(response => response.json())
        .then(data => {
            console.log("Datos de todos los productos:", data);
            mostrarResultados(data); // Muestra todos los productos
        })
        .catch(error => {
            console.error('Error al cargar todos los productos:', error);
        });
}
</script>
            

           
            <div class="dropdown">
              <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownProfileButton" data-bs-toggle="dropdown" aria-expanded="false">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
                  <path d="M8 8a3 3 0 1 0-3-3 3 3 0 0 0 3 3zm0 1a4 4 0 0 0-4 4v1h8v-1a4 4 0 0 0-4-4z" />
                </svg>
              </button>
              <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownProfileButton">
              <li><a class="dropdown-item" href="http://localhost/PROMEDIC/Iniciar_sesion/Cerra_Sesion.php">Cerrar sesión</a></li>
                <li><a class="dropdown-item" href="http://localhost/PROMEDIC/ROL_REGENTE/proveedor_bd/interfaz_proveedor.php">Gestión de Proveedor</a></li>

              </ul>
            </div>
          </div>
      </div>

  </nav>

 <nav class="navbar navbar-light" style="background-color: #ffffff;">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Categorías y Clasificaciones</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <h6 class="text-primary">Categorías</h6>
                <ul class="navbar-nav justify-content-end flex-grow-1 pe-3" id="category-list"></ul>
                <hr>
                <h6 class="text-primary">Clasificaciones</h6>
                <ul class="navbar-nav justify-content-end flex-grow-1 pe-3" id="classification-list"></ul>
            </div>
        </div>
    </div>
</nav>

<script>
    // Cargar datos desde el archivo PHP
    fetch('get_data.php')
        .then(response => response.json())
        .then(data => {
            const categoryList = document.getElementById('category-list');
            const classificationList = document.getElementById('classification-list');

            // Agregar categorías al menú
            data.categorias.forEach((categoria, index) => {
                const li = document.createElement('li');
                li.className = 'nav-item';
                li.innerHTML = `
                    <a class="nav-link" data-bs-toggle="collapse" href="#collapseCategory${index}" role="button" aria-expanded="false" aria-controls="collapseCategory${index}" onclick="filterProducts('${categoria.Nombre_Categoria}')">
                        ${categoria.Nombre_Categoria}
                    </a>
                    <div class="collapse" id="collapseCategory${index}">
                        <div class="card card-body">
                            ${categoria.Descripcion_Categoria}
                        </div>
                    </div>
                `;
                categoryList.appendChild(li);
            });

            // Agregar clasificaciones al menú
            data.clasificaciones.forEach((clasificacion, index) => {
                const li = document.createElement('li');
                li.className = 'nav-item';
                li.innerHTML = `
                    <a class="nav-link" data-bs-toggle="collapse" href="#collapseClassification${index}" role="button" aria-expanded="false" aria-controls="collapseClassification${index}" onclick="filterProducts('${clasificacion.Nombre_Clasificacion}')">
                        ${clasificacion.Nombre_Clasificacion}
                    </a>
                    <div class="collapse" id="collapseClassification${index}">
                        <div class="card card-body">
                            ${clasificacion.Descripcion_Clasificacion}
                        </div>
                    </div>
                `;
                classificationList.appendChild(li);
            });
        })
        .catch(error => console.error('Error al cargar los datos:', error));

    function filterProducts(name) {
        const tableRows = document.querySelectorAll('tbody tr');
        tableRows.forEach(row => {
            const categoryCell = row.cells[1].innerText; // Suponiendo que la categoría está en la segunda celda
            const classificationCell = row.cells[2].innerText; // Suponiendo que la clasificación está en la tercera celda
            if (categoryCell.includes(name) || classificationCell.includes(name)) {
                row.style.display = ''; // Mostrar la fila si coincide
            } else {
                row.style.display = 'none'; // Ocultar la fila si no coincide
            }
        });
    }
</script>



    <div class="container mt-5">
      <h1>Lista de Productos</h1>
      <table class="table table-bordered mt-3">
        <thead>

          <tr>
            <th>Código</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Precio</th>
            <th>Cantidad</th>
            <th>Unidad</th>
            <th>Fecha de Vencimiento</th>
            <th>Código de Barras</th>
            <th>Marca</th>
            <th>Estado</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
  <?php
  include "conexion.php";

  // Crear un array para almacenar los productos agrupados
  $productosAgrupados = [];
  $productosNoDisponibles = [];

  // Realizar la consulta para obtener los productos
  $sql = "SELECT * FROM producto"; // Asegúrate de ajustar la consulta según tu base de datos
  $result = $conn->query($sql);

  // Verificar si la consulta devolvió resultados
  if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      // Realiza las consultas necesarias para obtener los nombres
      $categoria_query = "SELECT Nombre_Categoria FROM categoria WHERE Id_Categoria = " . $row['Id_Categoria'];
      $categoria_result = $conn->query($categoria_query);
      $categoria_row = $categoria_result->fetch_assoc();
      $nombre_categoria = $categoria_row['Nombre_Categoria'];

      $marca_query = "SELECT Marca_Producto FROM marca WHERE Id_Marca = " . $row['Id_Marca'];
      $marca_result = $conn->query($marca_query);
      $marca_row = $marca_result->fetch_assoc();
      $nombre_marca = $marca_row['Marca_Producto'];

      $id_estado_producto = $row['Id_Estado_Producto'];

      if (!empty($id_estado_producto) && is_numeric($id_estado_producto)) {
        // Convertir a entero para mayor seguridad
        $id_estado_producto = intval($id_estado_producto);

        // Construir la consulta
        $estado_query = "SELECT Tipo_Estado_Producto FROM estado_producto WHERE Id_Estado_Producto = $id_estado_producto";
        
        // Ejecutar la consulta
        $estado_result = $conn->query($estado_query);

        // Verificar si la consulta se ejecutó correctamente
        if ($estado_result) {
          // Obtener el resultado
          $estado_row = $estado_result->fetch_assoc();
          
          // Verificar si se obtuvo un resultado
          if ($estado_row) {
            $nombre_estado = $estado_row['Tipo_Estado_Producto'];
          } else {
            $nombre_estado = "Estado no encontrado"; // Manejo de caso donde no se encuentra el estado
          }
        } else {
          die("Error en la consulta: " . $conn->error); // Mostrar error si la consulta falla
        }
      } else {
        $nombre_estado = "ID de estado inválido"; // Manejo de caso donde el ID no es válido
      }

      $clasificacion_query = "SELECT Nombre_Clasificacion FROM clasificacion WHERE Id_Clasificacion = " . $row['Id_Clasificacion'];
      $clasificacion_result = $conn->query($clasificacion_query);
      $clasificacion_row = $clasificacion_result->fetch_assoc();
      $clasificacion_nombre = $clasificacion_row['Nombre_Clasificacion'];

      // Agrupar productos por categoría y clasificación
      if ($nombre_estado == 'No Disponible') {
        $productosNoDisponibles[$nombre_categoria][$clasificacion_nombre][] = [
          'Id_Producto' => $row["Id_Producto"],
          'Nombre_Producto' => $row["Nombre_Producto"],
          'Descripcion_Producto' => $row["Descripcion_Producto"],
          'Precio' => $row["Precio"],
          'Cantidad_Stock' => $row["Cantidad_Stock"],
          'Peso' => $row["Peso"],
          'Fecha_Vencimiento' => $row["Fecha_Vencimiento"],
          'Codigo_Barras' => $row["Codigo_Barras"],
          'Nombre_Marca' => $nombre_marca,
          'Nombre_Estado' => $nombre_estado,
        ];
      } else {
        $productosAgrupados[$nombre_categoria][$clasificacion_nombre][] = [
          'Id_Producto' => $row["Id_Producto"],
          'Nombre_Producto' => $row["Nombre_Producto"],
          'Descripcion_Producto' => $row["Descripcion_Producto"],
          'Precio' => $row["Precio"],
          'Cantidad_Stock' => $row["Cantidad_Stock"],
          'Peso' => $row["Peso"],
          'Fecha_Vencimiento' => $row["Fecha_Vencimiento"],
          'Codigo_Barras' => $row["Codigo_Barras"],
          'Nombre_Marca' => $nombre_marca,
          'Nombre_Estado' => $nombre_estado,
        ];
      }
    }
  } else {
    echo "<tr><td colspan='14'>UPS !, No hay registros disponibles aún.</td></tr>";
  }

  // Imprimir los productos agrupados
  foreach ($productosAgrupados as $categoria => $clasificaciones) {
    echo "<tr><td colspan='11' class='table-primary'><strong>Categoria: $categoria</strong></td></tr>";

    foreach ($clasificaciones as $clasificacion => $productos) {
      echo "<tr><td colspan='11' class='table-secondary'><strong>Clasificación: $clasificacion</strong></td></tr>";
      foreach ($productos as $producto) {
        echo "<tr>
                <td>" . $producto['Id_Producto'] . "</td>
                <td>" . $producto['Nombre_Producto'] . "</td>
                <td>" . $producto['Descripcion_Producto'] . "</td>
                <td>" . $producto['Precio'] . " COP</td>
                <td>" . $producto['Cantidad_Stock'] . "</td>
                <td>" . $producto['Peso'] . "</td>
                <td>" . $producto['Fecha_Vencimiento'] . "</td>
                <td>" . $producto['Codigo_Barras'] . "</td>
                <td>" . $producto['Nombre_Marca'] . "</td>
                <td>" . $producto['Nombre_Estado'] . "</td>
                <td>
                    <button class='btn btn-edit' data-id='" . $producto["Id_Producto"] . "'>Editar</button>
                </td>
              </tr>";
      }
    }
  }

  // Imprimir los productos no disponibles
  if (count($productosNoDisponibles) > 0) {
    echo "<tr><td colspan='11' class='table-warning'><strong>Productos No Disponibles</strong></td></tr>";

    foreach ($productosNoDisponibles as $categoria => $clasificaciones) {
      echo "<tr><td colspan='11' class='table-primary'><strong>Categoria: $categoria</strong></td></tr>";

      foreach ($clasificaciones as $clasificacion => $productos) {
        echo "<tr><td colspan='11' class='table-secondary'><strong>Clasificación: $clasificacion</strong></td></tr>";
        foreach ($productos as $producto) {
          echo "<tr>
                  <td>" . $producto['Id_Producto'] . "</td>
                  <td>" . $producto['Nombre_Producto'] . "</td>
                  <td>" . $producto['Descripcion_Producto'] . "</td>
                  <td>" . $producto['Precio'] . " COP</td>
                  <td>" . $producto['Cantidad_Stock'] . "</td>
                  <td>" . $producto['Peso'] . "</td>
                  <td>" . $producto['Fecha_Vencimiento'] . "</td>
                  <td>" . $producto['Codigo_Barras'] . "</td>
                  <td>" . $producto['Nombre_Marca'] . "</td>
                  <td>" . $producto['Nombre_Estado'] . "</td>
                  <td>
                      <button class='btn btn-edit' data-id='" . $producto["Id_Producto"] . "'>Editar</button>
                  </td>
                </tr>";
        }
      }
    }
  }
  ?>
</tbody>
</table>
</div>

    <style>
    /* Estilo para posicionar el modal de salida de productos */
    .modal-floating {
        position: fixed;
        bottom: 0;
        right: 0;
        margin: 0;
        border-radius: 10px 10px 0 0;
        overflow: hidden;
    }

    .modal-header {
        background-color: #0d6efd; /* Azul */
        color: white;
    }

    .modal-dialog {
        max-width: 300px;
        margin: 0;
    }
</style>

<!-- Botón para abrir el modal -->
<button type="button" class="btn btn-primary position-fixed bottom-0 end-0 m-3" data-bs-toggle="modal" data-bs-target="#eliminarModal">
    Vender Producto
</button>

<!-- Modal -->
<div class="modal modal-floating fade" id="eliminarModal" tabindex="-1" aria-labelledby="eliminarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen-up">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eliminarModalLabel">Venta de Producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form action="Salida_Productos.php" method="POST">
                    <div class="mb-3">
                        <label for="idProducto" class="form-label">ID del Producto</label>
                        <input type="number" class="form-control" id="idProducto" name="idProducto" placeholder="Ingrese el ID del Producto" required>
                    </div>
                    <div class="mb-3">
                        <label for="cantidad" class="form-label">Cantidad</label>
                        <input type="number" class="form-control" id="cantidad" name="cantidad" placeholder="Ingrese la cantidad a Sacar" required>
                    </div>
                        <div class="mb-3">
                            <label for="regenteProducto" class="form-label">Regente</label>
                            <select class="form-select" id="regenteProducto" name="id_regente" required>
                                <option value="">Seleccione un regente</option>
                                <!-- Las opciones se cargarán dinámicamente -->
                            </select>
                        </div>
                          <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Sacar prodcuto</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const regenteSelect = document.getElementById('regenteProducto');

    // Función para cargar los datos de Regente
    function cargarRegentes() {
        fetch('Obtener_Datos.php')
            .then(response => response.json())
            .then(data => {
                // Verifica si hay regentes en los datos
                if (data.regentes) {
                    data.regentes.forEach(regente => {
                        const option = document.createElement('option');
                        option.value = regente.Id_Regente;
                        option.textContent = regente.Nombre;
                        regenteSelect.appendChild(option);
                    });
                } else {
                    const option = document.createElement('option');
                    option.value = "";
                    option.textContent = "No hay regentes disponibles";
                    regenteSelect.appendChild(option);
                }
            })
            .catch(error => console.error('Error al cargar datos:', error));
    }

    // Llamar la función al cargar la página
    cargarRegentes();
});

</script>



    <!-- Bootstrap y JavaScript -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.4.0/axios.min.js"></script>


    <script>
      
      document.getElementById("searchProduct").addEventListener("click", () => {
        const productName = document.getElementById("productName").value.trim();

        if (!productName) {
          alert("Por favor, ingrese un nombre de producto.");
          return;
        }

        // Asegurarnos de que el nombre del producto esté correctamente codificado para la URL
        const encodedProductName = encodeURIComponent(productName);

        // Generar la URL de búsqueda en DrugBank
        const searchURL = `https://go.drugbank.com/unearth/q?searcher=drugs&query=${encodedProductName}&button=`;

        // Abrir la página de búsqueda en DrugBank en una nueva pestaña
        window.open(searchURL, "_blank");

        // Cerrar el modal después de realizar la búsqueda
        const modal = bootstrap.Modal.getInstance(document.getElementById("searchModal"));
        modal.hide();
      });
    </script>
  </body>
</html>