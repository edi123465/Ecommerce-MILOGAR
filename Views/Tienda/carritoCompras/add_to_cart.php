<?php
session_start();

// Verificar si el carrito ya está inicializado
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Agregar producto al carrito
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = $_POST['product_id'];
    $productName = $_POST['product_name'];
    $productPrice = $_POST['product_price'];

    // Crear un array con los datos del producto
    $product = [
        'id' => $productId,
        'name' => $productName,
        'price' => $productPrice,
        'quantity' => 1 // Se puede modificar según necesidad
    ];

    // Verificar si el producto ya existe en el carrito
    $productFound = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] === $productId) {
            $item['quantity']++;
            $productFound = true;
            break;
        }
    }

    // Si el producto no está en el carrito, agregarlo
    if (!$productFound) {
        $_SESSION['cart'][] = $product;
    }
}

// Redireccionar de vuelta al catálogo o donde necesites
header('Location: ../shop.php');
exit;
?>
