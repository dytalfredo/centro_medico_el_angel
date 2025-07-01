<?php
require_once __DIR__ . '/../Core/BaseIntranetController.php';
require_once __DIR__ . '/../Core/Auth.php';
require_once __DIR__ . '/../models/Doctor.php';
require_once __DIR__ . '/../models/AuditLog.php';

class DoctorController extends BaseIntranetController {
    private $doctorModel;
    private $auditLogModel;
    private $baseUrl = '/elangel_medical_center/admin';

    public function __construct() {
        $this->doctorModel = new Doctor();
        $this->auditLogModel = new AuditLog();
    }

    /**
     * Muestra la página principal de gestión de médicos con filtros.
     * Solo accesible para administradores.
     */
    public function index() {
        Auth::requireLogin($this->baseUrl . '/login', 'admin');
        
        // Capturar parámetros de filtro de la URL (GET)
        $filters = [
            'name' => $_GET['name'] ?? '',
            'specialty' => $_GET['specialty'] ?? '',
            'is_active' => $_GET['is_active'] ?? 'all', // 'all', '1' (activo), '0' (inactivo)
        ];

        $doctors = $this->doctorModel->getAll($filters);

        $this->render('doctors/index.php', [
            'doctors' => $doctors,
            'filters' => $filters, // Pasar los filtros actuales a la vista
            'flash_message' => $this->getFlashMessage(),
            'user' => Auth::user()
        ]);
    }

    /**
     * Procesa la creación de un nuevo médico.
     * Solo accesible para administradores.
     */
    public function create() {
        Auth::requireLogin($this->baseUrl . '/login', 'admin');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $specialty = trim($_POST['specialty'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $fee_percentage = filter_var($_POST['fee_percentage'] ?? 0, FILTER_VALIDATE_FLOAT);
            $is_active = isset($_POST['is_active']) ? 1 : 0;

            if (empty($name) || empty($specialty) || $fee_percentage === false || $fee_percentage < 0) {
                $this->setFlashMessage('error', 'Nombre, especialidad y porcentaje de honorarios válidos son requeridos.');
                $this->redirect($this->baseUrl . '/medicos');
                return;
            }

            $data = [
                'name' => $name,
                'specialty' => $specialty,
                'phone' => $phone,
                'email' => $email,
                'fee_percentage' => $fee_percentage,
                'is_active' => $is_active,
            ];

            if ($this->doctorModel->create($data)) {
                $this->setFlashMessage('success', 'Médico registrado exitosamente.');
                $this->auditLogModel->logAction(Auth::user()['id'], 'doctor_created', "Médico '{$name}' registrado.");
            } else {
                $this->setFlashMessage('error', 'Error al registrar el médico.');
            }
            $this->redirect($this->baseUrl . '/medicos');
        } else {
            $this->redirect($this->baseUrl . '/medicos');
        }
    }

    /**
     * Procesa la actualización de un médico existente.
     * Solo accesible para administradores.
     * @param int $id ID del médico a actualizar.
     */
    public function update($id) {
        Auth::requireLogin($this->baseUrl . '/login', 'admin');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $specialty = trim($_POST['specialty'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $fee_percentage = filter_var($_POST['fee_percentage'] ?? 0, FILTER_VALIDATE_FLOAT);
            $is_active = isset($_POST['is_active']) ? 1 : 0;

            if (empty($name) || empty($specialty) || $fee_percentage === false || $fee_percentage < 0) {
                $this->setFlashMessage('error', 'Nombre, especialidad y porcentaje de honorarios válidos son requeridos.');
                $this->redirect($this->baseUrl . '/medicos');
                return;
            }

            $data = [
                'name' => $name,
                'specialty' => $specialty,
                'phone' => $phone,
                'email' => $email,
                'fee_percentage' => $fee_percentage,
                'is_active' => $is_active,
            ];

            if ($this->doctorModel->update($id, $data)) {
                $this->setFlashMessage('success', 'Médico actualizado exitosamente.');
                $this->auditLogModel->logAction(Auth::user()['id'], 'doctor_updated', "Médico '{$name}' (ID: {$id}) actualizado.");
            } else {
                $this->setFlashMessage('error', 'Error al actualizar el médico.');
            }
            $this->redirect($this->baseUrl . '/medicos');
        } else {
            $this->redirect($this->baseUrl . '/medicos');
        }
    }

    /**
     * Cambia el estado activo/inactivo de un médico.
     * Solo accesible para administradores.
     * @param int $id ID del médico.
     */
    public function toggleStatus($id) {
        Auth::requireLogin($this->baseUrl . '/login', 'admin');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $doctor = $this->doctorModel->findById($id);
            if (!$doctor) {
                $this->setFlashMessage('error', 'Médico no encontrado.');
                $this->redirect($this->baseUrl . '/medicos');
                return;
            }

            $newStatus = !$doctor['is_active'];

            if ($this->doctorModel->toggleActiveStatus($id, $newStatus)) {
                $statusText = $newStatus ? 'activado' : 'desactivado';
                $this->setFlashMessage('success', "Médico '{$doctor['name']}' {$statusText} exitosamente.");
                $this->auditLogModel->logAction(Auth::user()['id'], 'doctor_status_toggled', "Médico '{$doctor['name']}' (ID: {$id}) {$statusText}.");
            } else {
                $this->setFlashMessage('error', 'Error al cambiar el estado del médico.');
            }
            $this->redirect($this->baseUrl . '/medicos');
        } else {
            $this->redirect($this->baseUrl . '/medicos');
        }
    }

    /**
     * API endpoint para obtener los detalles de un médico (para el modal de edición).
     * @param int $id ID del médico.
     */
    public function getDoctorDetails($id) {
        Auth::requireLogin($this->baseUrl . '/login', 'admin');
        header('Content-Type: application/json');

        $doctor = $this->doctorModel->findById($id);
        echo json_encode($doctor);
        exit;
    }
}
