document.addEventListener('DOMContentLoaded', () => {
    const cargarProveedores = () => {
        return JSON.parse(localStorage.getItem('proveedores')) || [];
    };

    const guardarProveedores = (proveedores) => {
        localStorage.setItem('proveedores', JSON.stringify(proveedores));
    };

    const renderizarProveedores = (proveedores) => {
        const tbody = document.querySelector('#tabla-proveedores tbody');
        tbody.innerHTML = '';

        proveedores.forEach(proveedor => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${proveedor.id}</td>
                <td>${proveedor.nombre}</td>
                <td>${proveedor.direccion}</td>
                <td>${proveedor.correo}</td>
                <td>${proveedor.telefono}</td>
                <td>${proveedor.idAdministrador}</td>
                <td>
                    <button class="editar" data-id="${proveedor.id}">Editar</button>
                    <button class="eliminar" data-id="${proveedor.id}">Eliminar</button>
                </td>
            `;
            tbody.appendChild(tr);
        });
    };

    const procesarProveedor = (e) => {
        e.preventDefault();

        const id = document.querySelector('#id-proveedor').value;
        const nombre = document.querySelector('#nombre-proveedor').value;
        const direccion = document.querySelector('#direccion-proveedor').value;
        const correo = document.querySelector('#correo-proveedor').value;
        const telefono = document.querySelector('#telefono-proveedor').value;
        const idAdministrador = document.querySelector('#id-administrador').value;

        const proveedores = cargarProveedores();

        if (id) {
            const index = proveedores.findIndex(p => p.id === parseInt(id));
            if (index !== -1) {
                proveedores[index] = {
                    id: parseInt(id),
                    nombre,
                    direccion,
                    correo,
                    telefono,
                    idAdministrador
                };
                guardarProveedores(proveedores);
                renderizarProveedores(cargarProveedores());
                alert('Proveedor actualizado correctamente.');
            }
        } else {
            const nuevoId = proveedores.length > 0 ? Math.max(proveedores.map(p => p.id)) + 1 : 1;
            proveedores.push({
                id: nuevoId,
                nombre,
                direccion,
                correo,
                telefono,
                idAdministrador
            });
            guardarProveedores(proveedores);
            renderizarProveedores(cargarProveedores());
            alert('Proveedor agregado correctamente.');
        }

        e.target.reset();
        document.querySelector('#id-proveedor').value = '';
        document.querySelector('#form-agregar button').textContent = 'Agregar proveedor';
    };

    document.querySelector('#form-agregar').addEventListener('submit', procesarProveedor);

    // Filtrado automático
    document.querySelector('#buscar-proveedor').addEventListener('input', (e) => {
        const busqueda = e.target.value.toLowerCase();
        const proveedores = cargarProveedores();

        const resultados = proveedores.filter(proveedor =>
            proveedor.nombre.toLowerCase().includes(busqueda)
        );

        renderizarProveedores(resultados);
    });

    document.querySelector('#tabla-proveedores').addEventListener('click', (e) => {
        if (e.target.classList.contains('eliminar')) {
            const id = parseInt(e.target.getAttribute('data-id'));
            const proveedores = cargarProveedores();
            const proveedoresActualizados = proveedores.filter(p => p.id !== id);

            guardarProveedores(proveedoresActualizados);
            renderizarProveedores(cargarProveedores());
            alert('Proveedor eliminado correctamente.');
        }
    });

    document.querySelector('#tabla-proveedores').addEventListener('click', (e) => {
        if (e.target.classList.contains('editar')) {
            const id = parseInt(e.target.getAttribute('data-id'));
            const proveedores = cargarProveedores();
            const proveedor = proveedores.find(p => p.id === id);

            if (proveedor) {
                document.querySelector('#id-proveedor').value = proveedor.id;
                document.querySelector('#nombre-proveedor').value = proveedor.nombre;
                document.querySelector('#direccion-proveedor').value = proveedor.direccion;
                document.querySelector('#correo-proveedor').value = proveedor.correo;
                document.querySelector('#telefono-proveedor').value = proveedor.telefono;
                document.querySelector('#id-administrador').value = proveedor.idAdministrador;
                document.querySelector('#form-agregar button').textContent = 'Actualizar proveedor';
            }
        }
    });

    // Inicializar tabla
    renderizarProveedores(cargarProveedores());
});
