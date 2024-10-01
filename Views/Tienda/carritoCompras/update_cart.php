<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = $_POST['product_id'];
    $action = $_POST['action'];

    // Verificar el carrito y el producto
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] == $productId) {
                if ($action == 'increase') {
                    $item['quantity']++; // Aumentar cantidad
                } elseif ($action == 'decrease' && $item['quantity'] > 1) {
                    $item['quantity']--; // Disminuir cantidad
                }
                break;
            }
        }
    }

    // Devolver el carrito actualizado como JSON
    echo json_encode($_SESSION['cart']);
    exit;
}
?>
