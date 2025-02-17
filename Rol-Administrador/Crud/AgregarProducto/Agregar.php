<?php //llamar proveedores de la bd
include 'conexion.php';

$sql = "SELECT Id_Proveedor, Nombre_Proveedor FROM Proveedor";
$result = $conn->query($sql);

$proveedores = [];
if ($result->num_rows > 0) {  
    while($row = $result->fetch_assoc()) {
        $proveedores[] = $row;
    }
} else {
    $proveedores = null; // No hay proveedores disponibles
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="DISEÑOO.css"> 
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
        <div class="container-fluid">
    <a class="navbar-brand" href="#"><h2>Promedic</h2></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="http://localhost/PROMEDIC/Rol_Admin/Crud/index.php">Inicio</a>
            </li>
            <li class="nav-item"></li>
                <a class="nav-link active" aria-current="page" href="http://localhost/PROMEDIC/Rol_Admin/proveedor_bd/interfaz_proveedor.php">Proveedores</a>
            </li>
  </nav>
            
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="form-wrapper">
            <div class="form-container">
            <form id="productoForm" action="Agregar_Producto.php" method="POST">
            <fieldset>
                        <center><legend>Agregar productos</legend></center>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="nombreProducto" class="form-label">Nombre</label>
                                <input type="text" id="nombreProducto" name="nombre_producto" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label for="descripcionProducto" class="form-label">Descripción</label>
                                <input type="text" id="descripcionProducto"  name="descripcion_producto" class="form-control">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="precioProducto" class="form-label">Precio</label>
                                <input type="number" id="precioProducto"  name="precio" class="form-control" step="0.01" required>
                            </div>
                            <div class="col-md-6">
                                <label for="cantidadProducto" class="form-label">Cantidad</label>
                                <div class="input-group">
                                    <input type="number" id="cantidadProducto"   name="cantidad_stock" class="form-control" required>
                                    <select class="form-select" id="unidadCantidad">
                                        <option value="unidades">Unidades</option>
                                        <option value="cajas">Cajas</option>
                                        <option value="paquetes">Paquetes</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="cantidadMinima" class="form-label">Cantidad Mínima de Stock</label>
                                <input type="number" id="cantidadMinima"  name="cantidad_minima" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label for="cantidadMaxima" class="form-label">Cantidad Máxima de Stock</label>
                                <input type="number" id="cantidadMaxima"  name="cantidad_maxima" class="form-control" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="costoAdquisicion" class="form-label">Costo de Adquisicion</label>
                                <input type="number" id="costoAdquisicion" name="costo_adquisicion" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="peso" class="form-label">Peso</label>
                                <input type="text" id="peso" name="peso" class="form-control">
                            </div>
                            </div>
                            </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="fechaVencimiento" class="form-label">Fecha de Vencimiento</label>
                                <input type="date" id="fechaVencimiento"  name="fecha_vencimiento" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="codigoBarras" class="form-label">Código de Barras</label>
                                <input type="text" id="codigoBarras"  name="codigo_barras" class="form-control">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="marcaProducto" class="form-label">Marca</label>
                                <div class="input-group">
                                    <select class="form-select" id="marcaProducto" name="id_marca" required>
                                        <?php if ($marcas): ?>
                                            <?php foreach ($marcas as $marca): ?>
                                                <option value="<?php echo $marca['Id_Marca']; ?>"><?php echo $marca['Nombre_Marca']; ?></option>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <option value="">No hay marcas disponibles</option>
                                        <?php endif; ?>
                                    </select>
                                    <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalNuevaMarca">+</button>
                                </div>
                            </div>
                            <div class="col-md-6">

                            <!-- Selector de Estado del Producto -->
                            <label for="estadoProducto" class="form-label">Estado del Producto</label>
                            <div class="input-group">
                                <select class="form-select" id="estadoProducto" name="id_estado_producto" required>
                                    <?php if ($estadosProducto): ?>
                                        <?php foreach ($estadosProducto as $estado): ?>
                                            <option value="<?php echo $estado['Id_Estado_Producto']; ?>">
                                                <?php echo $estado['Tipo_Estado_Producto']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <option value="">No hay Estados del Producto</option>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                       <div class="col-md-3">
                            <!-- Formulario de Promoción (Oculto por defecto) -->
                            <div id="formPromocion" style="display: none;">
                            <div class="row mb-3">
                                <div class="col-12">
                                    <label for="tipoPromocion" class="form-label">Tipo de Promoción</label>
                                    <select class="form-select" id="tipoPromocion" name="id_tipo_promocion">
                                        <option value="">Cargando tipos de promoción...</option>
                                    </select>
                                </div>
                                <div class="col- 12" id="descuentoField" style="display: none;">
                                    <label for="Descuento" class="form-label">Porcentaje de Descuento</label>
                                    <input type="number" class="form-control" id="Descuento" name="Descuento" placeholder="Ej: 20%" min="1" max="100">
                                </div>
                                <div class="col-12">
                                    <label for="fecha_inicio" class="form-label">Fecha Inicio</label>
                                    <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
                                </div>
                                <div class="col-12">
                                    <label for="fecha_fin" class="form-label">Fecha Fin</label>
                                    <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" required>
                                </div>
                            </div>
                        </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="categoriaProducto" class="form-label">Categoría</label>
                                <div class="input-group">
                                    <select class="form-select" id="categoriaProducto" name="id_categoria" required>
                                        <?php if ($categorias): ?>
                                            <?php foreach ($categorias as $categoria): ?>
                                                <option value="<?php echo $categoria['Id_Categoria']; ?>"><?php echo $categoria['Nombre_Categoria']; ?></option>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <option value="">No hay categorías disponibles</option>
                                        <?php endif; ?>
                                    </select>
                                    <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalNuevaCategoria">+</button>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="clasificacionProducto" class="form-label">Clasificación</label>
                                <div class="input-group">
                                    <select class="form-select" id="clasificacionProducto" name="id_clasificacion" required>
                                        <?php if ($clasificaciones): ?>
                                            <?php foreach ($clasificaciones as $clasificacion): ?>
                                                <option value="<?php echo $clasificacion['Id_Clasificacion']; ?>"><?php echo $clasificacion['Nombre_Clasificacion']; ?></option>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <option value="">No hay clasificaciones disponibles</option>
                                        <?php endif; ?>
                                    </select>
                                    <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalNuevaClasificacion">+</button>
                                </div>
                            </div>
                        </div>
                        <!-- ... -->
                    </fieldset>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="select-proveedor" class="form-label">Seleccionar Proveedor:</label>
                            <select id="select-proveedor" class="form-select"  name="id_proveedor" required>
                                <option value="">Seleccionar proveedor</option>
                                <?php if ($proveedores): ?>
                                    <?php foreach ($proveedores as $proveedor): ?>
                                        <option value="<?php echo $proveedor['Id_Proveedor']; ?>"><?php echo $proveedor['Nombre_Proveedor']; ?></option>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <option value="">No hay proveedores disponibles</option>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
            </div>
        </div>
    </div>
                 <br>   
                        <center>
                              <button type="submit" class="btn btn-primary">Agregar</button>
        <button type="button" class="btn btn-secondary" onclick="window.history.back()">Cancelar</button>
                        </center>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
    </form>

    <!-- Modal Nueva categoría -->
    <div class="modal fade" id="modalNuevaCategoria" tabindex="-1" aria-labelledby="modalNuevaCategoriaLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalNuevaCategoriaLabel">Nueva categoría</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label for="nuevaCategoria" class="form-label">Nombre de la nueva categoría</label>
                    <input type="text" id="nuevaCategoria" class="form-control" name="nombre">
                </div> 
                <div class="modal-body">
                    <label for="nuevaCategoriaDescripcion" class="form-label">Descripción de la nueva categoría</label>
                    <input type="text" id="nuevaCategoriaDescripcion" class="form-control" name="descripcion">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="guardarCategoria">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Nueva Clasificación -->
    <div class="modal fade" id="modalNuevaClasificacion" tabindex="-1" aria-labelledby="modalNuevaClasificacionLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalNuevaClasificacionLabel">Nueva Clasificación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label for="nuevaClasificacion" class="form-label">Nombre de la nueva clasificación</label>
                    <input type="text" id="nuevaClasificacion" class="form-control">
                </div>
                <div class="modal-body">
                    <label for="nuevaClasificacionDescripcion" class="form-label">Descripción de la nueva clasificación</label>
                    <input type="text" id="nuevaClasificacionDescripcion" class="form-control">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="guardarClasificacion">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Nueva Marca -->
    <div class="modal fade" id="modalNuevaMarca" tabindex="-1" aria-labelledby="modalNuevaMarcaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalNuevaMarcaLabel">Nueva Marca</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <label for="nuevaMarca" class="form-label">Nombre de la nueva marca</label>
                <input type="text" id="nuevaMarca" class="form-control" required>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="guardarMarca">Guardar</button>
            </div>
        </div>
    </div>
</div>

</form>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>

    <script>//guardar categoria y clasificacion en la bd usando js
// Guardar categoría
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('guardarCategoria').addEventListener('click', function() {
        const nombre = document.getElementById('nuevaCategoria').value;
        const descripcion = document.getElementById('nuevaCategoriaDescripcion').value;

        if (!nombre || !descripcion) {
            alert("Por favor, complete todos los campos.");
            return;
        }

        fetch('Categoria.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `nombre=${encodeURIComponent(nombre)}&descripcion=${encodeURIComponent(descripcion)}`
        })
        .then(response => response.text())
        .then(data => {
            alert(data);
            $('#modalNuevaCategoria').modal('hide');
            document.getElementById('nuevaCategoria').value = '';
            document.getElementById('nuevaCategoriaDescripcion').value = '';
        })
        .catch(error => console.error('Error:', error));
    });

    // Guardar clasificación
    document.getElementById('guardarClasificacion').addEventListener('click', function() {
        const nombre = document.getElementById('nuevaClasificacion').value;
        const descripcion = document.getElementById('nuevaClasificacionDescripcion').value;
        const idCategoria = document.getElementById('categoriaProducto').value;

        if (!nombre || !descripcion || !idCategoria) {
            alert("Por favor, complete todos los campos.");
            return;
        }

        fetch('Clasificacion.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `nombre=${encodeURIComponent(nombre)}&descripcion=${encodeURIComponent(descripcion)}&idCategoria=${encodeURIComponent(idCategoria)}`
        })
        .then(response => response.text())
        .then(data => {
            alert(data);
            $('#modalNuevaClasificacion').modal('hide');
            document.getElementById('nuevaClasificacion').value = '';
            document.getElementById('nuevaClasificacionDescripcion').value = '';
        })
        .catch(error => console.error('Error:', error));
    });

   // Guardar marca
