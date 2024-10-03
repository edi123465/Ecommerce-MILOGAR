<?php

class SubcategoriaController {

    private $conn;
    private $table = 'Subcategorias';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $query = "SELECT id, nombrSubcategoria, descripcionSubcategoria, categoria_id, isActive, fechaCreacion FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        // Para depuración
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (empty($result)) {
            echo "No se encontraron subcategorías."; // Mensaje de depuración
        }
        return $result;
    }

    public function store($data) {
        // Imprimir los datos que se están recibiendo
        var_dump($data); // Esto te permitirá ver los valores que estás intentando insertar

        $query = "INSERT INTO " . $this->table . " (nombrSubcategoria, descripcionSubcategoria, categoria_id, isActive, fechaCreacion) 
          VALUES (:nombrSubcategoria, :descripcionSubcategoria, :categoria_id, :isActive, GETDATE())";
        $stmt = $this->conn->prepare($query);

        // Vincular los parámetros
        $stmt->bindParam(':nombrSubcategoria', $data['nombreSubcategoria']);
        $stmt->bindParam(':descripcionSubcategoria', $data['descripcionSubcategoria']);
        $stmt->bindParam(':categoria_id', $data['categoria_id'], PDO::PARAM_INT);
        $stmt->bindParam(':isActive', $data['isActive'], PDO::PARAM_BOOL);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Iniciar sesión y guardar el mensaje
            session_start(); // Asegúrate de que la sesión esté iniciada
            $_SESSION['message'] = "Subcategoría creada exitosamente."; // Guardar el mensaje en la sesión
            header('Location: index.php');  // Redirigir a la página de índice
            exit; // Asegúrate de salir después de la redirección
        } else {
            // Captura el error si algo falla
            $errorInfo = $stmt->errorInfo();
            echo "Error en la base de datos: " . $errorInfo[2];
        }
    }

    // Obtener subcategoría por ID
    public function getById($id) {
        $query = "SELECT id, nombrSubcategoria, descripcionSubcategoria, categoria_id, isActive FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $data) {
        // Prepara la consulta de actualización para la tabla subcategorias
        $sql = "UPDATE " . $this->table . " SET 
            nombrSubcategoria = :nombrSubcategoria,
            descripcionSubcategoria = :descripcionSubcategoria,
            categoria_id = :categoria_id,
            isActive = :isActive" .
                " WHERE id = :id"; // Asegúrate de que el campo de ID es el correcto

        $stmt = $this->conn->prepare($sql);

        // Enlazar parámetros
        $stmt->bindParam(':nombrSubcategoria', $data['nombrSubcategoria']);
        $stmt->bindParam(':descripcionSubcategoria', $data['descripcionSubcategoria']);
        $stmt->bindParam(':categoria_id', $data['categoria_id'], PDO::PARAM_INT);
        $stmt->bindParam(':isActive', $data['isActive'], PDO::PARAM_BOOL);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        // Ejecutar la consulta
        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            // Manejo de errores
            echo "Error al actualizar la subcategoría: " . $e->getMessage();
            return false;
        }
    }

    // Eliminar subcategoría
    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo "<script>alert('Subcategoría eliminada exitosamente');</script>";
            header('Location: index.php?success=deleted');
            exit;
        } else {
            echo "Error al eliminar la subcategoría.";
        }
    }

}

?>
