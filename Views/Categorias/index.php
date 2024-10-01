<?php
// Cargar la conexión usando ruta absoluta
require_once __DIR__ . '/../../Config/db.php';           // Cargar la conexión a la base de datos
require_once __DIR__ . '/../../Controllers/CategoriaController.php'; // Cargar el controlador de roles
require_once __DIR__ . '/../../Models/CategoriaModel.php';       // Cargar el modelo de roles
//
session_start();

// Mostrar mensaje si existe
if (isset($_SESSION['message'])) {
    echo "<script>alert('" . $_SESSION['message'] . "');</script>";
    unset($_SESSION['message']); // Eliminar el mensaje después de mostrarlo
}


// Crear una instancia de la conexión
$db = new Database();
$connection = $db->getConnection();
$categoria = new CategoriaModel($connection);

// Instanciar el controlador y pasarle la conexión
$controller = new CategoriaController($connection); // Asegúrate de pasar la conexión aquí
// Verificar si existe una acción en la URL
if (isset($_GET['action'])) {
    $action = $_REQUEST['action'];

    // Verificar si la acción es 'edit', 'update', o 'delete' y si el ID está presente
    if ($action == 'edit' && isset($_GET['id'])) {
        $controller->edit((int) $_GET['id']); // Pasar el ID como entero al controlador
    } elseif ($action == 'delete' && isset($_GET['id'])) {
        $controller->delete((int) $_GET['id']); // Pasar el ID como entero al método delete
    } elseif ($action == 'update' && isset($_GET['id'])) {
        // Aquí puedes agregar la lógica para el update si lo necesitas
    } elseif ($action === 'store') {
        // Asegúrate de que los datos se pasen correctamente
        $data = [
            'NombreUsuario' => isset($_POST['NombreUsuario']) ? $_POST['NombreUsuario'] : null,
            'Email' => isset($_POST['Email']) ? $_POST['Email'] : null,
            'RolID' => isset($_POST['RolID']) ? $_POST['RolID'] : null, // Agregar el RolID aquí
            'Contrasenia' => isset($_POST['Contrasenia']) ? $_POST['Contrasenia'] : null,
            'IsActive' => isset($_POST['IsActive']) ? 1 : 0, // Si está marcado el checkbox, valor 1, si no, 0
            'FechaCreacion' => date('Y-m-d'), // La fecha actual
        ];

        // Lógica para crear un nuevo usuario
        if ($controller->store($data)) { // Cambia a store
            header("Location: index.php"); // Redirigir a la lista de usuarios después de la creación exitosa
            exit;
        } else {
            echo "Error al crear el usuario.";
        }
    } else {
        echo "Acción no válida.";
    }
} else {
    // Acción por defecto, por ejemplo, mostrar la lista de usuarios
    $categoria = $controller->getAll(); // Llama a index() para obtener los usuarios
}
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Categorías</title>
        <style>
            /* Estilos básicos para la tabla */
            table {
                width: 100%;
                border-collapse: collapse; /* Unir bordes de las celdas */
            }
            th, td {
                border: 1px solid #ddd; /* Bordes de las celdas */
                padding: 8px; /* Espaciado interno */
                text-align: left; /* Alinear texto a la izquierda */
            }
            th {
                background-color: #f2f2f2; /* Color de fondo para el encabezado */
            }
            tr:hover {
                background-color: #f5f5f5; /* Color de fondo al pasar el mouse */
            }
            .action-btn {
                margin-right: 5px; /* Espaciado entre botones */
                padding: 5px 10px; /* Espaciado interno */
                border: none; /* Sin borde */
                border-radius: 3px; /* Bordes redondeados */
                cursor: pointer; /* Cambiar cursor al pasar por encima */
            }
            .edit-btn {
                background-color: #ffc107; /* Color de fondo para editar */
                color: white; /* Color del texto */
            }
            .delete-btn {
                background-color: #dc3545; /* Color de fondo para eliminar */
                color: white; /* Color del texto */
            }
        </style>
    </head>
    <body>

        <h1>Categorías</h1>
        <a href="create.php?action=create">Crear Categoría</a>
        <a href="#" onclick="window.open('../menu.php', '_self')">Regresar al menú</a>

        <table border="1">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Activo</th>
                <th>Fecha de creación</th>
                <th>Acciones</th>
            </tr>

            <?php if ($categoria): ?>
                <?php foreach ($categoria as $cat): ?>
                    <tr>
                        <td><?= $cat['id'] ?></td>
                        <td><?= $cat['nombre'] ?></td>
                        <td><?= $cat['descripcion'] ?></td>
                        <td><?= $cat['IsActive'] ? 'Sí' : 'No' ?></td>
                        <td><?= $cat['fechaCreacion'] ?></td>
                        <td>
                            <!-- El enlace de editar pasa correctamente el ID -->
                            <a href="edit.php?action=edit&id=<?= $cat['id']; ?>">Editar</a>
                            <!-- El enlace de eliminar pasa correctamente el ID -->
                            <a href="#" onclick="if (confirm('¿Estás seguro de que deseas eliminar este rol?')) {
                                        window.location.href = 'index.php?action=delete&id=<?= $cat['id']; ?>';
                                    }">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">No hay roles disponibles</td>
                </tr>
            <?php endif; ?>
        </table>


    </body>
</html>