document.getElementById('guardarMarca').addEventListener('click', function() {
    const nombre = document.getElementById('nuevaMarca').value.trim();

    if (!nombre) {
        alert("Por favor, completa el campo de la marca.");
        return;
    }

    fetch('marca.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `Marca_Producto=${encodeURIComponent(nombre)}`
    })
    .then(response => response.text())
    .then(data => {
        alert(data); // Mostrar el mensaje de respuesta del servidor
        if (data.includes("éxito")) { // Verificar si el mensaje contiene "éxito"
            $('#modalNuevaMarca').modal('hide'); // Ocultar el modal
            document.getElementById('nuevaMarca').value = ''; // Limpiar el campo de entrada
        }
    })
    .catch(error => console.error('Error:', error));
});


    // Cargar las categorías al cargar la página
    fetch('Obtener_Datos.php')
        .then(response => response.json())
        .then(data => {
            const categoriaSelect = document.getElementById('categoriaProducto');
            data.categorias.forEach(categoria => {
                const option = document.createElement('option');
                option.value = categoria.Id_Categoria;
                option.textContent = categoria.Nombre_Categoria;
                categoriaSelect.appendChild(option);
            });
        })
        .catch(error => console.error('Error al cargar datos:', error));
});

// Obtener las clasificaciones y llenar el select
document.addEventListener('DOMContentLoaded', () => {
    fetch('Obtener_Datos.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Error al obtener las clasificaciones.');
            }
            return response.json();
        })
        .then(data => {
            const clasificacionSelect = document.getElementById('clasificacionProducto');
            
            // Limpiar el select antes de agregar opciones
            clasificacionSelect.innerHTML = '<option value="">Seleccione una clasificación</option>';

            // Verificar si hay errores
            if (data.errores && data.errores.length > 0) {
                console.error('Errores:', data.errores);
                return;
            }

            // Agregar las opciones al select
            if (data.clasificaciones) {
                data.clasificaciones.forEach(clasificacion => {
                    const option = document.createElement('option');
                    option.value = clasificacion.Id_Clasificacion;
                    option.textContent = clasificacion.Nombre_Clasificacion;
                    clasificacionSelect.appendChild(option);
                });
            } else {
                const option = document.createElement('option');
                option.value = "";
                option.textContent = "No hay clasificaciones disponibles";
                clasificacionSelect.appendChild(option);
            }
        })
        .catch(error => console.error('Error al cargar clasificaciones:', error));
});

