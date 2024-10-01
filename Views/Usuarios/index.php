<?php
// Cargar la conexión usando ruta absoluta
require_once __DIR__ . '/../../Config/db.php';           // Cargar la conexión a la base de datos
require_once __DIR__ . '/../../Controllers/UsuarioController.php'; // Cargar el controlador de roles
require_once __DIR__ . '/../../Models/UsuarioModel.php';       // Cargar el modelo de roles
// Crear una instancia de la conexión
$db = new Database();
$connection = $db->getConnection();
$usuarioModel = new UsuarioModel($connection);

// Instanciar el controlador y pasarle la conexión
$controller = new UsuarioController($connection); // Asegúrate de pasar la conexión aquí
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
    $Usuarios = $controller->index(); // Llama a index() para obtener los usuarios
}
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Lista de Usuarios</title>
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
        <h1>Lista de Usuarios</h1>
        <a href="create.php">Crear Usuario</a>
        <a href="../Menu.php">Regresar al menu</a>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre Usuario</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Activo</th>
                    <th>Fecha Creación</th>
                    <th>Imagen</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (isset($Usuarios) && count($Usuarios) > 0): ?> <!-- Cambiado a $usuarios -->
                    <?php foreach ($Usuarios as $usuario): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($usuario['ID']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['NombreUsuario']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['Email']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['RolName']); ?></td> <!-- Cambiar RolID por RolName -->
                            <td><?php echo $usuario['IsActive'] ? 'Sí' : 'No'; ?></td>
                            <td><?php echo htmlspecialchars($usuario['FechaCreacion']); ?></td>
                            <td><img src="<?php echo htmlspecialchars('imagenesMilogar/Usuarios/' . $usuario['Imagen']); ?>" alt="Imagen de Usuario" width="50"></td>
                            <td>
                                <a href="edit.php?action=edit&id=<?= htmlspecialchars($usuario['ID']); ?>">Editar</a>
                                <a href="#" onclick="if (confirm('¿Estás seguro de que deseas eliminar este rol?')) {
                                            window.location.href = 'index.php?action=delete&id=<?= $usuario['ID']; ?>';
                                        }">Eliminar</a>                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8">No hay usuarios disponibles.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>


    </body>
</html>
