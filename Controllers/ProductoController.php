<?php

require_once __DIR__ . '/../Models/ProductoModel.php'; // Asegúrate de que la ruta es correcta

class ProductoController {

    private $model;

    // Constructor para inicializar el modelo
    public function __construct($connection) {
        $this->model = new ProductoModel($connection); // Pasar la conexión al modelo
    }

    // Método para crear un producto
    public function createProducto($data) {
        if ($this->model->create($data)) {
            $_SESSION['message'] = "Producto creado exitosamente.";
            header('Location: index.php'); // Redirigir a la lista de productos
            exit;
        } else {
            echo "Error al crear el producto.";
        }
    }

    // Método para obtener un producto por ID
    public function getProductoById($id) {
        return $this->model->getById($id);
    }

    // Método para actualizar un producto
    public function updateProducto($id, $data) {
        if ($this->model->update($id, $data)) {
            $_SESSION['message'] = "Producto actualizado exitosamente.";
            header('Location: index.php'); // Redirigir a la lista de productos
            exit;
        } else {
            echo "Error al actualizar el producto.";
        }
    }

    // Método para eliminar un producto
    public function deleteProducto($id) {
        if ($this->model->delete($id)) {
            $_SESSION['message'] = "Producto eliminado exitosamente.";
            header('Location: index.php'); // Redirigir a la lista de productos
            exit;
        } else {
            echo "Error al eliminar el producto.";
        }
    }

    // Método para obtener todos los productos
    public function getAllProductos() {
        return $this->model->read();
    }

    // Método para obtener todos los productos con categorías y subcategorías
    public function getAllProductosConCategorias() {
        return $this->model->getAllProductosConCategorias();
    }

}
