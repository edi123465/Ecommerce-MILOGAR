<?php
session_start(); // Iniciar sesión para acceder al mensaje
// Cargar la conexión usando ruta absoluta
require_once __DIR__ . '/../../Config/db.php';           // Cargar la conexión a la base de datos
require_once __DIR__ . '/../../Controllers/SubcategoriaController.php'; // Cargar el controlador de subcategorías
require_once __DIR__ . '/../../Models/SubcategoriaModel.php';       // Cargar el modelo de subcategorías
// Crear una instancia de la conexión
$db = new Database();
$connection = $db->getConnection();

// Instanciar el controlador y pasarle la conexión
$controller = new SubcategoriaController($connection); // Asegúrate de pasar la conexión aquí
// Verificar si existe una acción en la URL
if (isset($_GET['action'])) {
    $action = $_REQUEST['action'];

    // Verificar si la acción es 'edit', 'update', o 'delete' y si el ID está presente
    if ($action == 'edit' && isset($_GET['id'])) {
        // Recoger los datos enviados por el formulario
        $data = [
            'id' => $_POST['id'],
            'nombrSubcategoria' => $_POST['nombrSubcategoria'],
            'descripcionSubcategoria' => $_POST['descripcionSubcategoria'],
            'categoria_id' => $_POST['categoria_id'],
            'isActive' => isset($_POST['isActive']) ? 1 : 0
        ];

        // Lógica para actualizar la subcategoría
        $controller->update($data);
    } elseif ($action == 'delete' && isset($_GET['id'])) {
        $controller->delete((int) $_GET['id']); // Pasar el ID como entero al método delete
    } elseif ($action == 'store') {
        // Recoger los datos enviados por el formulario
        $data = [
            'nombreSubcategoria' => $_POST['nombreSubcategoria'],
            'descripcionSubcategoria' => $_POST['descripcionSubcategoria'],
            'categoria_id' => $_POST['categoria_id'],
            'isActive' => isset($_POST['isActive']) ? 1 : 0,
            'fechaCreacion' => date('Y-m-d H:i:s')
        ];

        // Lógica para crear una nueva subcategoría
        $controller->store($data); // Asegúrate de que el método store esté definido en tu controlador
    } else {
        echo "Acción no válida.";
    }
} else {
    // Acción por defecto, por ejemplo, mostrar la lista de subcategorías
    $subcategorias = $controller->getAll(); // Llama al método para obtener todas las subcategorías
}

// Verificar si hay un mensaje de sesión
if (isset($_SESSION['message'])) {
    echo "<script>alert('" . $_SESSION['message'] . "');</script>"; // Mostrar alerta con el mensaje
    unset($_SESSION['message']); // Limpiar el mensaje después de mostrarlo
}
?>



<html>
    <head>
        <title>Sub Categorías</title>
    </head>
    <body>

        <h1>Lista de Sub Categorías</h1>

        <!-- Enlace para crear una nueva subcategoría -->
        <a href="create.php?action=create">Crear Subcategoria</a>


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
                    <?php foreach ($subcategorias as $subcategori) : ?>
                        <tr>
                            <td><?php echo $subcategori['id']; ?></td>
                            <td><?php echo $subcategori['nombrSubcategoria']; ?></td>
                            <td><?php echo $subcategori['descripcionSubcategoria']; ?></td>
                            <td><?php echo $subcategori['categoria_id']; ?></td>
                            <td><?php echo $subcategori['isActive'] ? 'Activo' : 'Inactivo'; ?></td>
                            <td><?php echo $subcategori['fechaCreacion']; ?></td>
                            <td>
                                <!-- Enlaces para editar y eliminar -->
                                <a href="edit.php?action=edit&id=<?= htmlspecialchars($subcategori['id']); ?>">Editar</a>
                                <a href="#" onclick="if (confirm('¿Estás seguro de que deseas eliminar este rol?')) {
                                            window.location.href = 'index.php?action=delete&id=<?= $subcategori['id']; ?>';
                                        }">Eliminar</a>   
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