// Obtener las marca y llenar el select

document.addEventListener('DOMContentLoaded', () => {
    fetch('Obtener_Datos.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Error al obtener las marcas.');
            }
            return response.json();
        })
        .then(data => {
            const marcaSelect = document.getElementById('marcaProducto');
            
            // Limpiar el select antes de agregar opciones
            marcaSelect.innerHTML = '<option value="">Seleccione una marca</option>';

            // Verificar si hay errores
            if (data.errores && data.errores.length > 0) {
                console.error('Errores:', data.errores);
                return;
            }

            // Agregar las opciones al select
            if (data.marcas) {
                data.marcas.forEach(marca => {
                    const option = document.createElement('option');
                    option.value = marca.Id_Marca;
                    option.textContent = marca.Marca_Producto;
                    marcaSelect.appendChild(option);
                });
            } else {
                const option = document.createElement('option');
                option.value = "";
                option.textContent = "No hay marcas disponibles";
                marcaSelect.appendChild(option);
            }
        })
        .catch(error => console.error('Error al cargar marcas:', error));
});
// Obtener los estados del producto y llenar el select

document.addEventListener('DOMContentLoaded', () => {
    fetch('Obtener_Datos.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Error al obtener los estados de producto.');
            }
            return response.json();
        })
        .then(data => {
            const estadoSelect = document.getElementById('estadoProducto');
            
            // Limpiar el select antes de agregar opciones
            estadoSelect.innerHTML = '<option value="">Seleccione un estado</option>';

            // Verificar si hay errores
            if (data.errores && data.errores.length > 0) {
                console.error('Errores:', data.errores);
                return;
            }

            // Agregar las opciones al select
            if (data.estados && data.estados.length > 0) {
                data.estados.forEach(estado => {
                    const option = document.createElement('option');
                    option.value = estado.Id_Estado_Producto;
                    option.textContent = estado.Tipo_Estado_Producto;
                    estadoSelect.appendChild(option);
                });
            } else {
                const option = document.createElement('option');
                option.value = "";
                option.textContent = "No hay estados disponibles";
                estadoSelect.appendChild(option);
            }
        })
        .catch(error => console.error('Error al cargar estados:', error));
});

