<?php
// intranet_app/models/PublicWeb/ServiceDoctor.php
require_once __DIR__ . '/../../Core/PublicWebDatabase.php';

class ServiceDoctor {
    private $conn;
    private $table_name = "service_doctor"; // ¡Corregido el nombre de la tabla a 'service_doctor'!

    public function __construct() {
        $db = PublicWebDatabase::getInstance();
        $this->conn = $db->getConnection();
        if ($this->conn === null) {
            error_log("DEBUG: La conexión a la base de datos pública es nula en ServiceDoctor::__construct.");
        }
    }

    /**
     * Crea una asociación entre un servicio y un doctor.
     * @param int $service_id
     * @param int $doctor_id
     * @return bool
     */
    public function createAssociation($service_id, $doctor_id) {
        if ($this->conn === null) {
            error_log("ERROR: No hay conexión a la base de datos para createAssociation.");
            return false;
        }

        // Verificar si la asociación ya existe para evitar duplicados
        if ($this->associationExists($service_id, $doctor_id)) {
            return true; // Ya existe, no es un error
        }
        $stmt = $this->conn->prepare("INSERT INTO " . $this->table_name . " (service_id, doctor_id) VALUES (?, ?)");
        if ($stmt === false) {
            error_log("ERROR: Fallo al preparar la declaración para createAssociation: " . $this->conn->error);
            return false;
        }
        $stmt->bind_param("ii", $service_id, $doctor_id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    /**
     * Elimina una asociación específica entre un servicio y un doctor.
     * @param int $service_id
     * @param int $doctor_id
     * @return bool
     */
    public function deleteAssociation($service_id, $doctor_id) {
        if ($this->conn === null) {
            error_log("ERROR: No hay conexión a la base de datos para deleteAssociation.");
            return false;
        }
        $stmt = $this->conn->prepare("DELETE FROM " . $this->table_name . " WHERE service_id = ? AND doctor_id = ?");
        if ($stmt === false) {
            error_log("ERROR: Fallo al preparar la declaración para deleteAssociation: " . $this->conn->error);
            return false;
        }
        $stmt->bind_param("ii", $service_id, $doctor_id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    /**
     * Elimina todas las asociaciones para un servicio dado.
     * @param int $service_id
     * @return bool
     */
    public function deleteByServiceId($service_id) {
        if ($this->conn === null) {
            error_log("ERROR: No hay conexión a la base de datos para deleteByServiceId.");
            return false;
        }
        $stmt = $this->conn->prepare("DELETE FROM " . $this->table_name . " WHERE service_id = ?");
        if ($stmt === false) {
            error_log("ERROR: Fallo al preparar la declaración para deleteByServiceId: " . $this->conn->error);
            return false;
        }
        $stmt->bind_param("i", $service_id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    /**
     * Obtiene los doctores asociados a un servicio específico (incluyendo nombre y especialidad).
     * @param int $service_id
     * @return array
     */
    public function getServiceDoctors($service_id) {
        $doctors = [];
        if ($this->conn === null) {
            error_log("ERROR: No hay conexión a la base de datos para getServiceDoctors.");
            return [];
        }
        $stmt = $this->conn->prepare("
            SELECT d.id, d.name, d.specialty
            FROM " . $this->table_name . " sd
            JOIN doctors d ON sd.doctor_id = d.id
            WHERE sd.service_id = ?
        ");
        if ($stmt === false) {
            error_log("ERROR: Fallo al preparar la declaración para getServiceDoctors: " . $this->conn->error);
            return [];
        }
        $stmt->bind_param("i", $service_id);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $doctors[] = $row;
        }
        $stmt->close();
        return $doctors;
    }

    /**
     * Obtiene los IDs de los doctores asociados a un servicio específico.
     * @param int $service_id
     * @return array Un array de IDs de doctores.
     */
    public function getAssociatedDoctorIds($service_id) {
        $doctorIds = [];
        if ($this->conn === null) {
            error_log("ERROR: No hay conexión a la base de datos para getAssociatedDoctorIds.");
            return [];
        }
        $stmt = $this->conn->prepare("SELECT doctor_id FROM " . $this->table_name . " WHERE service_id = ?");
        if ($stmt === false) {
            error_log("ERROR: Fallo al preparar la declaración para getAssociatedDoctorIds: " . $this->conn->error);
            return [];
        }
        $stmt->bind_param("i", $service_id);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $doctorIds[] = $row['doctor_id'];
        }
        $stmt->close();
        return $doctorIds;
    }

    /**
     * Verifica si una asociación entre un servicio y un doctor ya existe.
     * @param int $service_id
     * @param int $doctor_id
     * @return bool
     */
    public function associationExists($service_id, $doctor_id) {
        if ($this->conn === null) {
            error_log("ERROR: No hay conexión a la base de datos para associationExists.");
            return false;
        }
        $stmt = $this->conn->prepare("SELECT COUNT(*) as count FROM " . $this->table_name . " WHERE service_id = ? AND doctor_id = ?");
        if ($stmt === false) {
            error_log("ERROR: Fallo al preparar la declaración para associationExists: " . $this->conn->error);
            return false;
        }
        $stmt->bind_param("ii", $service_id, $doctor_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row['count'] > 0;
    }
}
