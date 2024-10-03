<html>
<head>
    <title>Crear Subcategoría</title>
</head>
<body>
    <h1>Crear Subcategoría</h1>
    <form action="index.php?action=store" method="POST">
        <label for="nombre">Nombre de la subcategoría:</label>
        <input type="text" id="nombre" name="nombreSubcategoria" required><br><br>

        <label for="descripcion">Descripción:</label>
        <textarea id="descripcion" name="descripcionSubcategoria" rows="4" cols="50" required></textarea><br><br>

        <label for="categoria_id">Categoría:</label>
        <select id="categoria_id" name="categoria_id" required>
            <option value="">-- Selecciona una categoría --</option>
            <?php
            // Conexión a la base de datos
            require_once "../../Config/db.php";
            $db = new Database();
            $conn = $db->getConnection();

            // Consulta para obtener las categorías activas
            $query = "SELECT id, nombreCategoria FROM Categorias WHERE isActive = 1"; // Asumiendo que la columna es 'nombreCategoria'
            $stmt = $conn->prepare($query);
            $stmt->execute();
            $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Mostrar las opciones en el <select>
            foreach ($categorias as $categoria) {
                echo "<option value='" . $categoria['id'] . "'>" . $categoria['nombreCategoria'] . "</option>";
            }
            ?>
        </select><br><br>

        <label for="isActive">¿Subcategoría activa?</label>
        <input type="checkbox" id="isActive" name="isActive" value="1"><br><br>

        <button type="submit">Crear Subcategoría</button>
    </form>
    <button type="submit"><a href="index.php">Regresar a la consulta</a></button>
</body>
</html>
