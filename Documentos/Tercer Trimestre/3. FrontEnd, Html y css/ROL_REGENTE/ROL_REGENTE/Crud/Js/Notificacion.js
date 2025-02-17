// Función para verificar el stock y generar notificaciones
function checkStock() {
    const rows = document.querySelectorAll("tbody tr");
    const notificationContainer = document.getElementById("notification-container");
    const archivedNotificationsList = document.getElementById("archived-notifications-list");
  
    rows.forEach((row) => {
        const stockCell = row.querySelectorAll("td")[7]; // Columna de stock
        const product = row.querySelectorAll("td")[3].innerText; // Nombre del producto
        const stock = parseInt(stockCell.innerText);
  
        if (stock === 0 || stock < 20) {
            const notification = document.createElement("div");
            notification.classList.add("alert", stock === 0 ? "alert-danger" : "alert-warning");
            notification.innerHTML = `
              <strong>${product}</strong> tiene un stock ${stock === 0 ? "agotado" : "bajo"} (${stock} unidades).
              <button class="btn btn-sm btn-secondary archive-btn">Guardar</button>
              <button class="btn btn-sm btn-danger dismiss-btn">Descartar</button>
              <button class="btn btn-sm btn-warning remind-btn">Recordarme más tarde</button>
            `;
            notificationContainer.appendChild(notification);

            // Funcionalidad para los botones de la notificación
            notification.querySelector(".dismiss-btn").addEventListener("click", () => {
                notification.remove();
            });

            notification.querySelector(".archive-btn").addEventListener("click", () => {
                // Guardar en notificaciones archivadas
                const archivedNotification = document.createElement("li");
                archivedNotification.textContent = `${product} tiene un stock ${stock === 0 ? "agotado" : "bajo"} (${stock} unidades).`;
                archivedNotificationsList.appendChild(archivedNotification);
                notification.remove();
            });

            notification.querySelector(".remind-btn").addEventListener("click", () => {
                // Opcional: lógica para "recordar más tarde"
                setTimeout(() => {
                    notificationContainer.appendChild(notification); // Volver a mostrar la notificación después de un tiempo
                }, 60000); // Por ejemplo, 1 minuto después
                notification.remove();
            });
        }
    });
}

// Verificar el stock cada vez que se carga la página
document.addEventListener("DOMContentLoaded", () => {
    checkStock();
});
