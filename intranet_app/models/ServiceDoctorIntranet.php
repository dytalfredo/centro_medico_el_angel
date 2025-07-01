<?php
require_once __DIR__ . '/../Core/IntranetDatabase.php';

class ServiceDoctorIntranet {
    private $conn;
    private $table_name = "service_doctor_intranet"; // Tabla de unión entre servicios y doctores

    public function __construct() {
        $db = IntranetDatabase::getInstance();
        $this->conn = $db->getConnection();
    }

    /**
     * Crea una asociación entre un servicio y un doctor.
     * @param int $service_id
     * @param int $doctor_id
     * @return bool
     */
    public function createAssociation($service_id, $doctor_id) {
        // Verificar si la asociación ya existe para evitar duplicados
        if ($this->associationExists($service_id, $doctor_id)) {
            return true; // Ya existe, no es un error
        }
        $stmt = $this->conn->prepare("INSERT INTO " . $this->table_name . " (service_id, doctor_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $service_id, $doctor_id);
        return $stmt->execute();
    }

    /**
     * Elimina una asociación específica entre un servicio y un doctor.
     * @param int $service_id
     * @param int $doctor_id
     * @return bool
     */
    public function deleteAssociation($service_id, $doctor_id) {
        $stmt = $this->conn->prepare("DELETE FROM " . $this->table_name . " WHERE service_id = ? AND doctor_id = ?");
        $stmt->bind_param("ii", $service_id, $doctor_id);
        return $stmt->execute();
    }

    /**
     * Elimina todas las asociaciones para un servicio dado.
     * @param int $service_id
     * @return bool
     */
    public function deleteByServiceId($service_id) {
        $stmt = $this->conn->prepare("DELETE FROM " . $this->table_name . " WHERE service_id = ?");
        $stmt->bind_param("i", $service_id);
        return $stmt->execute();
    }

    /**
     * Obtiene los doctores asociados a un servicio específico.
     * @param int $service_id
     * @return array
     */
    public function getServiceDoctors($service_id) {
        $doctors = [];
        $stmt = $this->conn->prepare("
            SELECT d.id, d.name, d.specialty
            FROM " . $this->table_name . " sd
            JOIN doctors d ON sd.doctor_id = d.id
            WHERE sd.service_id = ?
        ");
        $stmt->bind_param("i", $service_id);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $doctors[] = $row;
        }
        return $doctors;
    }

    /**
     * Obtiene los IDs de los doctores asociados a un servicio específico.
     * @param int $service_id
     * @return array Un array de IDs de doctores.
     */
    public function getAssociatedDoctorIds($service_id) {
        $doctorIds = [];
        $stmt = $this->conn->prepare("SELECT doctor_id FROM " . $this->table_name . " WHERE service_id = ?");
        $stmt->bind_param("i", $service_id);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $doctorIds[] = $row['doctor_id'];
        }
        return $doctorIds;
    }

    /**
     * Verifica si una asociación entre un servicio y un doctor ya existe.
     * @param int $service_id
     * @param int $doctor_id
     * @return bool
     */
    public function associationExists($service_id, $doctor_id) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) as count FROM " . $this->table_name . " WHERE service_id = ? AND doctor_id = ?");
        $stmt->bind_param("ii", $service_id, $doctor_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['count'] > 0;
    }
}
?>
