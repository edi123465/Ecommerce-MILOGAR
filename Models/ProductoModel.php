<?php

class ProductoModel {

    private $conn;
    private $table = 'Productos';

    public function __construct($db) {
        $this->conn = $db;
    }

    // Crear un nuevo producto
    public function create($data) {
        $sql = "INSERT INTO " . $this->table . " 
                (nombreProducto, descripcionProducto, costoCompraIVA, precio_1, precio_2, precio_3, precio_4, stock, subcategoria_id, codigo_barras, imagen, isActive) 
                VALUES 
                (:nombreProducto, :descripcionProducto, :costoCompraIVA, :precio_1, :precio_2, :precio_3, :precio_4, :stock, :subcategoria_id, :codigo_barras, :imagen, :isActive)";
        $stmt = $this->conn->prepare($sql);

        // Vincular parámetros
        $stmt->bindParam(':nombreProducto', $data['nombreProducto']);
        $stmt->bindParam(':descripcionProducto', $data['descripcionProducto']);
        $stmt->bindParam(':costoCompraIVA', $data['costoCompraIVA']);
        $stmt->bindParam(':precio_1', $data['precio_1']);
        $stmt->bindParam(':precio_2', $data['precio_2']);
        $stmt->bindParam(':precio_3', $data['precio_3']);
        $stmt->bindParam(':precio_4', $data['precio_4']);
        $stmt->bindParam(':stock', $data['stock']);
        $stmt->bindParam(':subcategoria_id', $data['subcategoria_id'], PDO::PARAM_INT);
        $stmt->bindParam(':codigo_barras', $data['codigo_barras']);
        $stmt->bindParam(':imagen', $data['imagen']);
        $stmt->bindParam(':isActive', $data['isActive'], PDO::PARAM_BOOL);

        // Ejecutar la consulta
        return $stmt->execute();
    }

    // Método para obtener todos los productos con categorías y subcategorías
    public function getAllProductosConCategorias() {
        $query = "
            SELECT p.id, p.nombreProducto, p.descripcionProducto, p.precio, p.precio_1, 
                   p.precio_2, p.precio_3, p.precio_4, p.stock, 
                   s.id AS subcategoria_id, s.nombre AS subcategoria_nombre, 
                   c.id AS categoria_id, c.nombre AS categoria_nombre, 
                   p.codigo_barras
            FROM Productos p
            JOIN Subcategorias s ON p.subcategoria_id = s.id
            JOIN Categorias c ON s.categoria_id = c.id
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Leer (obtener) todos los productos
    public function read() {
        $sql = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener un producto por su ID
    public function getById($id) {
        $sql = "SELECT * FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Actualizar un producto
    public function update($id, $data) {
        $sql = "UPDATE " . $this->table . " 
                SET nombreProducto = :nombreProducto,
                    descripcionProducto = :descripcionProducto,
                    costoCompraIVA = :costoCompraIVA,
                    precio_1 = :precio_1,
                    precio_2 = :precio_2,
                    precio_3 = :precio_3,
                    precio_4 = :precio_4,
                    stock = :stock,
                    subcategoria_id = :subcategoria_id,
                    codigo_barras = :codigo_barras,
                    imagen = :imagen,
                    isActive = :isActive
                WHERE id = :id";
        $stmt = $this->conn->prepare($sql);

        // Vincular parámetros
        $stmt->bindParam(':nombreProducto', $data['nombreProducto']);
        $stmt->bindParam(':descripcionProducto', $data['descripcionProducto']);
        $stmt->bindParam(':costoCompraIVA', $data['costoCompraIVA']);
        $stmt->bindParam(':precio_1', $data['precio_1']);
        $stmt->bindParam(':precio_2', $data['precio_2']);
        $stmt->bindParam(':precio_3', $data['precio_3']);
        $stmt->bindParam(':precio_4', $data['precio_4']);
        $stmt->bindParam(':stock', $data['stock']);
        $stmt->bindParam(':subcategoria_id', $data['subcategoria_id'], PDO::PARAM_INT);
        $stmt->bindParam(':codigo_barras', $data['codigo_barras']);
        $stmt->bindParam(':imagen', $data['imagen']);
        $stmt->bindParam(':isActive', $data['isActive'], PDO::PARAM_BOOL);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        // Ejecutar la consulta
        return $stmt->execute();
    }

    // Eliminar un producto
    public function delete($id) {
        $sql = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

}

?>
