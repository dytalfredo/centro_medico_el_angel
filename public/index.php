<?php
session_start();

// Definir la URL base de tu aplicación pública
// Ahora, si accedes a tu sitio web como http://localhost/elangel_medical_center/
// entonces BASE_URL debería ser '/elangel_medical_center'.
define('BASE_URL', '/elangel_medical_center');
define('INT_BASE_URL', '/elangel_medical_center/admin'); // URL para la intranet

require_once __DIR__ . '/../app/config/database.php';
require_once __DIR__ . '/../app/models/Database.php';
require_once __DIR__ . '/../app/models/Page.php';
require_once __DIR__ . '/../app/models/Setting.php';
require_once __DIR__ . '/../app/models/Service.php';
require_once __DIR__ . '/../app/models/Testimonial.php';
require_once __DIR__ . '/../app/models/FAQ.php';
require_once __DIR__ . '/../app/models/SliderImage.php';

require_once __DIR__ . '/../app/controllers/BaseController.php';
require_once __DIR__ . '/../app/controllers/PageController.php';

$pageController = new PageController();

$requestUri = $_SERVER['REQUEST_URI'];

// Elimina el BASE_URL del inicio de la URI para obtener la ruta "limpia"
// Asegúrate de que $requestUri comience con BASE_URL antes de cortar.
if (strpos($requestUri, BASE_URL) === 0) {
    $requestUri = substr($requestUri, strlen(BASE_URL));
}

$requestUri = strtok($requestUri, '?'); // Elimina cualquier query string

switch ($requestUri) {
    case '/':
    case '/home':
        $currentPage = 'home';
        $pageController->home();
        break;
    case '/quienes-somos':
        $currentPage = 'quienes-somos';
        $pageController->about();
        break;
    case '/servicios':
        $currentPage = 'servicios';
        $pageController->services();
        break;
    case '/contacto':
        $currentPage = 'contacto';
        $pageController->contact();
        break;
    case '/faq':
        $currentPage = 'faq';
        $pageController->faq();
        break;
    case '/submit-contact':
        $pageController->submitContactForm();
        break;
    default:
        header("HTTP/1.0 404 Not Found");
        echo "<h1>404 Página No Encontrada2</h1>";
        break;
}
?>
