<?php
require_once "../../Config/db.php";  // Cargar la conexión
require_once "../../Controllers/RolController.php";  // Cargar el controlador
require_once "../../Models/RolModel.php";  // Cargar el modelo

// Crear una instancia de la conexión
$db = new Database();
$connection = $db->getConnection();

// Instanciar el controlador y pasarle la conexión
$controller = new RolController($connection); // Asegúrate de pasar la conexión aquí

// Verificar si hay un ID en la URL
if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];

    // Llamar al método edit para obtener los datos del rol
    $rol = $controller->edit($id); // Asegúrate de que el método edit devuelva el rol

    if ($rol) {
        // Mostrar el formulario con los datos recuperados
        ?>
        <h1>Editar Rol</h1>
        <form action="update.php?id=<?= $rol['ID'] ?>" method="POST">
            <label for="rolName">Nombre del rol:</label>
            <input type="text" name="RolName" value="<?= htmlspecialchars($rol['RolName']) ?>" required><br>

            <label for="rolDescription">Descripción:</label>
            <textarea name="RolDescription" required><?= htmlspecialchars($rol['RolDescription']) ?></textarea><br>

            <label for="isActive">Activo:</label>
            <input type="checkbox" name="IsActive" <?= $rol['IsActive'] ? 'checked' : '' ?>><br>

            <input type="submit" value="Actualizar" onclick="return confirm('¿Estás seguro de que deseas actualizar este rol?');">
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
