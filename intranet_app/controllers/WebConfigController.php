<?php
// intranet_app/controllers/WebConfigController.php
require_once __DIR__ . '/../Core/BaseIntranetController.php';
require_once __DIR__ . '/../Core/Auth.php';
require_once __DIR__ . '/../models/AuditLog.php';

// Modelos para el contenido de la web pública
require_once __DIR__ . '/../models/PublicWeb/Setting.php';
require_once __DIR__ . '/../models/PublicWeb/Doctor.php';
require_once __DIR__ . '/../models/PublicWeb/Faq.php';
require_once __DIR__ . '/../models/PublicWeb/Page.php';
require_once __DIR__ . '/../models/PublicWeb/Service.php';
require_once __DIR__ . '/../models/PublicWeb/SliderImage.php';
require_once __DIR__ . '/../models/PublicWeb/Testimonial.php';
require_once __DIR__ . '/../models/PublicWeb/ServiceDoctor.php'; // Para gestionar asociaciones de servicios/doctores


class WebConfigController extends BaseIntranetController {
    private $settingModel;
    private $doctorModel; // PublicWeb/Doctor
    private $faqModel;
    private $pageModel;
    private $serviceModel; // PublicWeb/Service
    private $sliderImageModel;
    private $testimonialModel;
    private $serviceDoctorModel; // PublicWeb/ServiceDoctor
    private $auditLogModel;
    private $baseUrl = '/elangel_medical_center/admin';

    public function __construct() {
        $this->settingModel = new Setting();
        $this->doctorModel = new PublicWebDoctor();
        $this->faqModel = new Faq();
        $this->pageModel = new Page();
        $this->serviceModel = new Service();
        $this->sliderImageModel = new SliderImage();
        $this->testimonialModel = new Testimonial();
        $this->serviceDoctorModel = new ServiceDoctor();
        $this->auditLogModel = new AuditLog();
    }

    /**
     * Muestra la página de configuración del contenido de la web pública para una sección específica.
     * @param string $section La sección a mostrar (ej. 'settings', 'doctors', 'faqs').
     * Solo accesible para administradores.
     */
    public function index($section = 'settings') {
        Auth::requireLogin($this->baseUrl . '/login', 'admin'); // Solo admin puede acceder

        $data = [
            'section' => $section,
            'flash_message' => $this->getFlashMessage(),
            'user' => Auth::user(),
            // 'current_doctors_public' => [], // Este ya no es necesario pasarlo a la vista principal
            'all_doctors_public' => $this->doctorModel->getAll(), // Obtener todos los doctores públicos para cualquier sección que los necesite (ej. ServiceDoctor associations)
        ];

        switch ($section) {
            case 'settings':
                $data['settings'] = $this->settingModel->getAll();
                break;
            case 'doctors':
                $data['doctors'] = $this->doctorModel->getAll();
                break;
            case 'faqs':
                $data['faqs'] = $this->faqModel->getAll();
                break;
            case 'pages':
                $data['pages'] = $this->pageModel->getAll();
                break;
            case 'services':
                $data['services'] = $this->serviceModel->getAll();
                break;
            case 'slider-images':
                $data['sliderImages'] = $this->sliderImageModel->getAll();
                break;
            case 'testimonials':
                $data['testimonials'] = $this->testimonialModel->getAll();
                break;
            default:
                $data['settings'] = $this->settingModel->getAll(); // Fallback
                $data['section'] = 'settings';
                break;
        }

        $this->render('web_config/index.php', $data);
    }

