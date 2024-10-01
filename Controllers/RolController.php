<?php

class RolController {

    private $conn;
    private $table = 'Roles';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $query = "SELECT ID, RolName, RolDescription, IsActive, CreatedAt FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function store($data) {
        $query = "INSERT INTO " . $this->table . " (RolName, RolDescription, IsActive, CreatedAt) 
              VALUES (:rolName, :rolDescription, :isActive, GETDATE())"; // Usamos GETDATE() para SQL Server
        $stmt = $this->conn->prepare($query);

        // Vincular los parámetros
        $stmt->bindParam(':rolName', $data['RolName']);
        $stmt->bindParam(':rolDescription', $data['RolDescription']);
        $stmt->bindParam(':isActive', $data['IsActive']);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            echo "<script>alert('Registro creado exitosamente');</script>";
            header('Location: index.php');  // Puedes redirigir a la misma página u otra
            exit;
            return true;
        } else {
            // Captura el error si algo falla
            $errorInfo = $stmt->errorInfo();
            echo "Error en la base de datos: " . $errorInfo[2];
            return false;
        }
    }

    // Asegúrate de que este método esté definido
    public function edit($id) {
        // Aquí debes agregar la lógica para obtener el rol basado en el ID
        $query = "SELECT * FROM Roles WHERE ID = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $query = "SELECT ID, RolName, RolDescription, IsActive FROM " . $this->table . " WHERE ID = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $query = "INSERT INTO " . $this->table . " (RolName, RolDescription, IsActive) VALUES (:rolName, :rolDescription, :isActive)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':rolName', $data['RolName']);
        $stmt->bindParam(':rolDescription', $data['RolDescription']);
        $stmt->bindParam(':isActive', $data['IsActive']);
        return $stmt->execute();
    }

    public function update($id, $data) {
        $query = "UPDATE " . $this->table . " SET RolName = :RolName, RolDescription = :RolDescription, IsActive = :IsActive WHERE ID = :id";
        $stmt = $this->conn->prepare($query);

        // Vincular los parámetros
        $stmt->bindParam(':RolName', $data['RolName']);
        $stmt->bindParam(':RolDescription', $data['RolDescription']);
        $stmt->bindParam(':IsActive', $data['IsActive']);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            return true;
        } else {
            $errorInfo = $stmt->errorInfo();
            echo "Error al actualizar: " . $errorInfo[2]; // Mostrar el error si algo falla
            return false;
        }
    }

    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE ID = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            // Redirigir a la lista de roles después de eliminar
            header('Location: index.php?success=deleted');
            exit;
        } else {
            echo "Error al eliminar el rol.";
        }
    }

}

?>
