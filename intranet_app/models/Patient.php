<?php
require_once __DIR__ . '/../Core/IntranetDatabase.php';

class Patient {
    private $conn;
    private $table_name = "patients";

    public function __construct() {
        $db = IntranetDatabase::getInstance();
        $this->conn = $db->getConnection();
    }

    /**
     * Obtiene un paciente por su ID.
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
     * Obtiene un paciente por su número de identificación (cédula).
     * @param string $id_number
     * @return array|null
     */
    public function findByIDNumber($id_number) {
        $stmt = $this->conn->prepare("SELECT * FROM " . $this->table_name . " WHERE id_number = ? LIMIT 1");
        $stmt->bind_param("s", $id_number);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }


    /**
     * Busca pacientes por nombre o número de identificación, devolviendo todos los detalles relevantes.
     * @param string $query
     * @return array
     */
    public function search($query) {
        $patients = [];
        $search_query = "%" . $query . "%";
        $stmt = $this->conn->prepare("SELECT id, name, id_number, phone, email, address, date_of_birth FROM " . $this->table_name . " WHERE name LIKE ? OR id_number LIKE ? LIMIT 10");
        $stmt->bind_param("ss", $search_query, $search_query);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $patients[] = $row;
        }
        return $patients;
    }

    /**
     * Obtiene los últimos N pacientes creados/actualizados.
     * @param int $limit
     * @return array
     */
    public function getLatest($limit = 10) {
        $patients = [];
        $stmt = $this->conn->prepare("SELECT id, name, id_number, phone, email FROM " . $this->table_name . " ORDER BY id DESC LIMIT ?");
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $patients[] = $row;
        }
        return $patients;
    }

    /**
     * Crea un nuevo paciente.
     * @param array $data Datos del paciente (name, id_number, phone, email, address, date_of_birth).
     * @return int ID del nuevo paciente o false si falla.
     */
    public function create($data) {
        $stmt = $this->conn->prepare("INSERT INTO " . $this->table_name . " (name, id_number, phone, email, address, date_of_birth) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $data['name'], $data['id_number'], $data['phone'], $data['email'], $data['address'], $data['date_of_birth']);
        if ($stmt->execute()) {
            return $stmt->insert_id;
        }
        return false;
    }
}
?>
