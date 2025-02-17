let regentes = JSON.parse(localStorage.getItem('regentes')) || [];
let nextId = regentes.length ? Math.max(...regentes.map(r => r.id)) + 1 : 1;

function renderTable() {
    const tbody = document.querySelector('#tabla-regentes tbody');
    tbody.innerHTML = '';
    regentes.forEach(regente => {
        tbody.innerHTML += `
            <tr>
                <td>${regente.id}</td>
                <td>${regente.nombre}</td>
                <td>${regente.apellido}</td>
                <td>${regente.dni}</td>
                <td>${regente.fechaContratacion}</td>
                <td>${regente.licencia}</td>
                <td>${regente.correo}</td>
                <td>${regente.telefono}</td>
                <td>${regente.idTurno}</td>
                <td>
                    <button onclick="editarRegente(${regente.id})" class="button-link">Editar</button>
                    <button onclick="eliminarRegente(${regente.id})" class="button">Eliminar</button>
                </td>
            </tr>
        `;
    });
}

function guardarRegente() {
    const id = parseInt(document.getElementById('id-regente').value) || nextId++;
    const nombre = document.getElementById('nombre-regente').value;
    const apellido = document.getElementById('apellido-regente').value;
    const dni = document.getElementById('dni-regente').value;
    const fechaContratacion = document.getElementById('fecha_contratacion-regente').value;
    const licencia = document.getElementById('licencia-regente').value;
    const correo = document.getElementById('correo-regente').value;
    const telefono = document.getElementById('telefono-regente').value;
    const idTurno = document.getElementById('id-turno-regente').value;

    const regenteIndex = regentes.findIndex(r => r.id === id);
    if (regenteIndex >= 0) {
        // Editar regente existente
        regentes[regenteIndex] = {
            id,
            nombre,
            apellido,
            dni,
            fechaContratacion,
            licencia,
            correo,
            telefono,
            idTurno
        };
    } else {
        // Agregar nuevo regente
        regentes.push({
            id,
            nombre,
            apellido,
            dni,
            fechaContratacion,
            licencia,
            correo,
            telefono,
            idTurno
        });
    }

    localStorage.setItem('regentes', JSON.stringify(regentes));
    renderTable();
    resetForm();
}

function buscarRegente() {
    const buscar = document.getElementById('buscar-regente').value.toLowerCase();
    const resultado = regentes.filter(regente => regente.nombre.toLowerCase().includes(buscar));
    const tbody = document.querySelector('#tabla-regentes tbody');
    tbody.innerHTML = '';
    resultado.forEach(regente => {
        tbody.innerHTML += `
            <tr>
                <td>${regente.id}</td>
                <td>${regente.nombre}</td>
                <td>${regente.apellido}</td>
                <td>${regente.dni}</td>
                <td>${regente.fechaContratacion}</td>
                <td>${regente.licencia}</td>
                <td>${regente.correo}</td>
                <td>${regente.telefono}</td>
                <td>${regente.idTurno}</td>
                <td>
                    <button onclick="editarRegente(${regente.id})" class="button-link">Editar</button>
                    <button onclick="eliminarRegente(${regente.id})" class="button">Eliminar</button>
                </td>
            </tr>
        `;
    });
}

function eliminarRegente(id) {
    regentes = regentes.filter(regente => regente.id !== id);
    localStorage.setItem('regentes', JSON.stringify(regentes));
    renderTable();
}

function editarRegente(id) {
    const regente = regentes.find(r => r.id === id);
    document.getElementById('id-regente').value = regente.id;
    document.getElementById('nombre-regente').value = regente.nombre;
    document.getElementById('apellido-regente').value = regente.apellido;
    document.getElementById('dni-regente').value = regente.dni;
    document.getElementById('fecha_contratacion-regente').value = regente.fechaContratacion;
    document.getElementById('licencia-regente').value = regente.licencia;
    document.getElementById('correo-regente').value = regente.correo;
    document.getElementById('telefono-regente').value = regente.telefono;
    document.getElementById('id-turno-regente').value = regente.idTurno;
}

function resetForm() {
    document.getElementById('form-regente').reset();
    document.getElementById('id-regente').value = '';
}

// Inicializar tabla al cargar la página
document.addEventListener('DOMContentLoaded', renderTable);