<?php
require_once __DIR__ . '/../Core/BaseIntranetController.php';
require_once __DIR__ . '/../Core/Auth.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/AuditLog.php'; // Necesitaremos un modelo de log

class AuthController extends BaseIntranetController {
    private $userModel;
    private $auditLogModel;
    private $baseUrl = '/elangel_medical_center/admin'; // Definir la base URL

    public function __construct() {
        $this->userModel = new User();
        $this->auditLogModel = new AuditLog();
    }

    /**
     * Muestra la página de login.
     */
    public function showLoginForm() {
        if (Auth::isLoggedIn()) {
            $this->redirect($this->baseUrl . '/dashboard'); // Ya está logueado, redirigir al dashboard
        }
        $this->render('auth/login.php', ['flash_message' => $this->getFlashMessage()], 'blank');
    }

    /**
     * Procesa el intento de login.
     */
    public function login() {
        if (Auth::isLoggedIn()) {
            $this->redirect($this->baseUrl . '/dashboard');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';

            if (empty($username) || empty($password)) {
                $this->setFlashMessage('error', 'Por favor, introduce usuario y contraseña.');
                $this->redirect($this->baseUrl . '/login');
            }

            $user = $this->userModel->findByUsername($username);

            if ($user && password_verify($password, $user['password'])) {
                Auth::login($user); // Inicia sesión y guarda datos del usuario
                $this->auditLogModel->logAction($user['id'], 'user_login', "Usuario {$username} ha iniciado sesión.");
                $this->redirect($this->baseUrl . '/dashboard');
            } else {
                $this->setFlashMessage('error', 'Usuario o contraseña incorrectos.');
                $this->auditLogModel->logAction(null, 'login_failed', "Intento de login fallido para usuario: {$username}");
                $this->redirect($this->baseUrl . '/login');
            }
        } else {
            $this->redirect($this->baseUrl . '/login'); // Si no es POST, redirigir al formulario
        }
    }

    /**
     * Cierra la sesión del usuario.
     */
    public function logout() {
        $user_id = Auth::user()['id'] ?? null;
        $username = Auth::user()['username'] ?? 'Desconocido';
        Auth::logout();
        $this->auditLogModel->logAction($user_id, 'user_logout', "Usuario {$username} ha cerrado sesión.");
        $this->setFlashMessage('success', 'Has cerrado sesión correctamente.');
        $this->redirect($this->baseUrl . '/login');
    }
}
?>
