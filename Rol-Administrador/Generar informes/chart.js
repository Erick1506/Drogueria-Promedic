document.querySelector('form').addEventListener('submit', function(e) {
    const startDate = document.getElementById('start-date').value;
    const endDate = document.getElementById('end-date').value;

    if (new Date(startDate) > new Date(endDate)) {
        e.preventDefault();
        alert('La fecha de inicio no puede ser mayor que la fecha de fin.');
    }
});