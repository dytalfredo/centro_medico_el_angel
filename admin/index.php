<?php
// START DEBUGGING LINES (REMOVE IN PRODUCTION)
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
// END DEBUGGING LINES

session_start(); // Inicia la sesión

// Incluye la configuración de la base de datos de la intranet
require_once __DIR__ . '/../intranet_app/config/intranet_database.php';

// Incluye las clases base y de modelos/controladores de la intranet
require_once __DIR__ . '/../intranet_app/Core/IntranetDatabase.php';
require_once __DIR__ . '/../intranet_app/Core/Auth.php';
require_once __DIR__ . '/../intranet_app/Core/BaseIntranetController.php';
// require_once __DIR__ . '/../intranet_app/Core/PublicWebDatabase.php'; // Conexión a la DB de la web pública (No necesaria en admin/index.php)

// Modelos de la Intranet
require_once __DIR__ . '/../intranet_app/models/AuditLog.php';
require_once __DIR__ . '/../intranet_app/models/Doctor.php'; // Modelo de doctor de la Intranet
require_once __DIR__ . '/../intranet_app/models/Patient.php';
require_once __DIR__ . '/../intranet_app/models/MedicalService.php'; // Modelo de servicio de la Intranet
require_once __DIR__ . '/../intranet_app/models/Invoice.php';
require_once __DIR__ . '/../intranet_app/models/InvoiceItem.php';
require_once __DIR__ . '/../intranet_app/models/DoctorPayment.php';
require_once __DIR__ . '/../intranet_app/models/ServiceDoctorIntranet.php'; // Modelo de asociación de la Intranet
require_once __DIR__ . '/../intranet_app/models/GeneralConfig.php';
require_once __DIR__ . '/../intranet_app/models/User.php'; // Incluir el modelo User

// Modelos para el contenido de la web pública (solo se incluyen si se gestionan desde la intranet)
require_once __DIR__ . '/../intranet_app/models/PublicWeb/Setting.php';
require_once __DIR__ . '/../intranet_app/models/PublicWeb/Doctor.php';
require_once __DIR__ . '/../intranet_app/models/PublicWeb/Faq.php';
require_once __DIR__ . '/../intranet_app/models/PublicWeb/Page.php';
require_once __DIR__ . '/../intranet_app/models/PublicWeb/Service.php';
require_once __DIR__ . '/../intranet_app/models/PublicWeb/SliderImage.php';
require_once __DIR__ . '/../intranet_app/models/PublicWeb/Testimonial.php';
require_once __DIR__ . '/../intranet_app/models/PublicWeb/ServiceDoctor.php';


// Controladores
require_once __DIR__ . '/../intranet_app/controllers/AuthController.php';
require_once __DIR__ . '/../intranet_app/controllers/DashboardController.php';
require_once __DIR__ . '/../intranet_app/controllers/InvoiceController.php';
require_once __DIR__ . '/../intranet_app/controllers/DoctorController.php';
require_once __DIR__ . '/../intranet_app/controllers/MedicalServiceController.php';
require_once __DIR__ . '/../intranet_app/controllers/ConfigController.php';
require_once __DIR__ . '/../intranet_app/controllers/WebConfigController.php';
require_once __DIR__ . '/../intranet_app/controllers/UserController.php'; 
require_once __DIR__ . '/../intranet_app/controllers/ReportController.php';
// Incluir el nuevo controlador de usuarios


// Instancia los controladores
$authController = new AuthController();
$dashboardController = new DashboardController();
$invoiceController = new InvoiceController();
$doctorController = new DoctorController();
$medicalServiceController = new MedicalServiceController();
$configController = new ConfigController();
$webConfigController = new WebConfigController();
$userController = new UserController(); 
$reportController = new ReportController();// Instanciar el controlador de usuarios


// Define la base de la URL para el enrutamiento de la intranet
$basePath = '/elangel_medical_center/admin';

// Obtiene la URI de la solicitud y la limpia
$requestUri = $_SERVER['REQUEST_URI'];
$cleanUri = '';
if (strpos($requestUri, $basePath) === 0) {
    $cleanUri = substr($requestUri, strlen($basePath));
} else {
    $cleanUri = strtok($requestUri, '?');
}

$cleanUri = strtok($cleanUri, '?');
$cleanUri = rtrim($cleanUri, '/');
if (empty($cleanUri)) {
    $cleanUri = '/';
}

