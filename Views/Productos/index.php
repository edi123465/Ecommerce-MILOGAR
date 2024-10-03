<?php
session_start(); // Iniciar la sesión para mostrar mensajes

require_once __DIR__ . '/../../Controllers/ProductoController.php'; // Cargar el controlador
require_once __DIR__ . '/../../Config/db.php'; // Conectar con la base de datos
// Crear una instancia de la conexión
$db = new Database();
$connection = $db->getConnection();

// Instanciar el controlador
$controller = new ProductoController($connection);
$productos = $controller->getAllProductos();
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Lista de Productos</title>
    </head>
    <body>

        <h1>Lista de Productos</h1>

        <!-- Mostrar mensajes de éxito/error -->
        <?php if (isset($_SESSION['message'])): ?>
            <p><?php
                echo $_SESSION['message'];
                unset($_SESSION['message']);
                ?></p>
        <?php endif; ?>

        <table border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Costo de compra</th>
                    <th>Precio 1</th>
                    <th>Precio 2</th>
                    <th>Precio 3</th>
                    <th>Precio 4</th>

                    <th>Stock</th>
                    <th>Categoría</th>
                    <th>Código de Barras</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($productos): ?>
                    <?php foreach ($productos as $producto): ?>
                        <tr>
                            <td><?php echo $producto['id']; ?></td>
                            <td><?php echo $producto['nombreProducto']; ?></td>
                            <td><?php echo $producto['descripcionProducto']; ?></td>
                            <td><?php echo number_format($producto['precio'], 2); ?></td>
                            <td><?php echo number_format($producto['precio_1'], 2); ?></td>
                            <td><?php echo number_format($producto['precio_2'], 2); ?></td>
                            <td><?php echo number_format($producto['precio_3'], 2); ?></td>
                            <td><?php echo number_format($producto['precio_4'], 2); ?></td>
                            <td><?php echo number_format($producto['stock'], 2); ?></td>
                            <td><?php echo $producto['subcategoria_id']; ?></td>
                            <td><?php echo $producto['codigo_barras']; ?></td>
                            <td>
                                <a href="edit.php?id=<?php echo $producto['id']; ?>">Editar</a>
                                <a href="delete.php?id=<?php echo $producto['id']; ?>" onclick="return confirm('¿Estás seguro de eliminar este producto?');">Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8">No hay productos.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <a href="create.php">Añadir Producto</a>

    </body>
</html>
