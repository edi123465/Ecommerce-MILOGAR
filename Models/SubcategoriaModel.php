<?php

require_once __DIR__ . "/../Config/db.php";

class SubcategoriaModel {

    private $conn;
    private $table = 'Subcategorias';
    public $id;
    public $nombre;
    public $descripcion;
    public $categoria_id;
    public $isActive;
    public $fechaCreacion;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Obtener todas las subcategorías
    public function getAll() {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);

        if ($stmt->execute()) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;  // Devuelve el resultado
        } else {
            return null;  // Si falla, devuelve null
        }
    }

    // Establecer propiedades de la subcategoría
    public function setProperties($nombre, $descripcion, $categoria_id, $activo) {
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->categoria_id = $categoria_id;
        $this->isActive = $activo;
        $this->fechaCreacion = date('Y-m-d H:i:s'); // Puedes cambiar esto si usas GETDATE() en SQL Server
    }

    // Crear una nueva subcategoría
    public function create($data) {
        $query = "INSERT INTO " . $this->table . " (nombrSubcategoria, descripcionSubcategoria, categoria_id, isActive, fechaCreacion) 
              VALUES (:nombre, :descripcion, :categoria_id, :isActive, GETDATE())";
        $stmt = $this->conn->prepare($query);

        // Enlace de parámetros
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':descripcion', $data['descripcion']);
        $stmt->bindParam(':categoria_id', $data['categoria_id'], PDO::PARAM_INT);
        $stmt->bindParam(':isActive', $data['isActive'], PDO::PARAM_BOOL);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            return true;
        } else {
            // Captura el error y muéstralo para la depuración
            $errorInfo = $stmt->errorInfo();
            echo "Error: " . $errorInfo[2]; // Mostrar el mensaje de error
            return false;
        }
    }

    // Obtener subcategoría por ID
    public function getById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($data) {
        $query = "UPDATE " . $this->table . " 
              SET nombrSubcategoria = :nombrSubcategoria,
                  descripcionSubcategoria = :descripcionSubcategoria,
                  categoria_id = :categoria_id,
                  isActive = :isActive 
              WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        // Vincular los parámetros
        $stmt->bindParam(':nombrSubcategoria', $data['nombrSubcategoria']);
        $stmt->bindParam(':descripcionSubcategoria', $data['descripcionSubcategoria']);
        $stmt->bindParam(':categoria_id', $data['categoria_id'], PDO::PARAM_INT);
        $stmt->bindParam(':isActive', $data['isActive'], PDO::PARAM_BOOL);
        $stmt->bindParam(':id', $data['id'], PDO::PARAM_INT);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Redirigir a la página de índice
            header('Location: index.php');
            exit;
        } else {
            // Captura el error si algo falla
            $errorInfo = $stmt->errorInfo();
            echo "Error en la base de datos: " . $errorInfo[2];
        }
    }

    // Eliminar una subcategoría
    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

}

?>