// Enrutamiento de la Intranet
switch ($cleanUri) {
    case '/login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $authController->login();
        } else {
            $authController->showLoginForm();
        }
        break;
    case '/logout':
        $authController->logout();
        break;
    case '/':
    case '/dashboard':
        $dashboardController->index();
        break;

    // Rutas para Facturas
    case '/facturas':
        $invoiceController->index();
        break;
    case '/facturas/create':
        $invoiceController->create();
        break;
    case (preg_match('/^\/facturas\/update\/(\d+)$/', $cleanUri, $matches) ? true : false):
        $invoiceId = $matches[1];
        $invoiceController->update($invoiceId);
        break;
    case (preg_match('/^\/facturas\/delete\/(\d+)$/', $cleanUri, $matches) ? true : false):
        $invoiceId = $matches[1];
        $invoiceController->delete($invoiceId);
        break;

    // Rutas para Gestión de Usuarios
    case '/usuarios':
        $userController->index();
        break;
    case '/usuarios/create':
        $userController->create();
        break;
    case (preg_match('/^\/usuarios\/update\/(\d+)$/', $cleanUri, $matches) ? true : false):
        $userId = $matches[1];
        $userController->update($userId);
        break;
    case (preg_match('/^\/usuarios\/delete\/(\d+)$/', $cleanUri, $matches) ? true : false):
        $userId = $matches[1];
        $userController->delete($userId);
        break;

    // Rutas para Médicos (Intranet)
    case '/medicos':
        $doctorController->index();
        break;
    case '/medicos/create':
        $doctorController->create();
        break;
    case (preg_match('/^\/medicos\/update\/(\d+)$/', $cleanUri, $matches) ? true : false):
        $doctorId = $matches[1];
        $doctorController->update($doctorId);
        break;
    case (preg_match('/^\/medicos\/toggle-status\/(\d+)$/', $cleanUri, $matches) ? true : false):
        $doctorId = $matches[1];
        $doctorController->toggleStatus($doctorId);
        break;

    // Rutas para Servicios Médicos (Intranet)
    case '/servicios':
        $medicalServiceController->index();
        break;
    case '/servicios/create':
        $medicalServiceController->create();
        break;
    case (preg_match('/^\/servicios\/update\/(\d+)$/', $cleanUri, $matches) ? true : false):
        $serviceId = $matches[1];
        $medicalServiceController->update($serviceId);
        break;
    case (preg_match('/^\/servicios\/toggle-status\/(\d+)$/', $cleanUri, $matches) ? true : false):
        $serviceId = $matches[1];
        $medicalServiceController->toggleStatus($serviceId);
        break;
    
    // Rutas para Reportes (NUEVAS)
    case '/reportes':
        $reportController->index();
        break;
    case '/reportes/generar-pdf':
        $reportController->generatePdfReport();
        break;

    // Rutas para Configuración de la Intranet (Base de Datos de la Intranet)
    case '/configuracion':
        $configController->index();
        break;
    case '/configuracion/update':
        $configController->update();
        break;
    
    // Rutas para Configuración de Contenido de la Web Pública
    case (preg_match('/^\/configuracion-web(\/(settings|doctors|faqs|pages|services|slider-images|testimonials|service-doctor))?$/', $cleanUri, $matches) ? true : false):
        $section = $matches[2] ?? 'settings'; // Default a 'settings' si no se especifica sección
        $webConfigController->index($section);
        break;
    case '/configuracion-web/update-settings':
        $webConfigController->updateSettings();
        break;
    // Rutas para acciones específicas por sección (CRUD para cada tabla de la web pública)
    case '/configuracion-web/doctors/create':
        $webConfigController->createDoctor();
        break;
    case (preg_match('/^\/configuracion-web\/doctors\/update\/(\d+)$/', $cleanUri, $matches) ? true : false):
        $id = $matches[1];
        $webConfigController->updateDoctor($id);
        break;
    case (preg_match('/^\/configuracion-web\/doctors\/delete\/(\d+)$/', $cleanUri, $matches) ? true : false):
        $id = $matches[1];
        $webConfigController->deleteDoctor($id);
        break;

    case '/configuracion-web/faqs/create':
        $webConfigController->createFaq();
        break;
    case (preg_match('/^\/configuracion-web\/faqs\/update\/(\d+)$/', $cleanUri, $matches) ? true : false):
        $id = $matches[1];
        $webConfigController->updateFaq($id);
        break;
    case (preg_match('/^\/configuracion-web\/faqs\/delete\/(\d+)$/', $cleanUri, $matches) ? true : false):
        $id = $matches[1];
        $webConfigController->deleteFaq($id);
        break;

    case '/configuracion-web/pages/create':
        $webConfigController->createPage();
        break;
    case (preg_match('/^\/configuracion-web\/pages\/update\/(\d+)$/', $cleanUri, $matches) ? true : false):
        $id = $matches[1];
        $webConfigController->updatePage($id);
        break;
    case (preg_match('/^\/configuracion-web\/pages\/delete\/(\d+)$/', $cleanUri, $matches) ? true : false):
        $id = $matches[1];
        $webConfigController->deletePage($id);
        break;

    case '/configuracion-web/services/create':
        $webConfigController->createService();
        break;
    case (preg_match('/^\/configuracion-web\/services\/update\/(\d+)$/', $cleanUri, $matches) ? true : false):
        $id = $matches[1];
        $webConfigController->updateService($id);
        break;
    case (preg_match('/^\/configuracion-web\/services\/delete\/(\d+)$/', $cleanUri, $matches) ? true : false):
        $id = $matches[1];
        $webConfigController->deleteService($id);
        break;
    case (preg_match('/^\/configuracion-web\/services\/manage-doctors\/(\d+)$/', $cleanUri, $matches) ? true : false):
        $id = $matches[1];
        $webConfigController->manageServiceDoctors($id);
        break; // Nueva ruta para gestionar doctores de un servicio público

    case '/configuracion-web/slider-images/create':
        $webConfigController->createSliderImage();
        break;
    case (preg_match('/^\/configuracion-web\/slider-images\/update\/(\d+)$/', $cleanUri, $matches) ? true : false):
        $id = $matches[1];
        $webConfigController->updateSliderImage($id);
        break;
    case (preg_match('/^\/configuracion-web\/slider-images\/delete\/(\d+)$/', $cleanUri, $matches) ? true : false):
        $id = $matches[1];
        $webConfigController->deleteSliderImage($id);
        break;

    case '/configuracion-web/testimonials/create':
        $webConfigController->createTestimonial();
        break;
    case (preg_match('/^\/configuracion-web\/testimonials\/update\/(\d+)$/', $cleanUri, $matches) ? true : false):
        $id = $matches[1];
        $webConfigController->updateTestimonial($id);
        break;
    case (preg_match('/^\/configuracion-web\/testimonials\/delete\/(\d+)$/', $cleanUri, $matches) ? true : false):
        $id = $matches[1];
        $webConfigController->deleteTestimonial($id);
        break;


    // API Endpoints (para AJAX)
    case '/api/patients/search':
        $invoiceController->searchPatients();
        break;
    case '/api/patients/create':
        $invoiceController->createPatient();
        break;
    case '/api/services/details':
        $invoiceController->getServiceDetails();
        break;
    case '/api/doctors/for-service':
        $invoiceController->getDoctorsForService();
        break;
    case (preg_match('/^\/api\/facturas\/(\d+)$/', $cleanUri, $matches) ? true : false):
        $invoiceId = $matches[1];
        $invoice = (new Invoice())->findById($invoiceId);
        header('Content-Type: application/json');
        echo json_encode($invoice);
        exit;
    case (preg_match('/^\/api\/facturas\/(\d+)\/items$/', $cleanUri, $matches) ? true : false):
        $invoiceId = $matches[1];
        $items = (new Invoice())->getInvoiceItems($invoiceId);
        header('Content-Type: application/json');
        echo json_encode($items);
        exit;
    case (preg_match('/^\/api\/doctors\/details\/(\d+)$/', $cleanUri, $matches) ? true : false):
        $doctorId = $matches[1];
        $doctorController->getDoctorDetails($doctorId);
        break;
    case (preg_match('/^\/api\/servicios\/details\/(\d+)$/', $cleanUri, $matches) ? true : false):
        $serviceId = $matches[1];
        $medicalServiceController->getServiceDetails($serviceId);
        break;
    case (preg_match('/^\/api\/usuarios\/(\d+)$/', $cleanUri, $matches) ? true : false):
        $userId = $matches[1];
        $userController->getUserDetails($userId);
        break;
    // API para WebConfigController
    case (preg_match('/^\/api\/web-config\/doctors\/(\d+)$/', $cleanUri, $matches) ? true : false):
        $id = $matches[1];
        $webConfigController->getDoctorDetailsApi($id);
        break;
    case (preg_match('/^\/api\/web-config\/faqs\/(\d+)$/', $cleanUri, $matches) ? true : false):
        $id = $matches[1];
        $webConfigController->getFaqDetailsApi($id);
        break;
    case (preg_match('/^\/api\/web-config\/pages\/(\d+)$/', $cleanUri, $matches) ? true : false):
        $id = $matches[1];
        $webConfigController->getPageDetailsApi($id);
        break;
    case (preg_match('/^\/api\/web-config\/services\/(\d+)$/', $cleanUri, $matches) ? true : false):
        $id = $matches[1];
        $webConfigController->getServiceDetailsApi($id);
        break;
    case (preg_match('/^\/api\/web-config\/slider-images\/(\d+)$/', $cleanUri, $matches) ? true : false):
        $id = $matches[1];
        $webConfigController->getSliderImageDetailsApi($id);
        break;
    case (preg_match('/^\/api\/web-config\/testimonials\/(\d+)$/', $cleanUri, $matches) ? true : false):
        $id = $matches[1];
        $webConfigController->getTestimonialDetailsApi($id);
        break;
    case (preg_match('/^\/api\/web-config\/service-doctors\/(\d+)$/', $cleanUri, $matches) ? true : false):
        $serviceId = $matches[1];
        $webConfigController->getServiceAssociatedDoctorsApi($serviceId);
        break;

    default:
        header("HTTP/1.0 404 Not Found");
        echo "<h1>404 Intranet: Página No Encontrada</h1>";
        break;
}

?>