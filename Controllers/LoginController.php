<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once $_SERVER['DOCUMENT_ROOT'] . '/Milogar/Models/UsuarioModel.php';

class LoginController {

    private $usuarioModel;

    public function __construct($connection) {
        $this->usuarioModel = new UsuarioModel($connection);
    }

    public function login($data) {
        $username = $data['txt_nombreUsuario'];
        $password = $data['txt_password'];

        // Obtener el usuario por nombre de usuario
        $usuario = $this->usuarioModel->getByUserName($username);

        if ($usuario) {
            // Verificar la contraseña
            if (password_verify($password, $usuario['Contrasenia'])) {
                // Obtener el nombre del rol del usuario
                $role = $this->usuarioModel->getRoleById($usuario['RolID']);

                // Almacenar información del usuario en la sesión
                $_SESSION['user_id'] = $usuario['ID'];
                $_SESSION['user_name'] = $usuario['NombreUsuario']; // Nombre del usuario
                $_SESSION['user_role'] = $role['RolName']; // Nombre del rol
                $_SESSION['is_logged_in'] = true; // Indicador de sesión activa
                // Almacenar información del usuario en la sesión
                $_SESSION['user_id'] = $usuario['ID'];
                $_SESSION['user_name'] = $usuario['NombreUsuario']; // Nombre del usuario
                $_SESSION['user_role'] = $usuario['RolID']; // ID del rol o nombre del rol si prefieres

                $_SESSION['is_logged_in'] = true; // Indicador de sesión activa
                // Redirigir dependiendo del rol del usuario
                if ($_SESSION['user_role'] === '1') {
                    // Si es Administrador, redirigir al panel de administración
                    header('Location: ../menu.php');
                } elseif ($_SESSION['user_role'] === '20') {
                    // Si es Cliente, redirigir a la tienda virtual
                    header('Location: ../Tienda/index.php');
                } else {
                    // Si el rol no es reconocido, redirigir a una página de error o principal
                    header('Location: ../acceso_denegado.php');
                }
                exit;

                // Depuración: mostrar contenido de la sesión
                echo "<pre>";
                var_dump($_SESSION);
                echo "</pre>";

                header('Location: ../menu.php'); // Redirigir a la página de inicio o panel de usuario
                exit;
            } else {
                return "Contraseña incorrecta.";
            }
        } else {
            return "Usuario no encontrado.";
        }
    }

    public function logout() {
        session_destroy(); // Destruir la sesión
        header('Location: login.php'); // Redirigir a la página de inicio
        exit;
    }

}
