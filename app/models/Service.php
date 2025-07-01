<?php
require_once __DIR__ . '/Database.php';

class Service {
    private $conn;

    public function __construct() {
        $db = Database::getInstance();
        $this->conn = $db->getConnection();
    }

    /**
     * Obtiene todos los servicios con sus doctores asociados.
     * @return array Un array de objetos con los datos de los servicios y sus doctores.
     */
    public function getAllWithDoctors() {
        $sql = "SELECT s.name AS service_name, d.name AS doctor_name
                FROM services s
                LEFT JOIN service_doctor sd ON s.id = sd.service_id
                LEFT JOIN doctors d ON sd.doctor_id = d.id
                ORDER BY s.name, d.name";
        $result = $this->conn->query($sql);

        $services = [];
        while ($row = $result->fetch_assoc()) {
            $serviceName = $row['service_name'];
            if (!isset($services[$serviceName])) {
                $services[$serviceName] = [
                    'service' => $serviceName,
                    'doctors' => []
                ];
            }
            if ($row['doctor_name']) {
                $services[$serviceName]['doctors'][] = $row['doctor_name'];
            }
        }
        // Convertir el array asociativo a un array indexado para la vista
        return array_values($services);
    }

    public function createServiceWithDoctors($serviceName, $serviceDescription, array $doctorIds = []) {
        // Iniciar la transacción
        $this->conn->begin_transaction();

        try {
            // 1. Insertar el nuevo servicio en la tabla `services`
            // ¡Aquí la clave está en añadir 'description' a la consulta INSERT y a bind_param!
            $stmt = $this->conn->prepare("INSERT INTO services (name, description) VALUES (?, ?)");
            $stmt->bind_param("ss", $serviceName, $serviceDescription); // "ss" para dos strings

            if (!$stmt->execute()) {
                throw new Exception("Error al insertar el servicio: " . $stmt->error);
            }
            $newServiceId = $this->conn->insert_id;
            $stmt->close();

            // 2. Asociar el servicio con los doctores en `service_doctor` (si se proporcionaron doctorIds)
            if (!empty($doctorIds)) {
                $insertDoctorStmt = $this->conn->prepare("INSERT INTO service_doctor (service_id, doctor_id) VALUES (?, ?)");

                foreach ($doctorIds as $doctorId) {
                    if (!$this->doctorExists($doctorId)) {
                         error_log("Advertencia: Doctor con ID $doctorId no existe. No se asociará con el servicio $serviceName.");
                         continue;
                    }
                    $insertDoctorStmt->bind_param("ii", $newServiceId, $doctorId);
                    if (!$insertDoctorStmt->execute()) {
                        throw new Exception("Error al asociar el doctor $doctorId con el servicio: " . $insertDoctorStmt->error);
                    }
                }
                $insertDoctorStmt->close();
            }

            // Si todo fue bien, confirmar la transacción
            $this->conn->commit();
            return $newServiceId;
        } catch (Exception $e) {
            // Si algo salió mal, revertir la transacción
            $this->conn->rollback();
            error_log("Error en createServiceWithDoctors: " . $e->getMessage());
            return false;
        }
    }

    // Método auxiliar para verificar si un doctor existe (sin cambios)
    private function doctorExists($doctorId) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM doctors WHERE id = ?");
        $stmt->bind_param("i", $doctorId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_row();
        $count = $row[0];
        $stmt->close();
        return $count > 0;
    }
}
?>