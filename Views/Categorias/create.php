
<?php
require_once "../../Controllers/CategoriaController.php";
require_once "../../Models/CategoriaModel.php";
?>
<html>
    <head>
        <title>MILOGAR - CREATE</title>
    </head>
    <body>
        <h1>Crear Categoría</h1>
        <form action="index.php?action=store" method="POST">
            <label for="RolName">Nombre:</label>
            <input type="text" name="categoriaName" required><br>

            <label for="RolDescription">Descripción:</label>
            <textarea name="categoriaDescription" required></textarea><br>

            <label for="IsActive">Activo:</label>
            <input type="checkbox" name="IsActive" value="1"><br>

            <label for="CreatedAt">Fecha de Creación:</label>
            <input type="datetime-local" id="CreatedAt" name="CreatedAt" required><br>
            <button type="submit">Guardar</button>

        </form>
        <button type="submit"><a href="index.php">Regresar a la consulta</a></button>


        <script src="../../assets/js/validaciones.js"></script>
    </body>
</html>

