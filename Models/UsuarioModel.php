<?php

require_once __DIR__ . "/../Config/db.php";

class UsuarioModel {

    private $conn;
    private $table = 'Usuarios';

    public function __construct($db) {
        // Verifica que la conexión no sea nula
        if ($db === null) {

            throw new Exception("Conexión a la base de datos no establecida.");
        }

        $this->conn = $db;
    }

    public function getAll() {
        $query = "SELECT u.ID, u.NombreUsuario, u.Email, u.RolID, u.IsActive, u.FechaCreacion, u.Imagen, r.RolName 
                  FROM " . $this->table . " u 
                  JOIN Roles r ON u.RolID = r.ID";
        $stmt = $this->conn->prepare($query);
        if ($stmt->execute()) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $errorInfo = $stmt->errorInfo();
            echo "Error: " . $errorInfo[2];
            return null;
        }
    }

    public function createUserClient($data) {
        // Verifica que la conexión no sea null
        if ($this->conn === null) {
            throw new Exception("Conexión a la base de datos no establecida.");
        }

        // Prepara la consulta SQL
        $query = "INSERT INTO Usuarios (NombreUsuario, Email, Contrasenia, RolID, IsActive, FechaCreacion, Imagen)
              VALUES (:NombreUsuario, :Email, :Contrasenia, :RolID, :IsActive, :FechaCreacion, :Imagen)";

        // Prepara la declaración
        $stmt = $this->conn->prepare($query);

        // Vincula los parámetros
        $stmt->bindParam(':NombreUsuario', $data['NombreUsuario']);
        $stmt->bindParam(':Email', $data['Email']);

        $hashedPassword = password_hash($data['Password'], PASSWORD_BCRYPT); // Asegúrate de usar 'Password'
        $stmt->bindParam(':Contrasenia', $hashedPassword);

        // Establece el RolID por defecto como "Cliente"
        $rolID = 8; // Asumiendo que el RolID para Cliente es 1. Cambia este valor si es diferente.
        $stmt->bindParam(':RolID', $rolID);

        // Establece IsActive y FechaCreacion
        $isActive = 1; // Asumiendo que el usuario está activo por defecto
        $fechaCreacion = date('Y-m-d H:i:s'); // Fecha actual
        $stmt->bindParam(':IsActive', $isActive);
        $stmt->bindParam(':FechaCreacion', $fechaCreacion);

        // Puedes dejar el campo Imagen como NULL o vacío si no lo vas a usar al crear la cuenta
        $imagen = null; // O una cadena vacía
        $stmt->bindParam(':Imagen', $imagen);

        // Ejecuta la consulta
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function create($data) {
        // Verifica que la conexión no sea null
        if ($this->conn === null) {
            throw new Exception("Conexión a la base de datos no establecida.");
        }

        // Prepara la consulta SQL
        $query = "INSERT INTO Usuarios (NombreUsuario, Email, Contrasenia, RolID, IsActive, FechaCreacion, Imagen)
                  VALUES (:NombreUsuario, :Email, :Contrasenia, :RolID, :IsActive, :FechaCreacion, :Imagen)";

        // Prepara la declaración
        $stmt = $this->conn->prepare($query);

        // Vincula los parámetros
        $stmt->bindParam(':NombreUsuario', $data['NombreUsuario']);
        $stmt->bindParam(':Email', $data['Email']);

        $hashedPassword = password_hash($data['Contrasenia'], PASSWORD_BCRYPT);
        $stmt->bindParam(':Contrasenia', $hashedPassword);

        $stmt->bindParam(':RolID', $data['RolID']);
        $stmt->bindParam(':IsActive', $data['IsActive']);
        $stmt->bindParam(':FechaCreacion', $data['FechaCreacion']);
        $stmt->bindParam(':Imagen', $data['Imagen']);

        // Ejecuta la consulta
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function update($data) {
        $query = "UPDATE " . $this->table . " SET 
                  NombreUsuario = :nombre, 
                  Email = :email, 
                  RolID = :rolID, 
                  IsActive = :isActive, 
                  Imagen = :imagen 
                  WHERE ID = :id";
        $stmt = $this->conn->prepare($query);

        // Vincular los parámetros
        $stmt->bindParam(':nombre', $data['NombreUsuario']);
        $stmt->bindParam(':email', $data['Email']);
        $stmt->bindParam(':rolID', $data['RolID']);
        $stmt->bindParam(':isActive', $data['IsActive']);
        $stmt->bindParam(':imagen', $data['Imagen']);
        $stmt->bindParam(':id', $data['ID']);

        if ($stmt->execute()) {
            return true;
        } else {
            $errorInfo = $stmt->errorInfo();
            echo "Error: " . $errorInfo[2];
            return false;
        }
    }

    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE ID = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            return true;
        } else {
            $errorInfo = $stmt->errorInfo();
            echo "Error: " . $errorInfo[2];
            return false;
        }
    }

    //obtener mediante el nombre de usuario
    public function getByUserName($username) {
        $sql = "SELECT * FROM " . $this->table . " WHERE NombreUsuario = :nombreUsuario";
        $query = $this->conn->prepare($sql);
        $query->bindParam(':nombreUsuario', $username);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function getRoleById($roleId) {
        $sql = "SELECT RolName FROM Roles WHERE ID = :roleId";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':roleId', $roleId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC); // Devuelve el nombre del rol como array asociativo
    }

}

?>