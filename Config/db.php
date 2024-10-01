<?php

class Database {

    private $host = '127.0.0.1'; // Cambia según tu host de SQL Server
    private $db_name = 'Milogar'; // Cambia según el nombre de tu base de datos
    private $username = 'edi'; // Cambia según tu usuario de SQL Server
    private $password = '01024'; // Cambia según tu contraseña
    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO("sqlsrv:Server=" . $this->host . ";Database=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            //echo "Conexión exitosa a la base de datos SQL Server";
        } catch (PDOException $exception) {
            echo "Error de conexión: " . $exception->getMessage();
        }

        return $this->conn;
        
    }
    
    
    

}
?>
