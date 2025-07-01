<?php
// Incluye la clase de autenticación para uso en controladores
require_once __DIR__ . '/Auth.php';

class BaseIntranetController {
    /**
     * Incluye un archivo de vista, pasando datos a la misma.
     * Los datos pasados serán accesibles como variables en la vista.
     * @param string $viewPath La ruta del archivo de vista (e.g., 'auth/login.php').
     * @param array $data Un array asociativo de datos a pasar a la vista.
     * @param string $layout 'default' (con header/sidebar/footer) o 'blank' (sin ellos, para login).
     */
    protected function render($viewPath, $data = [], $layout = 'default') {
        extract($data); // Extrae las claves del array $data como variables individuales

        if ($layout === 'default') {
            require_once __DIR__ . '/../views/partials/intranet_header.php';
            require_once __DIR__ . '/../views/partials/intranet_sidebar.php';
        }

        require_once __DIR__ . '/../views/' . $viewPath;

        if ($layout === 'default') {
            require_once __DIR__ . '/../views/partials/intranet_footer.php';
        }
    }

    /**
     * Redirige a una URL específica.
     * @param string $url La URL a la que redirigir.
     */
    protected function redirect($url) {
        header("Location: " . $url);
        exit();
    }

    /**
     * Establece un mensaje flash en la sesión.
     * @param string $type Tipo de mensaje (e.g., 'success', 'error', 'info').
     * @param string $message Contenido del mensaje.
     */
    protected function setFlashMessage($type, $message) {
        $_SESSION['flash_message'] = ['type' => $type, 'message' => $message];
    }

    /**
     * Obtiene y limpia un mensaje flash de la sesión.
     * @return array|null
     */
    protected function getFlashMessage() {
        if (isset($_SESSION['flash_message'])) {
            $message = $_SESSION['flash_message'];
            unset($_SESSION['flash_message']);
            return $message;
        }
        return null;
    }
}
?>