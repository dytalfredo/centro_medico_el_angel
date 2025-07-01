<?php
require_once __DIR__ . '/../Core/IntranetDatabase.php';
require_once __DIR__ . '/ServiceDoctorIntranet.php'; // Asegúrate de incluir el modelo de asociación

class MedicalService {
    private $conn;
    private $table_name = "medical_services";
    private $serviceDoctorModel;

    public function __construct() {
        $db = IntranetDatabase::getInstance();
        $this->conn = $db->getConnection();
        $this->serviceDoctorModel = new ServiceDoctorIntranet();
    }

    /**
     * Obtiene un servicio por su ID.
     * @param int $id
     * @return array|null
     */
    public function findById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 1");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    /**
     * Obtiene todos los servicios.
     * @return array
     */
    public function getAll() {
        $services = [];
        $result = $this->conn->query("SELECT id, name, base_price, description, is_active FROM " . $this->table_name . " ORDER BY name ASC");
        while ($row = $result->fetch_assoc()) {
            $services[] = $row;
        }
        return $services;
    }

    /**
     * Obtiene todos los servicios activos.
     * Este método es usado por el InvoiceController.
     * @return array
     */
    public function getAllActive() {
        $services = [];
        $stmt = $this->conn->prepare("SELECT id, name, base_price FROM " . $this->table_name . " WHERE is_active = 1 ORDER BY name ASC");
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $services[] = $row;
        }
        return $services;
    }

    /**
     * Crea un nuevo servicio médico.
     * @param array $data Datos del servicio (name, base_price, description, is_active).
     * @return int ID del nuevo servicio o false si falla.
     */
    public function create($data) {
        $stmt = $this->conn->prepare("INSERT INTO " . $this->table_name . " (name, base_price, description, is_active) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sdsi",
            $data['name'],
            $data['base_price'],
            $data['description'],
            $data['is_active']
        );
        if ($stmt->execute()) {
            return $stmt->insert_id;
        }
        return false;
    }

    /**
     * Actualiza un servicio médico existente.
     * @param int $id ID del servicio.
     * @param array $data Datos a actualizar.
     * @return bool True si tiene éxito, false si falla.
     */
    public function update($id, $data) {
        $sql = "UPDATE " . $this->table_name . " SET name = ?, base_price = ?, description = ?, is_active = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sdsii",
            $data['name'],
            $data['base_price'],
            $data['description'],
            $data['is_active'],
            $id
        );
        return $stmt->execute();
    }

    /**
     * Activa o desactiva un servicio.
     * @param int $id ID del servicio.
     * @param bool $is_active Nuevo estado.
     * @return bool True si tiene éxito, false si falla.
     */
    public function toggleActiveStatus($id, $is_active) {
        $stmt = $this->conn->prepare("UPDATE " . $this->table_name . " SET is_active = ? WHERE id = ?");
        $stmt->bind_param("ii", $is_active, $id);
        return $stmt->execute();
    }

    /**
     * Obtiene los doctores asociados a un servicio específico.
     * Este método es utilizado por InvoiceController y MedicalServiceController.
     * @param int $service_id
     * @return array
     */
    public function getDoctorsForService($service_id) {
        return $this->serviceDoctorModel->getServiceDoctors($service_id);
    }

    /**
     * Cuenta el número de servicios activos.
     * @return int El número de servicios activos.
     */
    public function countActiveServices() {
        $stmt = $this->conn->prepare("SELECT COUNT(*) as count FROM " . $this->table_name . " WHERE is_active = 1");
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['count'];
    }
}
?>
