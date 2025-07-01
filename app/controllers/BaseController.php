<?php
class BaseController {
    /**
     * Incluye un archivo de vista, pasando datos a la misma.
     * Los datos pasados serán accesibles como variables en la vista.
     * @param string $viewPath La ruta del archivo de vista (e.g., 'home.php').
     * @param array $data Un array asociativo de datos a pasar a la vista.
     */
    protected function render($viewPath, $data = []) {
        // Extrae las claves del array $data como variables individuales
        extract($data);
        require_once __DIR__ . '/../views/partials/header.php'; // Incluye el encabezado
        require_once __DIR__ . '/../views/' . $viewPath; // Incluye la vista principal
        require_once __DIR__ . '/../views/partials/footer.php'; // Incluye el pie de página
    }

    /**
     * Redirige a una URL específica.
     * @param string $url La URL a la que redirigir.
     */
    protected function redirect($url) {
        header("Location: " . $url);
        exit();
    }
}
?>