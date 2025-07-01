<?php
require_once __DIR__ . '/../Core/IntranetDatabase.php'; // Ruta actualizada

class Doctor {
    private $conn;
    private $table_name = "doctors";

    public function __construct() {
        $db = IntranetDatabase::getInstance();
        $this->conn = $db->getConnection();
    }

    /**
     * Obtiene un doctor por su ID.
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
     * Obtiene todos los doctores, con opciones de filtrado.
     * @param array $filters Opcional. Un array asociativo con filtros (name, specialty, is_active).
     * @return array
     */
    public function getAll($filters = []) {
        $doctors = [];
        $whereClause = "WHERE 1=1"; // Cláusula base para construir los filtros
        $params = [];
        $paramTypes = "";

        if (!empty($filters['name'])) {
            $whereClause .= " AND name LIKE ?";
            $params[] = "%" . $filters['name'] . "%";
            $paramTypes .= "s";
        }
        if (!empty($filters['specialty'])) {
            $whereClause .= " AND specialty LIKE ?";
            $params[] = "%" . $filters['specialty'] . "%";
            $paramTypes .= "s";
        }
        if (isset($filters['is_active']) && $filters['is_active'] !== 'all') {
            $whereClause .= " AND is_active = ?";
            $params[] = (int)$filters['is_active'];
            $paramTypes .= "i";
        }

        $sql = "SELECT id, name, specialty, phone, email, fee_percentage, is_active FROM " . $this->table_name . " {$whereClause} ORDER BY name ASC";
        
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            error_log("Error al preparar la consulta getAll Doctors: " . $this->conn->error);
            return [];
        }

        // Si hay parámetros, vincularlos
        if (!empty($params)) {
            $stmt->bind_param($paramTypes, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $doctors[] = $row;
        }
        $stmt->close();
        return $doctors;
    }

    /**
     * Crea un nuevo doctor.
     * @param array $data Datos del doctor (name, specialty, phone, email, fee_percentage, is_active).
     * @return int ID del nuevo doctor o false si falla.
     */
    public function create($data) {
        $stmt = $this->conn->prepare("INSERT INTO " . $this->table_name . " (name, specialty, phone, email, fee_percentage, is_active) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssid",
            $data['name'],
            $data['specialty'],
            $data['phone'],
            $data['email'],
            $data['fee_percentage'],
            $data['is_active']
        );
        if ($stmt->execute()) {
            return $stmt->insert_id;
        }
        return false;
    }

    /**
     * Actualiza un doctor existente.
     * @param int $id ID del doctor.
     * @param array $data Datos a actualizar.
     * @return bool True si tiene éxito, false si falla.
     */
    public function update($id, $data) {
        $sql = "UPDATE " . $this->table_name . " SET name = ?, specialty = ?, phone = ?, email = ?, fee_percentage = ?, is_active = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssssidi",
            $data['name'],
            $data['specialty'],
            $data['phone'],
            $data['email'],
            $data['fee_percentage'],
            $data['is_active'],
            $id
        );
        return $stmt->execute();
    }

    /**
     * Activa o desactiva un doctor.
     * @param int $id ID del doctor.
     * @param bool $is_active Nuevo estado.
     * @return bool True si tiene éxito, false si falla.
     */
    public function toggleActiveStatus($id, $is_active) {
        $stmt = $this->conn->prepare("UPDATE " . $this->table_name . " SET is_active = ? WHERE id = ?");
        $stmt->bind_param("ii", $is_active, $id);
        return $stmt->execute();
    }

    /**
     * Cuenta el número de doctores activos.
     * @return int El número de doctores activos.
     */
    public function countActiveDoctors() {
        $stmt = $this->conn->prepare("SELECT COUNT(*) as count FROM " . $this->table_name . " WHERE is_active = 1");
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['count'];
    }
}
