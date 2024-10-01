<?php

require_once __DIR__ . '/../Models/UsuarioModel.php'; // Asegúrate de requerir el modelo

class UsuarioController {

    private $model;

    public function __construct($db) {
        $this->conn = $db;
        $this->model = new UsuarioModel($db); // Instanciar el modelo
    }

    public function index() {
        
        // Obtener todos los usuarios a través del modelo
        $usuarios = $this->model->getAll();

        if ($usuarios === null) {
            echo "Error al obtener usuarios."; // Mensaje de depuración
            return;
        }

        // Incluir la vista que muestra la lista de usuarios
        return $usuarios;
    }

    public function store($data) {

        // Validación de contraseñas
        if ($data['Contrasenia'] !== $_POST['ConfirmarContrasenia']) {
            echo "Las contraseñas no coinciden.";
            return;
        }

        $usuarioModel = new UsuarioModel($this->conn);
        if ($usuarioModel->create($data)) {
            // Redirigir a la lista de usuarios después de la creación exitosa
            echo "<script>alert('Registro creado correctamente');</script>";
            header("Location: index.php"); // Cambia la ruta si es necesario
            exit;
        } else {
            echo "Error al crear el usuario.";
        }
    }

    //metodo para crear una cuenta:)
    public function createAccount() {
        // Verificar si se ha enviado el formulario
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Recoger los datos del formulario
            $data = [
                'NombreUsuario' => $_POST['NombreUsuario'],
                'Email' => $_POST['Email'],
                'Password' => $_POST['Contrasenia'],
                    // Imagen será manejada más adelante
            ];

            // Verificar si la contraseña y la confirmación coinciden
            if ($_POST['Contrasenia'] !== $_POST['ConfirmarContrasenia']) {
                echo "Las contraseñas no coinciden.";
                return;
            }

            // Manejar la imagen si se carga
            if (isset($_FILES['Imagen']) && $_FILES['Imagen']['error'] === UPLOAD_ERR_OK) {
                // Definir la ruta donde se guardará la imagen
                $uploadDir = 'imagenesMilogar/Usuarios/'; // Asegúrate de que esta carpeta exista y tenga permisos de escritura
                $imageName = basename($_FILES['Imagen']['name']);
                $uploadFilePath = $uploadDir . $imageName;

                // Mover la imagen cargada a la carpeta deseada
                if (move_uploaded_file($_FILES['Imagen']['tmp_name'], $uploadFilePath)) {
                    $data['Imagen'] = $imageName; // Guardar el nombre de la imagen
                } else {
                    echo "Error al subir la imagen.";
                    return;
                }
            } else {
                $data['Imagen'] = null; // Si no hay imagen, puedes dejarlo como NULL
            }

            // Crear la cuenta
            if ($this->model->createAccount($data)) {
                echo "Cuenta creada exitosamente.";
                // Redirigir o mostrar un mensaje de éxito
                header('Location: index.php?success=1'); // Ajusta según la página que desees redirigir
                exit;
            } else {
                echo "Error al crear la cuenta.";
            }
        }
    }

    public function edit($id) {
        $usuarioModel = new UsuarioModel($this->conn);
        $usuario = $usuarioModel->getById($id);

        if ($usuario) {
            // Cargar la vista de edición y pasar los datos del usuario
            require_once __DIR__ . '/../Views/Usuarios/edit.php'; // Asegúrate de que la ruta sea correcta
        } else {
            echo "Usuario no encontrado.";
        }
    }

    public function getById($id) {
        $sql = "SELECT * FROM Usuarios WHERE ID = :id";
        $query = $this->conn->prepare($sql);
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $data) {
        // Prepara la consulta de actualización
        $sql = "UPDATE Usuarios SET 
                NombreUsuario = :NombreUsuario,
                Email = :Email,
                RolID = :RolID,
                IsActive = :IsActive,
                FechaCreacion = :FechaCreacion" .
                (isset($data['Contrasenia']) ? ", Contrasenia = :Contrasenia" : "") .
                (isset($data['Imagen']) && !empty($data['Imagen']) ? ", Imagen = :Imagen" : "") .
                " WHERE ID = :id";

        $stmt = $this->conn->prepare($sql);

        // Enlazar parámetros
        $stmt->bindParam(':NombreUsuario', $data['NombreUsuario']);
        $stmt->bindParam(':Email', $data['Email']);
        $stmt->bindParam(':RolID', $data['RolID']);
        $stmt->bindParam(':IsActive', $data['IsActive']);
        $stmt->bindParam(':FechaCreacion', $data['FechaCreacion']);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        // Si hay una nueva contraseña, encriptarla y enlazar
        if (isset($data['Contrasenia'])) {
            $hashedPassword = password_hash($data['Contrasenia'], PASSWORD_DEFAULT);
            $stmt->bindParam(':Contrasenia', $hashedPassword);
        }

        // Si hay una nueva imagen, enlazarla
        if (isset($data['Imagen']) && !empty($data['Imagen'])) {
            $stmt->bindParam(':Imagen', $data['Imagen']);
        }

        // Ejecutar la consulta
        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            // Manejo de errores
            echo "Error al actualizar el usuario: " . $e->getMessage();
            return false;
        }
    }

    public function delete($id) {
        // Verificar que el ID sea un número entero
        if (!is_numeric($id)) {
            echo "ID no válido.";
            return false;
        }

        // Llamar al método delete del modelo
        $usuarioModel = new UsuarioModel($this->conn);
        if ($usuarioModel->delete($id)) {
            // Redirigir a la lista de usuarios después de la eliminación exitosa
            header("Location: index.php?success=1"); // Cambia la ruta si es necesario
            exit; // Asegúrate de detener la ejecución después de redirigir
        } else {
            echo "Error al eliminar el usuario.";
        }
    }

}
