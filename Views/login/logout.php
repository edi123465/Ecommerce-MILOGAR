<?php
session_start(); // Iniciar la sesión

// Verificar si hay una sesión activa
if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in']) {
    // Destruir todas las variables de sesión
    $_SESSION = array(); // Eliminar todas las variables de sesión

    // Si se desea, se puede destruir la sesión
    session_destroy(); // Destruir la sesión

    // Redirigir al usuario a la página de inicio o inicio de sesión
    header('Location: login.php'); // Cambia 'login.php' por la ruta deseada
    exit;
} else {
    // Si no hay sesión activa, redirigir al usuario a la página de inicio de sesión
    header('Location: login.php'); // Cambia 'login.php' por la ruta deseada
    exit;
}
