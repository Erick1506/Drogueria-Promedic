// Fecha y hora en vivo
function updateTime() {
    const now = new Date();
    const options = { 
        weekday: 'long', year: 'numeric', month: 'long', day: 'numeric',
        hour: '2-digit', minute: '2-digit', second: '2-digit'
    };
    document.getElementById('liveTime').textContent = now.toLocaleDateString('es-ES', options);
}

// Actualizar cada segundo
setInterval(updateTime, 1000);
updateTime(); // Llamada inicial para mostrar de inmediato