//tipo de promocion 
document.addEventListener('DOMContentLoaded', function () {
    // Referencias a los elementos del DOM
    const estadoProductoSelect = document.getElementById('estadoProducto');
    const formPromocion = document.getElementById('formPromocion');
    const tipoPromocionSelect = document.getElementById('tipoPromocion');
    const descuentoField = document.getElementById('descuentoField');

    // Función para cargar los tipos de promoción
    function cargarPromociones() {
        fetch('Obtener_Datos.php')
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Error HTTP: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                // Verifica si hay errores
                if (data.errores.length > 0) {
                    console.error('Errores encontrados:', data.errores);
                    tipoPromocionSelect.innerHTML = '<option value="">Error al cargar datos</option>';
                    return;
                }

                // Limpia el <select> y agrega las promociones
                tipoPromocionSelect.innerHTML = '';
                if (data.promociones.length > 0) {
                    data.promociones.forEach(promocion => {
                        const option = document.createElement('option');
                        option.value = promocion.Id_Tipo_Promocion;
                        option.textContent = promocion.Tipo_Promocion;
                        tipoPromocionSelect.appendChild(option);
                    });
                } else {
                    tipoPromocionSelect.innerHTML = '<option value="">No hay tipos de promoción disponibles</option>';
                }
            })
            .catch(error => {
                console.error('Error al cargar datos:', error);
                tipoPromocionSelect.innerHTML = '<option value="">Error al cargar datos</option>';
            });
    }

    // Mostrar el formulario de promoción cuando el estado es "3 (Promoción)"
    estadoProductoSelect.addEventListener('change', function () {
        if (this.value === '3') { // Suponiendo que '3' es el ID de promoción
            formPromocion.style.display = 'block';
            cargarPromociones(); // Cargar los tipos de promoción
        } else {
            formPromocion.style.display = 'none';
            descuentoField.style.display = 'none'; // Ocultar el campo de descuento
        }
    });

    // Mostrar el campo de descuento si el tipo de promoción es "2 (Descuento)"
    tipoPromocionSelect.addEventListener('change', function () {
        if (this.value === '2') { // Suponiendo que '2' es el ID de descuento
            descuentoField.style.display = 'block';
        } else {
            descuentoField.style.display = 'none';
        }
    });
});



</script>
</body>
</html> 