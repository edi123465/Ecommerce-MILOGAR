<?php

require_once __DIR__ . "/../Config/db.php";

class RolModel {

    private $conn;
    private $table = 'Roles';
    public $RolID;
    public $RolName;
    public $RolDescription;
    public $IsActive;
    public $CreatedAt;

    public function __construct($db) {

        $this->conn = $db;
    }

    public function getAll() {
        $query = "SELECT * FROM Roles";
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

    // Crear un nuevo usuario
    public function create($data) {
        $query = "INSERT INTO " . $this->table . " (NombreUsuario, Email, Contrasenia, RolID, IsActive, FechaCreacion, Imagen) 
              VALUES (:NombreUsuario, :Email, :Contrasenia, :RolID, :IsActive, :FechaCreacion, :Imagen)";
        $stmt = $this->conn->prepare($query);

        // Enlace de parámetros
        $stmt->bindParam(':NombreUsuario', $data['NombreUsuario']);
        $stmt->bindParam(':Email', $data['Email']);
        $stmt->bindParam(':Contrasenia', password_hash($data['Contrasenia'], PASSWORD_DEFAULT)); // Encriptar la contraseña
        $stmt->bindParam(':RolID', $data['RolID']);
        $stmt->bindParam(':IsActive', $data['IsActive']);
        $stmt->bindParam(':FechaCreacion', $data['FechaCreacion']);
        $stmt->bindParam(':Imagen', $data['Imagen']); // Asumiendo que tienes la ruta de la imagen
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

    public function getById($id) {
        $sql = "SELECT * FROM Roles WHERE ID = :id";
        $query = $this->conn->prepare($sql);
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }

// Actualizar un rol
    public function update($id, $rolName, $rolDescription, $isActive) {
        $query = "UPDATE " . $this->table . " 
              SET RolName = :RolName, RolDescription = :RolDescription, IsActive = :IsActive 
              WHERE ID = :ID";
        $stmt = $this->conn->prepare($query);

        // Enlace de parámetros
        $stmt->bindParam(':ID', $id, PDO::PARAM_INT); // Asegúrate de usar el ID recibido
        $stmt->bindParam(':RolName', $rolName); // Usa el valor pasado al método
        $stmt->bindParam(':RolDescription', $rolDescription); // Usa el valor pasado al método
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
