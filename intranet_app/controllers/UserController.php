<?php
require_once __DIR__ . '/../Core/BaseIntranetController.php';
require_once __DIR__ . '/../Core/Auth.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/AuditLog.php';

class UserController extends BaseIntranetController {
    private $userModel;
    private $auditLogModel;
    private $baseUrl = '/elangel_medical_center/admin'; // Definir la base URL

    public function __construct() {
        $this->userModel = new User();
        $this->auditLogModel = new AuditLog();
    }

    /**
     * Muestra la página principal de administración de usuarios.
     * Solo accesible por administradores.
     */
    public function index() {
        Auth::requireLogin($this->baseUrl . '/login', 'admin'); // Solo administradores pueden ver esta sección

        $users = $this->userModel->getAll();

        $this->render('users/index.php', [
            'users' => $users,
            'flash_message' => $this->getFlashMessage(),
            'user' => Auth::user()
        ]);
    }

    /**
     * Procesa la creación de un nuevo usuario.
     * Solo accesible por administradores.
     */
    public function create() {
        Auth::requireLogin($this->baseUrl . '/login', 'admin');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';
            $role = $_POST['role'] ?? 'assistant';
            $full_name = trim($_POST['full_name'] ?? '');
            $email = trim($_POST['email'] ?? '');

            // Validación básica
            if (empty($username) || empty($password) || empty($full_name) || empty($email)) {
                $this->setFlashMessage('error', 'Todos los campos obligatorios deben ser llenados.');
                $this->redirect($this->baseUrl . '/usuarios');
                return;
            }

            // Validar rol
            if (!in_array($role, ['admin', 'assistant'])) {
                $this->setFlashMessage('error', 'Rol de usuario no válido.');
                $this->redirect($this->baseUrl . '/usuarios');
                return;
            }

            // Verificar si el nombre de usuario ya existe
            if ($this->userModel->findByUsername($username)) {
                $this->setFlashMessage('error', 'El nombre de usuario ya existe. Por favor, elige otro.');
                $this->redirect($this->baseUrl . '/usuarios');
                return;
            }

            $userData = [
                'username' => $username,
                'password' => $password, // El modelo se encarga de hashear
                'role' => $role,
                'full_name' => $full_name,
                'email' => $email
            ];

            if ($this->userModel->create($userData)) {
                $this->setFlashMessage('success', 'Usuario creado exitosamente.');
                $this->auditLogModel->logAction(Auth::user()['id'], 'user_created', "Usuario '{$username}' creado por " . Auth::user()['username']);
            } else {
                $this->setFlashMessage('error', 'Error al crear el usuario.');
            }
            $this->redirect($this->baseUrl . '/usuarios');
        } else {
            $this->redirect($this->baseUrl . '/usuarios');
        }
    }

    /**
     * Procesa la actualización de un usuario existente.
     * Solo accesible por administradores.
     */
    public function update($id) {
        Auth::requireLogin($this->baseUrl . '/login', 'admin');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $role = $_POST['role'] ?? 'assistant';
            $full_name = trim($_POST['full_name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? ''; // Opcional, para cambio de contraseña

            // Validación básica
            if (empty($username) || empty($full_name) || empty($email)) {
                $this->setFlashMessage('error', 'Todos los campos obligatorios (excepto contraseña) deben ser llenados.');
                $this->redirect($this->baseUrl . '/usuarios');
                return;
            }
            // Validar rol
            if (!in_array($role, ['admin', 'assistant'])) {
                $this->setFlashMessage('error', 'Rol de usuario no válido.');
                $this->redirect($this->baseUrl . '/usuarios');
                return;
            }

            // Verificar si el nombre de usuario ha cambiado y si el nuevo nombre ya existe
            $originalUser = $this->userModel->findById($id);
            if (!$originalUser) {
                $this->setFlashMessage('error', 'Usuario no encontrado para actualizar.');
                $this->redirect($this->baseUrl . '/usuarios');
                return;
            }
            if ($username !== $originalUser['username'] && $this->userModel->findByUsername($username)) {
                $this->setFlashMessage('error', 'El nombre de usuario elegido ya existe. Por favor, elige otro.');
                $this->redirect($this->baseUrl . '/usuarios');
                return;
            }

            $userData = [
                'username' => $username,
                'role' => $role,
                'full_name' => $full_name,
                'email' => $email
            ];

            $success = $this->userModel->update($id, $userData);

            // Si se proporcionó una nueva contraseña, actualizarla
            if (!empty($password)) {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                if ($this->userModel->updatePassword($id, $hashed_password)) {
                    $success = true; // Considerar éxito si al menos la contraseña se actualizó
                    $this->setFlashMessage('success', 'Usuario y contraseña actualizados exitosamente.');
                    $this->auditLogModel->logAction(Auth::user()['id'], 'user_password_updated', "Contraseña del usuario '{$username}' actualizada por " . Auth::user()['username']);
                } else {
                    $success = false; // Falló la actualización de contraseña
                    $this->setFlashMessage('error', 'Error al actualizar la contraseña del usuario.');
                }
            }
            
            if ($success) {
                $this->setFlashMessage('success', 'Usuario actualizado exitosamente.');
                $this->auditLogModel->logAction(Auth::user()['id'], 'user_updated', "Usuario '{$username}' actualizado por " . Auth::user()['username']);
            } else {
                // Si ya se estableció un mensaje de error para la contraseña, no lo sobrescribas
                if (!$this->getFlashMessage()) { // Solo establece si no hay un mensaje previo
                    $this->setFlashMessage('error', 'Error al actualizar el usuario.');
                }
            }
            $this->redirect($this->baseUrl . '/usuarios');
        } else {
            $this->redirect($this->baseUrl . '/usuarios');
        }
    }

    /**
     * Procesa la eliminación de un usuario.
     * Solo accesible por administradores.
     */
    public function delete($id) {
        Auth::requireLogin($this->baseUrl . '/login', 'admin');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userToDelete = $this->userModel->findById($id);
            if (!$userToDelete) {
                $this->setFlashMessage('error', 'Usuario no encontrado para eliminar.');
                $this->redirect($this->baseUrl . '/usuarios');
                return;
            }

            // Impedir que un admin se elimine a sí mismo (opcional pero recomendable)
            if (Auth::user()['id'] == $id) {
                $this->setFlashMessage('error', 'No puedes eliminar tu propia cuenta de administrador.');
                $this->redirect($this->baseUrl . '/usuarios');
                return;
            }

            if ($this->userModel->delete($id)) {
                $this->setFlashMessage('success', 'Usuario "' . $userToDelete['username'] . '" eliminado exitosamente.');
                $this->auditLogModel->logAction(Auth::user()['id'], 'user_deleted', "Usuario '{$userToDelete['username']}' eliminado por " . Auth::user()['username']);
            } else {
                $this->setFlashMessage('error', 'Error al eliminar el usuario.');
            }
            $this->redirect($this->baseUrl . '/usuarios');
        } else {
            $this->redirect($this->baseUrl . '/usuarios');
        }
    }

    /**
     * API endpoint para obtener los detalles de un usuario.
     * @param int $id ID del usuario.
     */
    public function getUserDetails($id) {
        Auth::requireLogin($this->baseUrl . '/login', 'admin'); // Solo admin puede obtener detalles de usuario
        header('Content-Type: application/json');

        $user = $this->userModel->findById($id);
        if ($user) {
            echo json_encode(['success' => true, 'user' => $user]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Usuario no encontrado.']);
        }
        exit;
    }
}