    // --- Métodos CRUD para Settings ---
    public function updateSettings() {
        Auth::requireLogin($this->baseUrl . '/login', 'admin');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $updatedCount = 0;
            $errors = [];
            foreach ($_POST as $key => $value) {
                if (strpos($key, 'setting_') === 0) {
                    $settingId = (int)str_replace('setting_', '', $key);
                    $settingValue = trim($value);
                    if ($settingId > 0) {
                        if ($this->settingModel->update($settingId, $settingValue)) {
                            $updatedCount++;
                        } else {
                            $errors[] = "Error al actualizar la configuración ID: {$settingId}.";
                        }
                    }
                }
            }
            if (empty($errors) && $updatedCount > 0) {
                $this->setFlashMessage('success', 'Configuraciones de la web actualizadas exitosamente.');
                $this->auditLogModel->logAction(Auth::user()['id'], 'web_settings_updated', "Configuraciones de la web actualizadas por " . Auth::user()['username']);
            } elseif (empty($errors) && $updatedCount === 0) {
                $this->setFlashMessage('info', 'No se realizaron cambios en las configuraciones de la web.');
            } else {
                $errorMessage = "Se actualizaron {$updatedCount} configuraciones, pero ocurrieron errores: " . implode(" ", $errors);
                $this->setFlashMessage('error', $errorMessage);
            }
            $this->redirect($this->baseUrl . '/configuracion-web/settings');
        } else {
            $this->redirect($this->baseUrl . '/configuracion-web/settings');
        }
    }

    // --- Métodos CRUD para Doctors (Web Pública) ---
    public function createDoctor() {
        Auth::requireLogin($this->baseUrl . '/login', 'admin');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            if (empty($name)) {
                $this->setFlashMessage('error', 'El nombre del doctor es requerido.');
            } else {
                if ($this->doctorModel->create($name)) {
                    $this->setFlashMessage('success', 'Doctor de la web registrado exitosamente.');
                    $this->auditLogModel->logAction(Auth::user()['id'], 'web_doctor_created', "Doctor web '{$name}' registrado.");
                } else {
                    $this->setFlashMessage('error', 'Error al registrar el doctor de la web.');
                }
            }
            $this->redirect($this->baseUrl . '/configuracion-web/doctors');
        }
    }

    public function updateDoctor($id) {
        Auth::requireLogin($this->baseUrl . '/login', 'admin');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            if (empty($name)) {
                $this->setFlashMessage('error', 'El nombre del doctor es requerido.');
            } else {
                if ($this->doctorModel->update($id, $name)) {
                    $this->setFlashMessage('success', 'Doctor de la web actualizado exitosamente.');
                    $this->auditLogModel->logAction(Auth::user()['id'], 'web_doctor_updated', "Doctor web '{$name}' (ID: {$id}) actualizado.");
                } else {
                    $this->setFlashMessage('error', 'Error al actualizar el doctor de la web.');
                }
            }
            $this->redirect($this->baseUrl . '/configuracion-web/doctors');
        }
    }

    public function deleteDoctor($id) {
        Auth::requireLogin($this->baseUrl . '/login', 'admin');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $doctor = $this->doctorModel->findById($id);
            if ($doctor && $this->doctorModel->delete($id)) {
                $this->setFlashMessage('success', 'Doctor de la web eliminado exitosamente.');
                $this->auditLogModel->logAction(Auth::user()['id'], 'web_doctor_deleted', "Doctor web '{$doctor['name']}' (ID: {$id}) eliminado.");
            } else {
                $this->setFlashMessage('error', 'Error al eliminar el doctor de la web.');
            }
            $this->redirect($this->baseUrl . '/configuracion-web/doctors');
        }
    }

    // --- Métodos CRUD para FAQs (Web Pública) ---
    public function createFaq() {
        Auth::requireLogin($this->baseUrl . '/login', 'admin');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $question = trim($_POST['question'] ?? '');
            $answer = trim($_POST['answer'] ?? '');
            if (empty($question) || empty($answer)) {
                $this->setFlashMessage('error', 'Pregunta y respuesta son requeridos.');
            } else {
                if ($this->faqModel->create($question, $answer)) {
                    $this->setFlashMessage('success', 'FAQ de la web registrada exitosamente.');
                    $this->auditLogModel->logAction(Auth::user()['id'], 'web_faq_created', "FAQ web '{$question}' registrada.");
                } else {
                    $this->setFlashMessage('error', 'Error al registrar la FAQ de la web.');
                }
            }
            $this->redirect($this->baseUrl . '/configuracion-web/faqs');
        }
    }

    public function updateFaq($id) {
        Auth::requireLogin($this->baseUrl . '/login', 'admin');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $question = trim($_POST['question'] ?? '');
            $answer = trim($_POST['answer'] ?? '');
            if (empty($question) || empty($answer)) {
                $this->setFlashMessage('error', 'Pregunta y respuesta son requeridos.');
            } else {
                if ($this->faqModel->update($id, $question, $answer)) {
                    $this->setFlashMessage('success', 'FAQ de la web actualizada exitosamente.');
                    $this->auditLogModel->logAction(Auth::user()['id'], 'web_faq_updated', "FAQ web (ID: {$id}) actualizada.");
                } else {
                    $this->setFlashMessage('error', 'Error al actualizar la FAQ de la web.');
                }
            }
            $this->redirect($this->baseUrl . '/configuracion-web/faqs');
        }
    }

    public function deleteFaq($id) {
        Auth::requireLogin($this->baseUrl . '/login', 'admin');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->faqModel->delete($id)) {
                $this->setFlashMessage('success', 'FAQ de la web eliminada exitosamente.');
                $this->auditLogModel->logAction(Auth::user()['id'], 'web_faq_deleted', "FAQ web (ID: {$id}) eliminada.");
            } else {
                $this->setFlashMessage('error', 'Error al eliminar la FAQ de la web.');
            }
            $this->redirect($this->baseUrl . '/configuracion-web/faqs');
        }
    }

    // --- Métodos CRUD para Pages (Web Pública) ---
    public function createPage() {
        Auth::requireLogin($this->baseUrl . '/login', 'admin');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $page_key = trim($_POST['page_key'] ?? '');
            $content = trim($_POST['content'] ?? '');
            if (empty($page_key) || empty($content)) {
                $this->setFlashMessage('error', 'Clave de página y contenido son requeridos.');
            } else {
                if ($this->pageModel->create($page_key, $content)) {
                    $this->setFlashMessage('success', 'Página de la web registrada exitosamente.');
                    $this->auditLogModel->logAction(Auth::user()['id'], 'web_page_created', "Página web '{$page_key}' registrada.");
                } else {
                    $this->setFlashMessage('error', 'Error al registrar la página de la web.');
                }
            }
            $this->redirect($this->baseUrl . '/configuracion-web/pages');
        }
    }

    public function updatePage($id) {
        Auth::requireLogin($this->baseUrl . '/login', 'admin');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $content = trim($_POST['content'] ?? '');
            if (empty($content)) {
                $this->setFlashMessage('error', 'El contenido de la página es requerido.');
            } else {
                if ($this->pageModel->update($id, $content)) {
                    $this->setFlashMessage('success', 'Página de la web actualizada exitosamente.');
                    $this->auditLogModel->logAction(Auth::user()['id'], 'web_page_updated', "Página web (ID: {$id}) actualizada.");
                } else {
                    $this->setFlashMessage('error', 'Error al actualizar la página de la web.');
                }
            }
            $this->redirect($this->baseUrl . '/configuracion-web/pages');
        }
    }

    public function deletePage($id) {
        Auth::requireLogin($this->baseUrl . '/login', 'admin');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->pageModel->delete($id)) {
                $this->setFlashMessage('success', 'Página de la web eliminada exitosamente.');
                $this->auditLogModel->logAction(Auth::user()['id'], 'web_page_deleted', "Página web (ID: {$id}) eliminada.");
            } else {
                $this->setFlashMessage('error', 'Error al eliminar la página de la web.');
            }
            $this->redirect($this->baseUrl . '/configuracion-web/pages');
        }
    }

    // --- Métodos CRUD para Services (Web Pública) ---
    public function createService() {
        Auth::requireLogin($this->baseUrl . '/login', 'admin');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');
            if (empty($name)) {
                $this->setFlashMessage('error', 'El nombre del servicio es requerido.');
            } else {
                if ($this->serviceModel->create($name, $description)) {
                    $this->setFlashMessage('success', 'Servicio de la web registrado exitosamente.');
                    $this->auditLogModel->logAction(Auth::user()['id'], 'web_service_created', "Servicio web '{$name}' registrado.");
                } else {
                    $this->setFlashMessage('error', 'Error al registrar el servicio de la web.');
                }
            }
            $this->redirect($this->baseUrl . '/configuracion-web/services');
        }
    }

    public function updateService($id) {
        Auth::requireLogin($this->baseUrl . '/login', 'admin');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');
            if (empty($name)) {
                $this->setFlashMessage('error', 'El nombre del servicio es requerido.');
            } else {
                if ($this->serviceModel->update($id, $name, $description)) {
                    $this->setFlashMessage('success', 'Servicio de la web actualizado exitosamente.');
                    $this->auditLogModel->logAction(Auth::user()['id'], 'web_service_updated', "Servicio web '{$name}' (ID: {$id}) actualizado.");
                } else {
                    $this->setFlashMessage('error', 'Error al actualizar el servicio de la web.');
                }
            }
            $this->redirect($this->baseUrl . '/configuracion-web/services');
        }
    }

    public function deleteService($id) {
        Auth::requireLogin($this->baseUrl . '/login', 'admin');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // También eliminar asociaciones de service_doctor
            $this->serviceDoctorModel->deleteByServiceId($id);
            if ($this->serviceModel->delete($id)) {
                $this->setFlashMessage('success', 'Servicio de la web eliminado exitosamente.');
                $this->auditLogModel->logAction(Auth::user()['id'], 'web_service_deleted', "Servicio web (ID: {$id}) eliminado.");
            } else {
                $this->setFlashMessage('error', 'Error al eliminar el servicio de la web.');
            }
            $this->redirect($this->baseUrl . '/configuracion-web/services');
        }
    }

    // Método para gestionar doctores asociados a un servicio público
    public function manageServiceDoctors($serviceId) {
        Auth::requireLogin($this->baseUrl . '/login', 'admin');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $associated_doctors = json_decode($_POST['associated_doctors'] ?? '[]', true);

            $currentAssociatedDoctorIds = $this->serviceDoctorModel->getAssociatedDoctorIds($serviceId);

            $doctorsToAdd = array_diff($associated_doctors, $currentAssociatedDoctorIds);
            $doctorsToRemove = array_diff($currentAssociatedDoctorIds, $associated_doctors);

            $success = true;
            foreach ($doctorsToAdd as $doctor_id) {
                if (!$this->serviceDoctorModel->createAssociation($serviceId, $doctor_id)) {
                    $success = false;
                }
            }
            foreach ($doctorsToRemove as $doctor_id) {
                if (!$this->serviceDoctorModel->deleteAssociation($serviceId, $doctor_id)) {
                    $success = false;
                }
            }

            if ($success) {
                $this->setFlashMessage('success', 'Asociaciones de doctores para el servicio actualizadas exitosamente.');
                $this->auditLogModel->logAction(Auth::user()['id'], 'web_service_doctors_updated', "Asociaciones de doctores para servicio web (ID: {$serviceId}) actualizadas.");
            } else {
                $this->setFlashMessage('error', 'Error al actualizar las asociaciones de doctores para el servicio.');
            }
            $this->redirect($this->baseUrl . '/configuracion-web/services');
        } else {
            $this->redirect($this->baseUrl . '/configuracion-web/services');
        }
    }


    // --- Métodos CRUD para Slider Images (Web Pública) ---
    public function createSliderImage() {
        Auth::requireLogin($this->baseUrl . '/login', 'admin');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $image_url = trim($_POST['image_url'] ?? '');
            $title = trim($_POST['title'] ?? '');
            $order_display = (int)($_POST['order_display'] ?? 0);
            if (empty($image_url) || empty($title)) {
                $this->setFlashMessage('error', 'URL de imagen y título son requeridos.');
            } else {
                if ($this->sliderImageModel->create($image_url, $title, $order_display)) {
                    $this->setFlashMessage('success', 'Imagen de slider de la web registrada exitosamente.');
                    $this->auditLogModel->logAction(Auth::user()['id'], 'web_slider_image_created', "Imagen de slider web '{$title}' registrada.");
                } else {
                    $this->setFlashMessage('error', 'Error al registrar la imagen de slider de la web.');
                }
            }
            $this->redirect($this->baseUrl . '/configuracion-web/slider-images');
        }
    }

    public function updateSliderImage($id) {
        Auth::requireLogin($this->baseUrl . '/login', 'admin');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $image_url = trim($_POST['image_url'] ?? '');
            $title = trim($_POST['title'] ?? '');
            $order_display = (int)($_POST['order_display'] ?? 0);
            if (empty($image_url) || empty($title)) {
                $this->setFlashMessage('error', 'URL de imagen y título son requeridos.');
            } else {
                if ($this->sliderImageModel->update($id, $image_url, $title, $order_display)) {
                    $this->setFlashMessage('success', 'Imagen de slider de la web actualizada exitosamente.');
                    $this->auditLogModel->logAction(Auth::user()['id'], 'web_slider_image_updated', "Imagen de slider web '{$title}' (ID: {$id}) actualizada.");
                } else {
                    $this->setFlashMessage('error', 'Error al actualizar la imagen de slider de la web.');
                }
            }
            $this->redirect($this->baseUrl . '/configuracion-web/slider-images');
        }
    }

    public function deleteSliderImage($id) {
        Auth::requireLogin($this->baseUrl . '/login', 'admin');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->sliderImageModel->delete($id)) {
                $this->setFlashMessage('success', 'Imagen de slider de la web eliminada exitosamente.');
                $this->auditLogModel->logAction(Auth::user()['id'], 'web_slider_image_deleted', "Imagen de slider web (ID: {$id}) eliminada.");
            } else {
                $this->setFlashMessage('error', 'Error al eliminar la imagen de slider de la web.');
            }
            $this->redirect($this->baseUrl . '/configuracion-web/slider-images');
        }
    }

    // --- Métodos CRUD para Testimonials (Web Pública) ---
    public function createTestimonial() {
        Auth::requireLogin($this->baseUrl . '/login', 'admin');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $quote = trim($_POST['quote'] ?? '');
            $author = trim($_POST['author'] ?? '');
            if (empty($quote) || empty($author)) {
                $this->setFlashMessage('error', 'Cita y autor son requeridos.');
            } else {
                if ($this->testimonialModel->create($quote, $author)) {
                    $this->setFlashMessage('success', 'Testimonio de la web registrado exitosamente.');
                    $this->auditLogModel->logAction(Auth::user()['id'], 'web_testimonial_created', "Testimonio web de '{$author}' registrado.");
                } else {
                    $this->setFlashMessage('error', 'Error al registrar el testimonio de la web.');
                }
            }
            $this->redirect($this->baseUrl . '/configuracion-web/testimonials');
        }
    }

    public function updateTestimonial($id) {
        Auth::requireLogin($this->baseUrl . '/login', 'admin');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $quote = trim($_POST['quote'] ?? '');
            $author = trim($_POST['author'] ?? '');
            if (empty($quote) || empty($author)) {
                $this->setFlashMessage('error', 'Cita y autor son requeridos.');
            } else {
                if ($this->testimonialModel->update($id, $quote, $author)) {
                    $this->setFlashMessage('success', 'Testimonio de la web actualizado exitosamente.');
                    $this->auditLogModel->logAction(Auth::user()['id'], 'web_testimonial_updated', "Testimonio web (ID: {$id}) actualizado.");
                } else {
                    $this->setFlashMessage('error', 'Error al actualizar el testimonio de la web.');
                }
            }
            $this->redirect($this->baseUrl . '/configuracion-web/testimonials');
        }
    }

    public function deleteTestimonial($id) {
        Auth::requireLogin($this->baseUrl . '/login', 'admin');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->testimonialModel->delete($id)) {
                $this->setFlashMessage('success', 'Testimonio de la web eliminado exitosamente.');
                $this->auditLogModel->logAction(Auth::user()['id'], 'web_testimonial_deleted', "Testimonio web (ID: {$id}) eliminada.");
            } else {
                $this->setFlashMessage('error', 'Error al eliminar el testimonio de la web.');
            }
            $this->redirect($this->baseUrl . '/configuracion-web/testimonials');
        }
    }

    // --- API Endpoints para AJAX (Web Pública) ---
    public function getDoctorDetailsApi($id) {
        Auth::requireLogin($this->baseUrl . '/login', 'admin');
        header('Content-Type: application/json');
        $doctor = $this->doctorModel->findById($id);
        echo json_encode($doctor);
        exit;
    }

    public function getFaqDetailsApi($id) {
        Auth::requireLogin($this->baseUrl . '/login', 'admin');
        header('Content-Type: application/json');
        $faq = $this->faqModel->findById($id);
        echo json_encode($faq);
        exit;
    }

    public function getPageDetailsApi($id) {
        Auth::requireLogin($this->baseUrl . '/login', 'admin');
        header('Content-Type: application/json');
        $page = $this->pageModel->findById($id);
        echo json_encode($page);
        exit;
    }

    public function getServiceDetailsApi($id) {
        Auth::requireLogin($this->baseUrl . '/login', 'admin');
        header('Content-Type: application/json');
        $service = $this->serviceModel->findById($id);
        // También obtener los doctores asociados para la edición
        if ($service) {
            $service['associated_doctors'] = $this->serviceDoctorModel->getAssociatedDoctorIds($id);
        }
        echo json_encode($service);
        exit;
    }

    public function getSliderImageDetailsApi($id) {
        Auth::requireLogin($this->baseUrl . '/login', 'admin');
        header('Content-Type: application/json');
        $image = $this->sliderImageModel->findById($id);
        echo json_encode($image);
        exit;
    }

    public function getTestimonialDetailsApi($id) {
        Auth::requireLogin($this->baseUrl . '/login', 'admin');
        header('Content-Type: application/json');
        $testimonial = $this->testimonialModel->findById($id);
        echo json_encode($testimonial);
        exit;
    }

    public function getServiceAssociatedDoctorsApi($serviceId) {
        Auth::requireLogin($this->baseUrl . '/login', 'admin');
        header('Content-Type: application/json');
        $associatedDoctorIds = $this->serviceDoctorModel->getAssociatedDoctorIds($serviceId);
        echo json_encode($associatedDoctorIds);
        exit;
    }
}
