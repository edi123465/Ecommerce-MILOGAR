<?php
require_once "../../Config/db.php";  // Cargar la conexión
require_once "../../Controllers/CategoriaController.php";  // Cargar el controlador
require_once "../../Models/CategoriaModel.php";  // Cargar el modelo
// Crear una instancia de la conexión
$db = new Database();
$connection = $db->getConnection();

// Instanciar el controlador y pasarle la conexión
$controller = new CategoriaController($connection); // Asegúrate de pasar la conexión aquí
// Verificar si hay un ID en la URL
if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];

    // Llamar al método edit para obtener los datos del rol
    $categoria = $controller->getById($id); // Asegúrate de que el método edit devuelva el rol

    if ($categoria) {
        // Mostrar el formulario con los datos recuperados
        ?>
        <h1>Editar Categoría</h1>
        <form action="update.php?id=<?= $categoria['ID'] ?>" method="POST">
            <label for="nombreCategoria">Nombre :</label>
            <input type="text" name="nombreCategoria" value="<?= htmlspecialchars($categoria['nombreCategoria']) ?>" required><br>

            <label for="categoriaDescription">Descripción:</label>
            <textarea name="categoriaDescription" required><?= htmlspecialchars($categoria['descripcionCategoria']) ?></textarea><br>

            <label for="isActive">Activo:</label>
            <input type="checkbox" name="isActive" <?= $categoria['IsActive'] ? 'checked' : '' ?>><br>

            <input type="submit" value="Actualizar" onclick="return confirm('¿Estás seguro de que deseas actualizar esta categoría?');">
        </form>
        <a href="index.php"><input type="submit" value="Regresar a la consulta"></a>
        <?php
    } else {
        echo "Rol no encontrado."; // Mensaje si el rol no existe
    }
} else {
    echo "ID no proporcionado."; // Mensaje si no hay ID
}

// Redirigir al índice si se presiona el botón de regresar
if (isset($_REQUEST["btn_regresar"])) {
    header("Location: index.php"); // Asegúrate de que esto esté entre comillas
    exit; // Siempre es una buena práctica seguir con exit después de un redireccionamiento
}
?>
