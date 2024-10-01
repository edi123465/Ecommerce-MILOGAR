document.addEventListener('DOMContentLoaded', function () {
    const now = new Date();

    // Obtener la fecha en formato YYYY-MM-DD
    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, '0'); // Meses son de 0 a 11
    const day = String(now.getDate()).padStart(2, '0');

    // Formatear la fecha en el formato correcto para el input date
    const formattedDate = `${year}-${month}-${day}`;

    // Establecer el valor en el campo CreatedAt (si es un campo tipo date)
    document.getElementById('CreatedAt').value = formattedDate;
});



// Llama a la función cuando se carga la página
window.onload = setCurrentDateTime;


function confirmDelete(id) {
    if (confirm('¿Estás seguro de que deseas eliminar este rol?')) {
        window.location.href = 'index.php?action=delete&id=' + id;
    }
}


