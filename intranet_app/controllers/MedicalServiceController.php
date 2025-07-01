<?php
require_once __DIR__ . '/../Core/BaseIntranetController.php';
require_once __DIR__ . '/../Core/Auth.php';
require_once __DIR__ . '/../models/MedicalService.php';
require_once __DIR__ . '/../models/Doctor.php'; // Para listar doctores disponibles
require_once __DIR__ . '/../models/ServiceDoctorIntranet.php'; // Para gestionar asociaciones
require_once __DIR__ . '/../models/AuditLog.php';

class MedicalServiceController extends BaseIntranetController {
    private $medicalServiceModel;
    private $doctorModel;
    private $serviceDoctorModel;
    private $auditLogModel;
    private $baseUrl = '/elangel_medical_center/admin';

    public function __construct() {
        $this->medicalServiceModel = new MedicalService();
        $this->doctorModel = new Doctor();
        $this->serviceDoctorModel = new ServiceDoctorIntranet();
        $this->auditLogModel = new AuditLog();
    }

    /**
     * Muestra la página principal de gestión de servicios.
     * Solo accesible para administradores.
     */
    public function index() {
        Auth::requireLogin($this->baseUrl . '/login', 'admin'); // Solo admin puede acceder
        
        $services = $this->medicalServiceModel->getAll();
        $allDoctors = $this->doctorModel->getAll(); // Necesario para la lista de doctores en el modal

        $this->render('medical_services/index.php', [
            'services' => $services,
            'allDoctors' => $allDoctors,
            'flash_message' => $this->getFlashMessage(),
            'user' => Auth::user()
        ]);
    }

    /**
     * Procesa la creación de un nuevo servicio.
     * Solo accesible para administradores.
     */
    public function create() {
        Auth::requireLogin($this->baseUrl . '/login', 'admin');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $base_price = filter_var($_POST['base_price'] ?? 0, FILTER_VALIDATE_FLOAT);
            $description = trim($_POST['description'] ?? '');
            $is_active = isset($_POST['is_active']) ? 1 : 0;
            $associated_doctors = json_decode($_POST['associated_doctors'] ?? '[]', true); // IDs de doctores

            if (empty($name) || $base_price === false || $base_price < 0) {
                $this->setFlashMessage('error', 'Nombre y precio base válidos son requeridos para el servicio.');
                $this->redirect($this->baseUrl . '/servicios');
                return;
            }

            $serviceData = [
                'name' => $name,
                'base_price' => $base_price,
                'description' => $description,
                'is_active' => $is_active
            ];

            $newServiceId = $this->medicalServiceModel->create($serviceData);

            if ($newServiceId) {
                // Asociar doctores
                foreach ($associated_doctors as $doctor_id) {
                    $this->serviceDoctorModel->createAssociation($newServiceId, $doctor_id);
                }
                $this->setFlashMessage('success', 'Servicio registrado exitosamente.');
                $this->auditLogModel->logAction(Auth::user()['id'], 'medical_service_created', "Servicio '{$name}' registrado.");
            } else {
                $this->setFlashMessage('error', 'Error al registrar el servicio.');
            }
            $this->redirect($this->baseUrl . '/servicios');
        } else {
            $this->redirect($this->baseUrl . '/servicios');
        }
    }

    /**
     * Procesa la actualización de un servicio existente.
     * Solo accesible para administradores.
     * @param int $id ID del servicio a actualizar.
     */
    public function update($id) {
        Auth::requireLogin($this->baseUrl . '/login', 'admin');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $base_price = filter_var($_POST['base_price'] ?? 0, FILTER_VALIDATE_FLOAT);
            $description = trim($_POST['description'] ?? '');
            $is_active = isset($_POST['is_active']) ? 1 : 0;
            $associated_doctors = json_decode($_POST['associated_doctors'] ?? '[]', true); // IDs de doctores

            if (empty($name) || $base_price === false || $base_price < 0) {
                $this->setFlashMessage('error', 'Nombre y precio base válidos son requeridos para el servicio.');
                $this->redirect($this->baseUrl . '/servicios');
                return;
            }

            $serviceData = [
                'name' => $name,
                'base_price' => $base_price,
                'description' => $description,
                'is_active' => $is_active
            ];

            if ($this->medicalServiceModel->update($id, $serviceData)) {
                // Actualizar asociaciones de doctores
                $currentAssociatedDoctorIds = $this->serviceDoctorModel->getAssociatedDoctorIds($id);

                $doctorsToAdd = array_diff($associated_doctors, $currentAssociatedDoctorIds);
                $doctorsToRemove = array_diff($currentAssociatedDoctorIds, $associated_doctors);

                foreach ($doctorsToAdd as $doctor_id) {
                    $this->serviceDoctorModel->createAssociation($id, $doctor_id);
                }
                foreach ($doctorsToRemove as $doctor_id) {
                    $this->serviceDoctorModel->deleteAssociation($id, $doctor_id);
                }

                $this->setFlashMessage('success', 'Servicio actualizado exitosamente.');
                $this->auditLogModel->logAction(Auth::user()['id'], 'medical_service_updated', "Servicio '{$name}' (ID: {$id}) actualizado.");
            } else {
                $this->setFlashMessage('error', 'Error al actualizar el servicio.');
            }
            $this->redirect($this->baseUrl . '/servicios');
        } else {
            $this->redirect($this->baseUrl . '/servicios');
        }
    }

    /**
     * Cambia el estado activo/inactivo de un servicio.
     * Solo accesible para administradores.
     * @param int $id ID del servicio.
     */
    public function toggleStatus($id) {
        Auth::requireLogin($this->baseUrl . '/login', 'admin');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $service = $this->medicalServiceModel->findById($id);
            if (!$service) {
                $this->setFlashMessage('error', 'Servicio no encontrado.');
                $this->redirect($this->baseUrl . '/servicios');
                return;
            }

            $newStatus = !$service['is_active']; // Invertir el estado actual

            if ($this->medicalServiceModel->toggleActiveStatus($id, $newStatus)) {
                $statusText = $newStatus ? 'activado' : 'desactivado';
                $this->setFlashMessage('success', "Servicio '{$service['name']}' {$statusText} exitosamente.");
                $this->auditLogModel->logAction(Auth::user()['id'], 'medical_service_status_toggled', "Servicio '{$service['name']}' (ID: {$id}) {$statusText}.");
            } else {
                $this->setFlashMessage('error', 'Error al cambiar el estado del servicio.');
            }
            $this->redirect($this->baseUrl . '/servicios');
        } else {
            $this->redirect($this->baseUrl . '/servicios');
        }
    }

    /**
     * API endpoint para obtener los detalles de un servicio (para el modal de edición).
     * @param int $id ID del servicio.
     */
    public function getServiceDetails($id) {
        Auth::requireLogin($this->baseUrl . '/login', 'admin');
        header('Content-Type: application/json');

        $service = $this->medicalServiceModel->findById($id);
        if ($service) {
            $service['associated_doctors'] = $this->serviceDoctorModel->getAssociatedDoctorIds($id);
        }
        echo json_encode($service);
        exit;
    }
}
?>
