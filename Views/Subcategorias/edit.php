<?php
session_start(); // Iniciar sesión para acceder al mensaje

// Cargar la conexión usando ruta absoluta
require_once __DIR__ . '/../../Config/db.php'; // Asegúrate de que la ruta sea correcta
require_once __DIR__ . '/../../Controllers/SubcategoriaController.php'; // Cargar el controlador de subcategorías

// Crear una instancia de la conexión
$db = new Database();
$connection = $db->getConnection();

// Instanciar el controlador
$controller = new SubcategoriaController($connection);

// Verificar si se proporciona un ID
if (isset($_GET['id'])) {
    $subcategoria = $controller->getById((int)$_GET['id']); // Obtener la subcategoría por ID
} else {
    echo "ID no válido.";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Editar Subcategoría</title>
</head>
<script>
function confirmUpdate() {
    return confirm("¿Estás seguro de que deseas actualizar esta subcategoría?");
}


</script>
<body>

<h1>Editar Subcategoría</h1>
<form action="update.php?id=<?= htmlspecialchars($subcategoria['id']) ?>" method="POST" onsubmit="return confirmUpdate();">
    <input type="hidden" name="id" value="<?php echo $subcategoria['id']; ?>"> <!-- ID oculto -->
    
    <label for="nombre">Nombre de la subcategoría:</label>
    <input type="text" id="nombre" name="nombrSubcategoria" value="<?php echo $subcategoria['nombrSubcategoria']; ?>" required><br><br>

    <label for="descripcion">Descripción:</label>
    <textarea id="descripcion" name="descripcionSubcategoria" rows="4" cols="50" required><?php echo $subcategoria['descripcionSubcategoria']; ?></textarea><br><br>

    <label for="categoria_id">Categoría:</label>
    <select id="categoria_id" name="categoria_id" required>
        <option value="">-- Selecciona una categoría --</option>
        <?php
        // Consulta para obtener las categorías activas
        $query = "SELECT id, nombreCategoria FROM Categorias WHERE isActive = 1"; // Asumiendo que la columna es 'nombreCategoria'
        $stmt = $connection->prepare($query);
        $stmt->execute();
        $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Mostrar las opciones en el <select>
        foreach ($categorias as $categoria) {
            $selected = ($categoria['id'] == $subcategoria['categoria_id']) ? 'selected' : '';
            echo "<option value='" . $categoria['id'] . "' $selected>" . $categoria['nombreCategoria'] . "</option>";
        }
        ?>
    </select><br><br>

    <label for="isActive">¿Subcategoría activa?</label>
    <input type="checkbox" id="isActive" name="isActive" value="1" <?php echo $subcategoria['isActive'] ? 'checked' : ''; ?>><br><br>

    <button type="submit">Actualizar Subcategoría</button>
</form>
<button type="submit"><a href="index.php">Regresar a la consulta</a></button>

<script src="../../assets/js/validaciones.js"></script>
</body>
</html>
