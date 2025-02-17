import React, { useState, useEffect } from 'react';
import axios from 'axios';

const Notificaciones = () => {
    const [notificaciones, setNotificaciones] = useState([]);
    const [nuevoMensaje, setNuevoMensaje] = useState('');
    const [loading, setLoading] = useState(true);

    // Obtener todas las notificaciones al cargar el componente
    useEffect(() => {
        axios.get('http://localhost/promedicch/notificaciones.php')
            .then((response) => {
                setNotificaciones(response.data); // Establece las notificaciones obtenidas
                setLoading(false);
            })
            .catch((error) => {
                console.error('Error al obtener notificaciones:', error);
                setLoading(false);
            });
    }, []);

    // Agregar una nueva notificación
    const agregarNotificacion = () => {
        if (nuevoMensaje.trim()) {
            axios.post('http://localhost/promedicch/notificaciones.php', { mensaje: nuevoMensaje })
                .then((response) => {
                    const nuevaNotificacion = {
                        id: response.data.id,
                        mensaje: nuevoMensaje,
                        fecha_creacion: response.data.fecha_creacion
                    };
                    setNotificaciones((prev) => [nuevaNotificacion, ...prev]);
                    setNuevoMensaje(''); // Limpia el campo de entrada
                })
                .catch((error) => {
                    console.error('Error al agregar notificación:', error);
                });
        } else {
            alert('El mensaje no puede estar vacío.');
        }
    };

    // Eliminar una notificación por ID
    const eliminarNotificacion = (id) => {
        axios.delete('http://localhost/promedicch/notificaciones.php', { data: { id } })
            .then(() => {
                setNotificaciones((prev) => prev.filter((n) => n.id !== id)); // Elimina la notificación localmente
            })
            .catch((error) => {
                console.error('Error al eliminar notificación:', error);
            });
    };

    return (
        <div>
            <h1>Notificaciones</h1>

            {loading ? (
                <p>Cargando notificaciones...</p>
            ) : (
                <ul>
                    {notificaciones.map((notificacion) => (
                        <li key={notificacion.id}>
                            {notificacion.mensaje} - <small>{notificacion.fecha_creacion}</small>
                            <button onClick={() => eliminarNotificacion(notificacion.id)}>Eliminar</button>
                        </li>
                    ))}
                </ul>
            )}

            <div>
                <input
                    type="text"
                    value={nuevoMensaje}
                    onChange={(e) => setNuevoMensaje(e.target.value)}
                    placeholder="Nueva notificación"
                />
                <button onClick={agregarNotificacion}>Agregar</button>
            </div>
        </div>
    );
};

export default Notificaciones;
