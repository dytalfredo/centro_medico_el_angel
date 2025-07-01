<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/Page.php';
require_once __DIR__ . '/../models/Setting.php';
require_once __DIR__ . '/../models/Testimonial.php';
require_once __DIR__ . '/../models/FAQ.php';
require_once __DIR__ . '/../models/Service.php';
require_once __DIR__ . '/../models/SliderImage.php';

class PageController extends BaseController {
    private $pageModel;
    private $settingModel;
    private $testimonialModel;
    private $faqModel;
    private $serviceModel;
    private $sliderImageModel;

    public function __construct() {
        $this->pageModel = new Page();
        $this->settingModel = new Setting();
        $this->testimonialModel = new Testimonial();
        $this->faqModel = new FAQ();
        $this->serviceModel = new Service();
        $this->sliderImageModel = new SliderImage();
    }

    public function home() {
        $mission = $this->pageModel->getContent('home_mission');
        $testimonials = $this->testimonialModel->getAll();
        $sliderImages = $this->sliderImageModel->getAll();

        $this->render('home.php', [
            'mission' => $mission,
            'testimonials' => $testimonials,
            'sliderImages' => $sliderImages
        ]);
    }

    public function about() {
        $mission = $this->pageModel->getContent('about_mission');
        $vision = $this->pageModel->getContent('about_vision');

        $this->render('quienes-somos.php', [
            'mission' => $mission,
            'vision' => $vision
        ]);
    }

    public function contact() {
        $phone = $this->settingModel->getValue('contact_phone');
        $email = $this->settingModel->getValue('contact_email');
        $address = $this->settingModel->getValue('contact_address');
        $googleMapsSrc = $this->settingModel->getValue('google_maps_iframe_src');

        $this->render('contacto.php', [
            'phone' => $phone,
            'email' => $email,
            'address' => $address,
            'googleMapsSrc' => $googleMapsSrc
        ]);
    }

    public function faq() {
        $faqs = $this->faqModel->getAll();

        $this->render('faq.php', [
            'faqs' => $faqs
        ]);
    }

    public function services() {
        $servicesAndDoctors = $this->serviceModel->getAllWithDoctors();

        $this->render('services.php', [
            'servicesAndDoctors' => $servicesAndDoctors
        ]);
    }

    // Método para manejar el envío del formulario de contacto
    public function submitContactForm() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = htmlspecialchars($_POST['name'] ?? '');
            $email = htmlspecialchars($_POST['email'] ?? '');
            $message = htmlspecialchars($_POST['message'] ?? '');

            // Aquí puedes agregar la lógica para enviar un correo electrónico
            // o guardar el mensaje en una base de datos.
            // Por simplicidad, solo mostraremos un mensaje de éxito.

            // Ejemplo de envío de correo (requiere configuración del servidor PHP)
            /*
            $to = $this->settingModel->getValue('contact_email');
            $subject = "Mensaje desde el sitio web de El Ángel";
            $headers = "From: " . $email . "\r\n" .
                       "Reply-To: " . $email . "\r\n" .
                       "Content-Type: text/html; charset=UTF-8";
            $email_body = "<h3>Nuevo Mensaje de Contacto</h3>
                           <p><strong>Nombre:</strong> {$name}</p>
                           <p><strong>Email:</strong> {$email}</p>
                           <p><strong>Mensaje:</strong><br>{$message}</p>";

            if (mail($to, $subject, $email_body, $headers)) {
                $_SESSION['form_message'] = '¡Tu mensaje ha sido enviado con éxito!';
            } else {
                $_SESSION['form_message'] = 'Hubo un error al enviar tu mensaje. Por favor, inténtalo de nuevo.';
            }
            */

            $_SESSION['form_message'] = '¡Tu mensaje ha sido recibido! (Funcionalidad de envío real deshabilitada para demostración)';
            $this->redirect('/contacto'); // Redirige de vuelta a la página de contacto
        } else {
            $this->redirect('/'); // Redirige a la página de inicio si no es un POST
        }
    }
}
?>
