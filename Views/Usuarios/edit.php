<?php
require_once "../../Config/db.php";  // Cargar la conexión
require_once "../../Controllers/UsuarioController.php";  // Cargar el controlador
require_once "../../Models/UsuarioModel.php";  // Cargar el modelo
// Crear una instancia de la conexión
$db = new Database();
$connection = $db->getConnection();

// Instanciar el controlador y pasarle la conexión
$controller = new UsuarioController($connection); // Asegúrate de pasar la conexión aquí
// Verificar si hay un ID en la URL
if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];

    // Llamar al método getById para obtener los datos del usuario
    $usuario = $controller->getById($id); // Asegúrate de que el método getById devuelva el usuario

    if ($usuario) {
        // Mostrar el formulario con los datos recuperados
        ?>
        <h1>Editar Usuario</h1>
        <form action="update.php?id=<?= htmlspecialchars($usuario['ID']) ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="ID" value="<?= htmlspecialchars($usuario['ID']) ?>">

            <label for="NombreUsuario">Nombre de Usuario:</label>
            <input type="text" name="NombreUsuario" value="<?= htmlspecialchars($usuario['NombreUsuario']) ?>" required><br>

            <label for="Email">Email:</label>
            <input type="email" name="Email" value="<?= htmlspecialchars($usuario['Email']) ?>" required><br>

            <label for="Contrasenia">Contraseña:</label>
            <input type="password" name="Contrasenia"><br>
            <small>Deja vacío si no deseas cambiar la contraseña.</small><br>

            <label for="ConfirmarContrasenia">Confirmar contraseña:</label>
            <input type="password" name="ConfirmarContrasenia"><br>

            <label for="RolID">Rol:</label>
            <select name="RolID" required>
        <?php
        // Obtener roles desde la base de datos
        $query = "SELECT ID, RolName FROM Roles";
        $stmt = $connection->prepare($query);
        $stmt->execute();
        $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($roles as $rol) {
            echo '<option value="' . htmlspecialchars($rol['ID']) . '"' . ($rol['ID'] == $usuario['RolID'] ? ' selected' : '') . '>' . htmlspecialchars($rol['RolName']) . '</option>';
        }
        ?>
            </select><br>

            <label for="IsActive">Activo:</label>
            <input type="checkbox" name="IsActive" <?= $usuario['IsActive'] ? 'checked' : '' ?>><br>

            <label for="FechaCreacion">Fecha de Creación:</label>
            <input type="date" name="FechaCreacion" value="<?= htmlspecialchars($usuario['FechaCreacion']) ?>" readonly><br>

            <label for="Imagen">Imagen:</label>
            <input type="file" name="Imagen" accept="image/*"><br>
            <img src="<?= htmlspecialchars('imagenes milogar/Usuarios/' . $usuario['Imagen']) ?>" alt="Imagen de Usuario" width="50"><br>

            <input type="submit" value="Actualizar" onclick="return confirm('¿Estás seguro de que deseas actualizar este usuario?');">
        </form>
        <a href="index.php"><input type="button" value="Regresar a la consulta"></a>
        <?php
    } else {
        echo "Usuario no encontrado."; // Mensaje si el usuario no existe
    }
} else {
    echo "ID no proporcionado."; // Mensaje si no hay ID
}
?>
