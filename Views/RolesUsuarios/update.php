<?php
require_once "../../Config/db.php";  // Cargar la conexión
require_once "../../Controllers/RolController.php";  // Cargar el controlador
require_once "../../Models/RolModel.php";  // Cargar el modelo

// Crear una instancia de la conexión
$db = new Database();
$connection = $db->getConnection();

// Instanciar el controlador y pasarle la conexión
$controller = new RolController($connection);

// Verificar si se ha enviado el formulario con el método POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'nombre' => $_POST['nombreCategoria'], // Clave correcta para el nombre
        'descripcion' => $_POST['categoriaDescription'], // Clave correcta para la descripción
        'isActive' => isset($_POST['isActive']) ? 1 : 0 // Clave correcta para isActive
    ];

    // Llama al método de actualización con los datos
    $id = $_GET['id']; // Suponiendo que estás pasando el ID a través de la URL
    $updateResult = $controller->update($id, $data);
    
    // Maneja el resultado de la actualización
    if ($updateResult) {
        echo "Categoría actualizada correctamente.";
    } else {
        echo "Hubo un problema al actualizar la categoría.";
    }
}

?>
