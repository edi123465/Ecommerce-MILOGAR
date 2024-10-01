<?php

require_once __DIR__ . "/../Config/db.php";

class CategoriaModel {

    private $conn;
    private $table = 'Categorias';
    public $RolID;
    public $RolName;
    public $RolDescription;
    public $IsActive;
    public $CreatedAt;

    public function __construct($db) {

        $this->conn = $db;
    }

    public function getAll() {
        $query = "SELECT * FROM Categorias";
        $stmt = $this->conn->prepare($query);

        if ($stmt->execute()) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;  // Devuelve el resultado
        } else {
            return null;  // Si falla, devuelve null
        }
    }

    public function setProperties($nombre, $descripcion, $activo) {
        $this->RolName = $nombre;
        $this->RolDescription = $descripcion;
        $this->IsActive = $activo;
        // Puedes establecer CreatedAt aquí o dejarlo en la consulta con GETDATE() en SQL Server
        $this->CreatedAt = date('Y-m-d H:i:s'); // O solo elimínalo si usas GETDATE() en la consulta
    }

    public function create($data) {
        // Verifica que la conexión no sea null
        if ($this->conn === null) {
            throw new Exception("Conexión a la base de datos no establecida.");
        }

        // Prepara la consulta SQL para la tabla Categorías
        $query = "INSERT INTO Categorias (nombre, descripcion, isActive, fechaCreacion)
              VALUES (:nombre, :descripcion, :isActive, :fechaCreacion)";

        // Prepara la declaración
        $stmt = $this->conn->prepare($query);

        // Vincula los parámetros
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':descripcion', $data['descripcion']);
        $stmt->bindParam(':isActive', $data['isActive']);

        // Si la fecha de creación no se pasa, utiliza la fecha actual
        $fechaCreacion = $data['fechaCreacion'] ?? date('Y-m-d H:i:s');
        $stmt->bindParam(':fechaCreacion', $fechaCreacion);

        // Ejecuta la consulta
        if ($stmt->execute()) {
            return true; // Inserción exitosa
        }

        return false; // Inserción fallida
    }

    public function getById($id) {
        $sql = "SELECT * FROM Roles WHERE ID = :id";
        $query = $this->conn->prepare($sql);
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }

// Actualizar un rol
    public function update($id, $nombre, $descripcion, $isActive) {
        $query = "UPDATE " . $this->table . " 
              SET nombre = :nombre, descripcion = :descripcion, IsActive = :IsActive 
              WHERE ID = :ID";
        $stmt = $this->conn->prepare($query);

        // Enlace de parámetros
        $stmt->bindParam(':ID', $id, PDO::PARAM_INT); // Asegúrate de usar el ID recibido
        $stmt->bindParam(':nombre', $rolName); // Usa el valor pasado al método
        $stmt->bindParam(':descripcion', $rolDescription); // Usa el valor pasado al método
        $stmt->bindParam(':IsActive', $isActive, PDO::PARAM_INT); // Usa el valor pasado al método
        // Ejecutar la consulta
        if ($stmt->execute()) {
            return true; // Si la ejecución es exitosa
        }
        return false; // Si hay un error
    }

    // Eliminar un rol
    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE ID = :ID";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':ID', $id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

}

?>
