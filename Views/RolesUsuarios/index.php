<?php

// Cargar la conexión usando ruta absoluta
require_once __DIR__ . '/../../Config/db.php';           // Cargar la conexión a la base de datos
require_once __DIR__ . '/../../Controllers/RolController.php'; // Cargar el controlador de roles
require_once __DIR__ . '/../../Models/RolModel.php';       // Cargar el modelo de roles
// Crear una instancia de la conexión
$db = new Database();
$connection = $db->getConnection();

// Instanciar el controlador y pasarle la conexión
$controller = new RolController($connection); // Asegúrate de pasar la conexión aquí
// Llamar al método index() para obtener los roles
$roles = $controller->getAll();  // Esta variable contiene los roles o null si no hay datos
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
    } elseif ($action == 'store') {
        $data = [
            'RolName' => $_POST['RolName'], // El valor enviado por el formulario con el nombre del rol
            'RolDescription' => $_POST['RolDescription'], // El valor enviado por el formulario con la descripción del rol
            'IsActive' => isset($_POST['IsActive']) ? 1 : 0, // Si está marcado el checkbox, valor 1, si no, 0
            'CreatedAt' => $_POST['CreatedAt']        // La fecha seleccionada en el formulario
        ];
        // Lógica para crear un nuevo rol
        $controller->store($data); // Asegúrate de que el método create esté definido en tu controlador
    } else {
        echo "Acción no válida.";
    }
} else {
    // Acción por defecto, por ejemplo, mostrar la lista de roles
    $roles = $controller->getAll(); // Llama a index() para obtener los roles
}
?>

<h1>Lista de Roles</h1>
<a href="#" onclick="window.open('create.php?action=create', '_blank')">Crear nuevo rol</a>
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

<?php if ($roles): ?>
    <?php foreach ($roles as $rol): ?>
            <tr>
                <td><?= $rol['ID'] ?></td>
                <td><?= $rol['RolName'] ?></td>
                <td><?= $rol['RolDescription'] ?></td>
                <td><?= $rol['IsActive'] ? 'Sí' : 'No' ?></td>
                <td><?= $rol['CreatedAt'] ?></td>
                <td>
                    <!-- El enlace de editar pasa correctamente el ID -->
                    <a href="edit.php?action=edit&id=<?= $rol['ID']; ?>">Editar</a>
                    <!-- El enlace de eliminar pasa correctamente el ID -->
                    <a href="#" onclick="if (confirm('¿Estás seguro de que deseas eliminar este rol?')) {
                                window.location.href = 'index.php?action=delete&id=<?= $rol['ID']; ?>';
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
