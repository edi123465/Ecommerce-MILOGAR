<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Crear Usuario</title>

    </head>
    <body>
        <h1>Crear Usuario</h1>
        <form action="index.php?action=store" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="NombreUsuario">Nombre de Usuario:</label>
                <input type="text" id="NombreUsuario" name="NombreUsuario" >
            </div>
            <div class="form-group">
                <label for="Email">Email:</label>
                <input type="email" id="Email" name="Email" >
            </div>
            <div class="form-group">
                <label for="Contrasenia">Contraseña:</label>
                <input type="password" id="Contrasenia" name="Contrasenia" >
            </div>
            <div class="form-group">
                <label for="Contrasenia">Confirmar contraseña:</label>
                <input type="password" id="Contrasenia" name="ConfirmarContrasenia" >
            </div>
            <div class="form-group">
                <label for="RolID">Rol:</label>
                <select id="RolID" name="RolID" >
                    <!-- Aquí se llenarán los roles desde la base de datos -->
                    <?php
                    // Conectar a la base de datos y obtener roles
                    require_once __DIR__ . '/../../Config/db.php';
                    $db = new Database();
                    $connection = $db->getConnection();

                    $query = "SELECT ID, RolName FROM Roles"; // Ajusta la consulta según tu esquema
                    $stmt = $connection->prepare($query);
                    $stmt->execute();
                    $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($roles as $rol) {
                        echo '<option value="' . htmlspecialchars($rol['ID']) . '">' . htmlspecialchars($rol['RolName']) . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="IsActive">Activo:</label>
                <input type="checkbox" id="IsActive" name="IsActive" value="1"> 
            </div>
            <div class="form-group">
                <label for="FechaCreacion">Fecha de Creación:</label>
                <input type="date" id="FechaCreacion" name="FechaCreacion" value="<?php echo date('Y-m-d'); ?>" readonly>
            </div>
            <div class="form-group">
                <label for="Imagen">Imagen:</label>
                <input type="file" id="Imagen" name="Imagen" accept="image/*" >
            </div><br>
            <button type="submit">Crear Usuario</button>
        </form>
        <a href="index.php">Regresar al menu</a>
    </body>
</html>
