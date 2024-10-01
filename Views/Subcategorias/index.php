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
            'RolName' => $_POST['nombre'], // El valor enviado por el formulario con el nombre del rol
            'RolDescription' => $_POST['descripcion'], // El valor enviado por el formulario con la descripción del rol
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

<html>
    <head>
        <title>Sub Categorías</title>
    </head>
    <body>

        <h1>Lista de Sub Categorías</h1>

        <!-- Enlace para crear una nueva subcategoría -->
        <a href="create.php?action=create">Crear Subcategora</a>


        <!-- Enlace para regresar al menú principal -->
        <a href="../Menu.php">Regresar al Menú</a>


        <!-- Tabla para mostrar las subcategorías -->
        <table border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Categoría ID</th>
                    <th>Estado</th>
                    <th>Fecha de Creación</th>
                    <th>Acciones</th> <!-- Nueva columna para las acciones -->
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($subcategorias)) : ?>
                    <?php foreach ($subcategorias as $subcategoria) : ?>
                        <tr>
                            <td><?php echo $subcategoria['id']; ?></td>
                            <td><?php echo $subcategoria['nombre']; ?></td>
                            <td><?php echo $subcategoria['descripcion']; ?></td>
                            <td><?php echo $subcategoria['categoria_id']; ?></td>
                            <td><?php echo $subcategoria['isActive'] ? 'Activo' : 'Inactivo'; ?></td>
                            <td><?php echo $subcategoria['fechaCreacion']; ?></td>
                            <td>
                                <!-- Enlaces para editar y eliminar -->
                                <a href="edit_subcategoria.php?id=<?php echo $subcategoria['id']; ?>">Editar</a> |
                                <a href="delete_subcategoria.php?id=<?php echo $subcategoria['id']; ?>" onclick="return confirm('¿Estás seguro de que quieres eliminar esta subcategoría?');">Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="7">No hay subcategorías registradas.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

    </body>
</html>
