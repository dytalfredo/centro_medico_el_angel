<?php
require_once __DIR__ . '/../Core/BaseIntranetController.php';
require_once __DIR__ . '/../Core/Auth.php';
require_once __DIR__ . '/../models/Invoice.php';
require_once __DIR__ . '/../models/Patient.php';
require_once __DIR__ . '/../models/Doctor.php';
require_once __DIR__ . '/../models/MedicalService.php';
require_once __DIR__ . '/../models/AuditLog.php';

class InvoiceController extends BaseIntranetController {
    private $invoiceModel;
    private $patientModel;
    private $doctorModel;
    private $medicalServiceModel;
    private $auditLogModel;
    private $baseUrl = '/elangel_medical_center/admin'; // Definir la base URL

    public function __construct() {
        $this->invoiceModel = new Invoice();
        $this->patientModel = new Patient();
        $this->doctorModel = new Doctor();
        $this->medicalServiceModel = new MedicalService();
        $this->auditLogModel = new AuditLog();
    }

    /**
     * Muestra la página principal de gestión de facturas.
     */
    public function index() {
        Auth::requireLogin($this->baseUrl . '/login');
        if (!Auth::hasRole('admin') && !Auth::hasRole('assistant')) {
            $this->setFlashMessage('error', 'No tienes permisos para acceder a esta sección.');
            $this->redirect($this->baseUrl . '/dashboard');
        }

        // Capturar parámetros de filtro de la URL (GET)
        $filters = [
            'status' => $_GET['status'] ?? 'all', // 'all', 'pending', 'pagada', 'cancelled'
            'patient_id' => $_GET['patient_id'] ?? null,
            'start_date' => $_GET['start_date'] ?? null,
            'end_date' => $_GET['end_date'] ?? null,
            'patient_name_display' => $_GET['patient_name_display'] ?? '', // Para mostrar el nombre del paciente en el campo
        ];

        // Obtener el nombre del paciente si se envió patient_id (para precargar el campo)
        if ($filters['patient_id']) {
            $patient = $this->patientModel->findById($filters['patient_id']);
            if ($patient) {
                $filters['patient_name_display'] = "{$patient['name']} ({$patient['id_number']})";
            }
        }
        
        // Obtener las facturas filtradas
        $latestInvoices = $this->invoiceModel->getLatest(20, $filters); // Limitar a 20 resultados por página

        $this->render('invoices/index.php', [
            'latestInvoices' => $latestInvoices,
            'filters' => $filters, // Pasar los filtros actuales a la vista
            'flash_message' => $this->getFlashMessage(),
            'user' => Auth::user()
        ]);
    }

    /**
     * Procesa la creación de una nueva factura.
     */
    public function create() {
        Auth::requireLogin($this->baseUrl . '/login');
        if (!Auth::hasRole('admin') && !Auth::hasRole('assistant')) {
            $this->setFlashMessage('error', 'No tienes permisos para crear facturas.');
            $this->redirect($this->baseUrl . '/dashboard');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $patient_id = $_POST['patient_id'] ?? null;
            $invoice_date = $_POST['invoice_date'] ?? date('Y-m-d');
            $total_amount = $_POST['total_amount'] ?? 0.00;
            $status = $_POST['status'] ?? 'pending';
            $payment_method = $_POST['payment_method'] ?? null;
            $exchange_rate = $_POST['exchange_rate'] ?? 1.00; // Capturar la tasa de cambio
            $notes = $_POST['notes'] ?? '';
            $invoice_items_data = json_decode($_POST['invoice_items_json'] ?? '[]', true);

            // Validación: Asegurarse de que haya al menos un ítem y que todos los ítems tengan doctor_id
            if (empty($patient_id) || empty($invoice_items_data) || $total_amount <= 0) {
                $this->setFlashMessage('error', 'Faltan datos requeridos para crear la factura (paciente, servicios o total).');
                $this->redirect($this->baseUrl . '/facturas');
                return;
            }

            foreach ($invoice_items_data as $item) {
                if (empty($item['service_id']) || empty($item['doctor_id']) || $item['quantity'] <= 0 || $item['price_at_invoice'] <= 0) {
                    $this->setFlashMessage('error', 'Todos los ítems de la factura deben tener un servicio, un doctor, cantidad y precio válidos.');
                    $this->redirect($this->baseUrl . '/facturas');
                    return;
                }
            }

            // Validar que el asistente no pueda cambiar a estado 'pagada' o 'cancelada'
            if (Auth::hasRole('assistant') && ($status === 'pagada' || $status === 'cancelada')) {
                $status = 'pending'; // Forzar a 'pending' para asistentes
                $this->setFlashMessage('info', 'Los asistentes solo pueden crear facturas en estado pendiente.');
            }

            // Generar número de factura (puedes tener una lógica más sofisticada)
            $invoice_number = 'INV-' . date('YmdHis'); // Ejemplo: INV-20250611143000

            $invoiceData = [
                'invoice_number' => $invoice_number,
                'patient_id' => $patient_id,
                'invoice_date' => $invoice_date,
                'total_amount' => $total_amount,
                'status' => $status,
                'payment_method' => $payment_method,
                'exchange_rate' => $exchange_rate, // Nuevo campo
                'notes' => $notes,
                'created_by_user_id' => Auth::user()['id']
            ];

            $new_invoice_id = $this->invoiceModel->createInvoice($invoiceData, $invoice_items_data);

            if ($new_invoice_id) {
                $this->setFlashMessage('success', 'Factura creada exitosamente con número: ' . $invoice_number);
                $this->auditLogModel->logAction(Auth::user()['id'], 'invoice_created', "Factura {$invoice_number} creada por " . Auth::user()['username']);
            } else {
                $this->setFlashMessage('error', 'Error al crear la factura.');
            }
            $this->redirect($this->baseUrl . '/facturas');
        } else {
            $this->redirect($this->baseUrl . '/facturas'); // GET request to creation endpoint
        }
    }

