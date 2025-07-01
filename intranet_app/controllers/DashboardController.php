<?php
require_once __DIR__ . '/../Core/BaseIntranetController.php';
require_once __DIR__ . '/../Core/Auth.php';
require_once __DIR__ . '/../models/Invoice.php';
require_once __DIR__ . '/../models/Doctor.php';
require_once __DIR__ . '/../models/MedicalService.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/AuditLog.php';

class DashboardController extends BaseIntranetController {
    private $invoiceModel;
    private $doctorModel;
    private $medicalServiceModel;
    private $userModel;
    private $auditLogModel;
    private $baseUrl = '/elangel_medical_center/admin'; // Definir la base URL

    public function __construct() {
        $this->invoiceModel = new Invoice();
        $this->doctorModel = new Doctor();
        $this->medicalServiceModel = new MedicalService();
        $this->userModel = new User();
        $this->auditLogModel = new AuditLog();
    }

    /**
     * Muestra la página principal del dashboard.
     */
    public function index() {
        Auth::requireLogin($this->baseUrl . '/login'); // Requiere que el usuario esté logueado

        $invoices_pending_count = $this->invoiceModel->countInvoicesByStatus('pendiente');
        $active_doctors_count = $this->doctorModel->countActiveDoctors();
        $active_services_count = $this->medicalServiceModel->countActiveServices();
        $total_users_count = $this->userModel->countUsersByRole(); // Total users (admins + assistants)
        $latest_logs = $this->auditLogModel->getLatest(5); // Last 5 audit logs

        $this->render('dashboard/index.php', [
            'invoices_pending_count' => $invoices_pending_count,
            'active_doctors_count' => $active_doctors_count,
            'active_services_count' => $active_services_count,
            'total_users_count' => $total_users_count,
            'latest_logs' => $latest_logs,
            'flash_message' => $this->getFlashMessage(), // Para mostrar mensajes después de acciones
            'user' => Auth::user() // Pasar datos del usuario logueado a la vista
        ]);
    }
}
?>
