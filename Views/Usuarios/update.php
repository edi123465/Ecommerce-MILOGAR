<?php
require_once "../../Config/db.php";  // Cargar la conexión
require_once "../../Controllers/UsuarioController.php";  // Cargar el controlador
require_once "../../Models/UsuarioModel.php";  // Cargar el modelo

// Crear una instancia de la conexión
$db = new Database();
$connection = $db->getConnection();

// Instanciar el controlador y pasarle la conexión
$controller = new UsuarioController($connection);

// Verificar si se ha enviado el formulario con el método POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['id'])) {
    $id = (int) $_GET['id']; // Obtener el ID de la URL

    // Recoger los datos enviados desde el formulario
    $data = [
        'NombreUsuario' => $_POST['NombreUsuario'],
        'Email' => $_POST['Email'],
        'Contrasenia' => $_POST['Contrasenia'], // Este campo se usará si se desea cambiar la contraseña
        'ConfirmarContrasenia' => $_POST['ConfirmarContrasenia'], // Este campo se usará para la validación
        'RolID' => $_POST['RolID'],
        'IsActive' => isset($_POST['IsActive']) ? 1 : 0, // Verificar si el checkbox está marcado
        'FechaCreacion' => $_POST['FechaCreacion'], // Esto debe ser readonly, pero se mantiene para la lógica
        'Imagen' => $_FILES['Imagen']['name'] // Para manejar la imagen
    ];

    // Verifica si se ha ingresado una nueva contraseña
    if (!empty($data['Contrasenia']) && $data['Contrasenia'] !== $data['ConfirmarContrasenia']) {
        echo "Las contraseñas no coinciden.";
        exit; // Detener la ejecución si hay un error
    }

    // Si no se proporciona una nueva contraseña, se elimina del array de datos
    if (empty($data['Contrasenia'])) {
        unset($data['Contrasenia']);
    }

    // Llamar al método update del controlador
    if ($controller->update($id, $data)) {
        // Redirigir al índice o mostrar un mensaje de éxito
        header('Location: index.php?success=1');
        exit; // Detener la ejecución después de la redirección
    } else {
        echo "Error al actualizar el usuario.";
    }
} else {
    echo "Solicitud no válida.";
}
?>