    /**
     * Procesa la actualización de una factura existente.
     */
    public function update($id) {
        Auth::requireLogin($this->baseUrl . '/login');
        if (!Auth::hasRole('admin') && !(Auth::hasRole('assistant') && $this->invoiceModel->findById($id)['status'] === 'pending')) {
            $this->setFlashMessage('error', 'No tienes permisos para editar esta factura.');
            $this->redirect($this->baseUrl . '/dashboard');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $invoice_id = $id;
            $patient_id = $_POST['patient_id'] ?? null;
            $invoice_date = $_POST['invoice_date'] ?? date('Y-m-d');
            $total_amount = $_POST['total_amount'] ?? 0.00;
            $status = $_POST['status'] ?? 'pending';
            $payment_method = $_POST['payment_method'] ?? null;
            $exchange_rate = $_POST['exchange_rate'] ?? 1.00; // Capturar la tasa de cambio
            $notes = $_POST['notes'] ?? '';
            $invoice_items_data = json_decode($_POST['invoice_items_json'] ?? '[]', true);

            $original_invoice = $this->invoiceModel->findById($invoice_id);
            if (!$original_invoice) {
                 $this->setFlashMessage('error', 'Factura no encontrada para actualizar.');
                 $this->redirect($this->baseUrl . '/facturas');
                 return;
            }

            // Validación: Asegurarse de que haya al menos un ítem y que todos los ítems tengan doctor_id
            if (empty($patient_id) || empty($invoice_items_data) || $total_amount <= 0) {
                $this->setFlashMessage('error', 'Faltan datos requeridos para actualizar la factura (paciente, servicios o total).');
                $this->redirect($this->baseUrl . '/facturas');
                return;
            }

            foreach ($invoice_items_data as $item) {
                if (empty($item['service_id']) || empty($item['doctor_id']) || $item['quantity'] <= 0 || $item['price_at_invoice'] <= 0) {
                    $this->setFlashMessage('error', 'Todos los ítems de la factura deben tener un servicio, un doctor, cantidad y precio válidos.');
                    $this->redirect($this->baseUrl . '/facturas');
                    return;
                }
            }

            // Permisos para asistentes: Solo pueden editar si el estado es 'pending' y no pueden cambiar a 'pagada'/'cancelada'
            if (Auth::hasRole('assistant')) {
                if ($original_invoice['status'] !== 'pending') {
                    $this->setFlashMessage('error', 'Los asistentes solo pueden editar facturas en estado pendiente.');
                    $this->redirect($this->baseUrl . '/facturas');
                    return;
                }
                // Si el asistente intenta cambiar el estado a 'pagada' o 'cancelada', se ignora y se mantiene 'pending'
                if ($status === 'pagada' || $status === 'cancelada') {
                    $status = 'pending';
                    $this->setFlashMessage('info', 'Los asistentes no pueden cambiar el estado a pagada o cancelada.');
                }
            }

            $invoiceData = [
                'invoice_number' => $original_invoice['invoice_number'], // Mantener el número de factura existente
                'patient_id' => $patient_id,
                'invoice_date' => $invoice_date,
                'total_amount' => $total_amount,
                'status' => $status,
                'payment_method' => $payment_method,
                'exchange_rate' => $exchange_rate, // Nuevo campo
                'notes' => $notes
            ];

            if ($this->invoiceModel->updateInvoice($invoice_id, $invoiceData, $invoice_items_data)) {
                $this->setFlashMessage('success', 'Factura actualizada exitosamente.');
                $this->auditLogModel->logAction(Auth::user()['id'], 'invoice_updated', "Factura {$original_invoice['invoice_number']} actualizada por " . Auth::user()['username']);
            } else {
                $this->setFlashMessage('error', 'Error al actualizar la factura.');
            }
            $this->redirect($this->baseUrl . '/facturas');
        } else {
            $this->redirect($this->baseUrl . '/facturas'); // GET request to update endpoint
        }
    }

