
<?php
require_once "../../Controllers/RolController.php";
require_once "../../Models/RolModel.php";
?>
<html>
    <head>
        <title>MILOGAR - CREATE</title>
    </head>
    <body>
        <h1>Crear Rol</h1>
        <form action="index.php?action=store" method="POST">
            <label for="RolName">Nombre del Rol:</label>
            <input type="text" name="RolName" required><br>

            <label for="RolDescription">Descripción:</label>
            <textarea name="RolDescription" required></textarea><br>

            <label for="IsActive">Activo:</label>
            <input type="checkbox" name="IsActive" value="1"><br>

            <label for="CreatedAt">Fecha de Creación:</label>
            <input type="date" id="CreatedAt" name="CreatedAt" required><br>
            <button type="submit">Guardar</button>
        </form>

        <script src="../../assets/js/validaciones.js"></script>
    </body>
</html>

