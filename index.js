const express = require('express');
const app = express();

app.use(express.json());

// Simulación de datos de notificaciones
const notificaciones = [
    { id: 1, mensaje: "El producto 'Paracetamol' ha vencido." },
    { id: 2, mensaje: "El producto 'Ibuprofeno' está por debajo de la cantidad mínima." },
    { id: 3, mensaje: "El producto 'Aspirina' ha superado la cantidad máxima." },
];

// Ruta principal
app.get('/', (req, res) => {
    res.send('API de Notificaciones');
});

// Obtener todas las notificaciones (GET)
app.get('/api/notificaciones', (req, res) => {
    res.send(notificaciones);
});

// Obtener una notificación específica por ID (GET)
app.get('/api/notificaciones/:id', (req, res) => {
    const notificacion = notificaciones.find(n => n.id === parseInt(req.params.id));
    if (!notificacion) return res.status(404).send('Notificación no encontrada');
    res.send(notificacion);
});

// Crear una nueva notificación (POST)
app.post('/api/notificaciones', (req, res) => {
    const notificacion = {
        id: notificaciones.length + 1,
        mensaje: req.body.mensaje
    };

    notificaciones.push(notificacion);
    res.send(notificacion);
});

// Eliminar una notificación por ID (DELETE)
app.delete('/api/notificaciones/:id', (req, res) => {
    const notificacion = notificaciones.find(n => n.id === parseInt(req.params.id));
    if (!notificacion) return res.status(404).send('Notificación no encontrada');

    const index = notificaciones.indexOf(notificacion);
    notificaciones.splice(index, 1);

    res.send(notificacion);
});

// Configuración del puerto
const port = process.env.PORT || 90;
app.listen(port, () => console.log(`Servidor escuchando en el puerto ${port}...`));