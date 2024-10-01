<?php

class CategoriaController {

    private $conn;
    private $table = 'Categorias';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $query = "SELECT id, nombre, descripcion, IsActive, fechaCreacion FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function store($data) {
        $data = [
            'nombre' => $_POST['categoriaName'],
            'descripcion' => $_POST['categoriaDescription'],
            'isActive' => isset($_POST['isActive']) ? 1 : 0 // Asegúrate de que el valor es 0 o 1
        ];

        // Prepara la consulta SQL para insertar en la tabla Categorías
        $query = "INSERT INTO " . $this->table . " (nombre, descripcion, isActive, fechaCreacion) 
              VALUES (:nombre, :descripcion, :isActive, GETDATE())"; // Usamos GETDATE() para SQL Server
        // Prepara la declaración
        $stmt = $this->conn->prepare($query);

        // Vincula los parámetros
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':descripcion', $data['descripcion']);
        $stmt->bindParam(':isActive', $data['isActive']);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Iniciar sesión si no se ha hecho
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }

            // Establecer un mensaje de éxito en la sesión
            $_SESSION['message'] = 'Categoría creada exitosamente';

            header('Location: index.php');  // Redirigir a la página de inicio
            exit;
        } else {
            // Captura el error si algo falla
            $errorInfo = $stmt->errorInfo();
            echo "Error en la base de datos: " . $errorInfo[2];
            return false; // Inserción fallida
        }
    }

    public function getById($id) {
        $query = "SELECT ID, nombre, descripcion, IsActive FROM " . $this->table . " WHERE ID = :id";
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
        $query = "UPDATE " . $this->table . " SET nombre = :nombre, descripcion = :descripcion, isActive = :isActive WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':descripcion', $data['descripcion']);
        $stmt->bindParam(':isActive', $data['isActive'], PDO::PARAM_BOOL); // Asegúrate de que es un booleano
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            echo "<script>alert('exito');</script>";
            header("location: index.php");

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
