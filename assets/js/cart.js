
    // Capturar eventos de clic para botones de aumento y disminución de cantidad
    document.addEventListener('DOMContentLoaded', function () {
        // Botón para aumentar la cantidad
        document.querySelectorAll('.btn-plus').forEach(button => {
            button.addEventListener('click', function () {
                const productId = this.getAttribute('data-id');
                updateCart(productId, 'increase');  // Llamamos a la función para aumentar la cantidad
            });
        });

        // Botón para disminuir la cantidad
        document.querySelectorAll('.btn-minus').forEach(button => {
            button.addEventListener('click', function () {
                const productId = this.getAttribute('data-id');
                updateCart(productId, 'decrease');  // Llamamos a la función para disminuir la cantidad
            });
        });
    });

    // Función para actualizar el carrito en el servidor
    function updateCart(productId, action) {
        fetch('update_cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                'product_id': productId,
                'action': action
            })
        })
        .then(response => response.json())
        .then(cart => {
            // Actualizar la interfaz con los nuevos valores
            updateCartUI(cart);
        })
        .catch(error => console.error('Error al actualizar el carrito:', error));
    }

    // Función para actualizar la UI después de los cambios en el carrito
    function updateCartUI(cart) {
        let subtotal = 0;
        const taxRate = 0.21;
        const shipping = 10;

        // Actualizar cada fila del carrito
        document.querySelectorAll('#cart-items tr').forEach((row, index) => {
            if (index < cart.length) {
                const item = cart[index];
                const total = item.price * item.quantity;
                row.querySelector('.form-control').value = item.quantity;
                row.querySelector('.align-middle:nth-child(4)').textContent = `$${total.toFixed(2)}`;
                subtotal += total;
            }
        });

        // Calcular IVA y total
        const iva = subtotal * taxRate;
        const total = subtotal + iva + shipping;

        // Actualizar valores en la UI
        document.getElementById('cart-subtotal').textContent = subtotal.toFixed(2);
        document.getElementById('cart-iva').textContent = iva.toFixed(2);
        document.getElementById('cart-total').textContent = total.toFixed(2);
    }

