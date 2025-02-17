// Función para mostrar categorías en el menú
document.getElementById('showCategories').addEventListener('click', function() {
    fetch('http://localhost:8000/categorias') // Asegúrate de cambiar esta URL a tu ruta API real
        .then(response => response.json())
        .then(data => {
            let categoriesHtml = '<ul class="list-group">';
            data.forEach(category => {
                categoriesHtml += `<li class="list-group-item">${category.nombre}</li>`;
            });
            categoriesHtml += '</ul>';
            document.querySelector('.offcanvas-body').innerHTML = categoriesHtml;
            document.getElementById('showClassifications').style.display = 'block';
            document.getElementById('showProducts').style.display = 'none';
        });
});

// Función para mostrar clasificaciones en el menú
document.getElementById('showClassifications').addEventListener('click', function() {
    fetch('http://localhost:8000/clasificaciones') // Asegúrate de cambiar esta URL a tu ruta API real
        .then(response => response.json())
        .then(data => {
            let classificationsHtml = '<ul class="list-group">';
            data.forEach(classification => {
                classificationsHtml += `<li class="list-group-item">${classification.nombre}</li>`;
            });
            classificationsHtml += '</ul>';
            document.querySelector('.offcanvas-body').innerHTML = classificationsHtml;
            document.getElementById('showProducts').style.display = 'block';
        });
});

// Función para mostrar productos en el menú
document.getElementById('showProducts').addEventListener('click', function() {
    fetch('http://localhost:8000/productos') // Asegúrate de cambiar esta URL a tu ruta API real
        .then(response => response.json())
        .then(data => {
            let productsHtml = '<ul class="list-group">';
            data.forEach(product => {
                productsHtml += `<li class="list-group-item">${product.nombre} - ${product.precio}</li>`;
            });
            productsHtml += '</ul>';
            document.querySelector('.offcanvas-body').innerHTML = productsHtml;
        });
});
