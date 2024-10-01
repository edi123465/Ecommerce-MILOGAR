<?php

class SubcategoriaController {

    private $conn;
    private $table = 'Subcategorias';

    public function __construct($db) {
        $this->conn = $db;
    }

    // Obtener todas las subcategorías
    public function getAll() {
        $query = "SELECT id, nombre, descripcion, categoria_id, isActive, fechaCreacion FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Almacenar una nueva subcategoría
    public function store($data) {
        $query = "INSERT INTO " . $this->table . " (nombre, descripcion, categoria_id, isActive, fechaCreacion) 
                  VALUES (:nombre, :descripcion, :categoria_id, :isActive, GETDATE())"; // Usamos GETDATE() para SQL Server
        $stmt = $this->conn->prepare($query);

        // Vincular los parámetros
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':descripcion', $data['descripcion']);
        $stmt->bindParam(':categoria_id', $data['categoria_id'], PDO::PARAM_INT);
        $stmt->bindParam(':isActive', $data['isActive'], PDO::PARAM_BOOL);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            echo "<script>alert('Subcategoría creada exitosamente');</script>";
            header('Location: index.php');  // Puedes redirigir a la misma página u otra
            exit;
        } else {
            // Captura el error si algo falla
            $errorInfo = $stmt->errorInfo();
            echo "Error en la base de datos: " . $errorInfo[2];
            return false;
        }
    }

    // Obtener subcategoría por ID
    public function getById($id) {
        $query = "SELECT id, nombre, descripcion, categoria_id, isActive FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Actualizar subcategoría
    public function update($id, $data) {
        $query = "UPDATE " . $this->table . " 
                  SET nombre = :nombre, descripcion = :descripcion, categoria_id = :categoria_id, isActive = :isActive 
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        // Vincular los parámetros
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':descripcion', $data['descripcion']);
        $stmt->bindParam(':categoria_id', $data['categoria_id'], PDO::PARAM_INT);
        $stmt->bindParam(':isActive', $data['isActive'], PDO::PARAM_BOOL);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            echo "<script>alert('Subcategoría actualizada exitosamente');</script>";
            return true;
        } else {
            $errorInfo = $stmt->errorInfo();
            echo "Error al actualizar: " . $errorInfo[2];
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
