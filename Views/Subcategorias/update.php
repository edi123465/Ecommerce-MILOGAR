<?php
require_once "../../Config/db.php";  // Cargar la conexión
require_once "../../Controllers/SubcategoriaController.php";  // Cargar el controlador
require_once "../../Models/SubcategoriaModel.php";  // Cargar el modelo

// Crear una instancia de la conexión
$db = new Database();
$connection = $db->getConnection();

// Instanciar el controlador y pasarle la conexión
$controller = new SubcategoriaController($connection);

// Verificar si se ha enviado el formulario con el método POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['id'])) {
    $id = (int) $_GET['id']; // Obtener el ID de la URL

    // Recoger los datos enviados desde el formulario
    $data = [
        'nombrSubcategoria' => $_POST['nombrSubcategoria'],
        'descripcionSubcategoria' => $_POST['descripcionSubcategoria'],
        'categoria_id' => $_POST['categoria_id'],
        'isActive' => isset($_POST['isActive']) ? 1 : 0 // Verificar si el checkbox está marcado
    ];

    // Llamar al método update del controlador con el ID y los datos
    if ($controller->update($id, $data)) {
        // Redirigir al índice o mostrar un mensaje de éxito
        header('Location: index.php?success=1');
        exit; // Detener la ejecución después de la redirección
    } else {
        echo "Error al actualizar la subcategoría.";
    }
} else {
    echo "Solicitud no válida.";
}
?>
