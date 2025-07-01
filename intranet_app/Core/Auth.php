<?php
class Auth {
    /**
     * Verifica si el usuario está autenticado.
     * @return bool
     */
    public static function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    /**
     * Obtiene los datos del usuario autenticado.
     * @return array|null
     */
    public static function user() {
        return $_SESSION['user_data'] ?? null;
    }

    /**
     * Inicia sesión para un usuario.
     * @param array $user_data Datos del usuario (id, username, role, full_name).
     */
    public static function login($user_data) {
        session_regenerate_id(true); // Regenera el ID de sesión para prevenir Session Fixation
        $_SESSION['user_id'] = $user_data['id'];
        $_SESSION['user_data'] = [
            'id' => $user_data['id'],
            'username' => $user_data['username'],
            'role' => $user_data['role'],
            'full_name' => $user_data['full_name']
        ];
    }

    /**
     * Cierra la sesión del usuario.
     */
    public static function logout() {
        $_SESSION = []; // Vacía todas las variables de sesión
        session_destroy(); // Destruye la sesión
        setcookie(session_name(), '', time() - 3600, '/'); // Elimina la cookie de sesión
    }

    /**
     * Verifica si el usuario tiene un rol específico.
     * @param string $role
     * @return bool
     */
    public static function hasRole($role) {
        $user = self::user();
        return $user && $user['role'] === $role;
    }

    /**
     * Redirige al usuario si no está autenticado o no tiene el rol requerido.
     * @param string $redirect_path Ruta a redirigir si no está logueado (por defecto a login).
     * @param string|null $required_role Rol requerido para acceder.
     */
    public static function requireLogin($redirect_path = '/elangel_medical_center/admin/login', $required_role = null) {
        if (!self::isLoggedIn()) {
            header("Location: " . $redirect_path);
            exit();
        }
        if ($required_role && !self::hasRole($required_role)) {
            // Podrías redirigir a una página de "Acceso Denegado" o al dashboard
            header("Location: /elangel_medical_center/admin/dashboard"); // O alguna página de error
            exit();
        }
    }
}
?>