    /**
     * Procesa la eliminación de una factura. (Solo Admin)
     */
    public function delete($id) {
        Auth::requireLogin($this->baseUrl . '/login', 'admin'); // Solo Admin puede eliminar

        if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Usar POST para la eliminación segura
            $invoice = $this->invoiceModel->findById($id);
            if (!$invoice) {
                $this->setFlashMessage('error', 'Factura no encontrada.');
                $this->redirect($this->baseUrl . '/facturas');
                return;
            }

            if ($this->invoiceModel->delete($id)) {
                $this->setFlashMessage('success', 'Factura ' . $invoice['invoice_number'] . ' eliminada exitosamente.');
                $this->auditLogModel->logAction(Auth::user()['id'], 'invoice_deleted', "Factura {$invoice['invoice_number']} eliminada por " . Auth::user()['username']);
            } else {
                $this->setFlashMessage('error', 'Error al eliminar la factura.');
            }
            $this->redirect($this->baseUrl . '/facturas');
        } else {
            $this->redirect($this->baseUrl . '/facturas'); // GET request to delete endpoint
        }
    }

    /**
     * API endpoint para buscar pacientes (para el autocomplete del modal).
     */
    public function searchPatients() {
        Auth::requireLogin($this->baseUrl . '/login'); // Require login
        header('Content-Type: application/json');

        $query = $_GET['q'] ?? '';
        $patients = $this->patientModel->search($query);
        echo json_encode($patients);
        exit;
    }

    /**
     * API endpoint para crear un nuevo paciente.
     */
    public function createPatient() {
        Auth::requireLogin($this->baseUrl . '/login'); // Ensure only logged-in users can create patients
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);

            $name = trim($data['name'] ?? '');
            $id_number = trim($data['id_number'] ?? '');
            $phone = trim($data['phone'] ?? '');
            $email = trim($data['email'] ?? '');
            $address = trim($data['address'] ?? '');
            $date_of_birth = trim($data['date_of_birth'] ?? '');

            if (empty($name) || empty($id_number) || empty($phone)) {
                echo json_encode(['success' => false, 'message' => 'Nombre, Cédula y Teléfono del paciente son requeridos.']);
                exit;
            }

            // Check if patient with same ID number already exists
            if ($this->patientModel->findByIDNumber($id_number)) {
                echo json_encode(['success' => false, 'message' => 'Ya existe un paciente con esa cédula.']);
                exit;
            }

            $patientData = [
                'name' => $name,
                'id_number' => $id_number,
                'phone' => $phone,
                'email' => $email,
                'address' => $address,
                'date_of_birth' => $date_of_birth
            ];

            $newPatientId = $this->patientModel->create($patientData);

            if ($newPatientId) {
                $newPatient = $this->patientModel->findById($newPatientId);
                $this->auditLogModel->logAction(Auth::user()['id'], 'patient_created', "Nuevo paciente '{$name}' (Cédula: {$id_number}) registrado.");
                echo json_encode(['success' => true, 'message' => 'Paciente registrado exitosamente.', 'patient' => $newPatient]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al registrar el paciente.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
        }
        exit;
    }


    /**
     * API endpoint para obtener detalles de un servicio (para el modal de factura).
     * Si service_id es 'all', devuelve todos los servicios activos.
     * Si es un ID, devuelve los detalles de ese servicio.
     */
    public function getServiceDetails() {
        Auth::requireLogin($this->baseUrl . '/login'); // Require login
        header('Content-Type: application/json');

        $service_id = $_GET['service_id'] ?? null;
        if ($service_id === 'all') {
            $services = $this->medicalServiceModel->getAllActive();
            echo json_encode($services);
        } elseif ($service_id) {
            $service = $this->medicalServiceModel->findById($service_id);
            echo json_encode($service);
        } else {
            echo json_encode(null);
        }
        exit;
    }

    /**
     * API endpoint para obtener doctores asociados a un servicio específico.
     */
    public function getDoctorsForService() {
        Auth::requireLogin($this->baseUrl . '/login');
        header('Content-Type: application/json');

        $service_id = $_GET['service_id'] ?? null;
        if ($service_id) {
            $doctors = $this->medicalServiceModel->getDoctorsForService($service_id);
            echo json_encode($doctors);
        } else {
            echo json_encode([]); // Devolver un array vacío si no hay servicio seleccionado
        }
        exit;
    }

    /**
     * API endpoint para obtener los detalles de una factura por ID.
     * Utilizado en la edición de facturas para cargar el modal.
     * @param int $id ID de la factura.
     */
    public function getDetailsApi($id) {
        Auth::requireLogin($this->baseUrl . '/login');
        header('Content-Type: application/json');

        $invoice = $this->invoiceModel->findById($id);
        echo json_encode($invoice);
        exit;
    }

    /**
     * API endpoint para obtener los ítems de una factura por ID.
     * Utilizado en la edición de facturas para cargar los ítems del modal.
     * @param int $id ID de la factura.
     */
    public function getInvoiceItemsApi($id) {
        Auth::requireLogin($this->baseUrl . '/login');
        header('Content-Type: application/json');

        $items = $this->invoiceModel->getInvoiceItems($id);
        echo json_encode($items);
        exit;
    }
}
