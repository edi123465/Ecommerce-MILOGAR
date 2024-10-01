<?php
session_start();

// Verifica si se ha enviado el ID del producto
if (isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];

    // Busca el índice del producto en el carrito
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['id'] == $product_id) {
            // Elimina el producto del carrito
            unset($_SESSION['cart'][$key]);
            break; // Sale del bucle una vez que se elimina el producto
        }
    }

    // Opcional: Redirecciona de vuelta a la página del carrito
    header("Location: cart.php");
    exit;
}
?>
