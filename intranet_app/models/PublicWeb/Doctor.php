<?php
// intranet_app/models/PublicWeb/Doctor.php
require_once __DIR__ . '/../../Core/PublicWebDatabase.php';

class PublicWebDoctor
{
    private $conn;
    private $table_name = "doctors";

    public function __construct()
    {
        $db = PublicWebDatabase::getInstance();
        $this->conn = $db->getConnection();
    }

    /**
     * Obtiene todos los doctores.
     * @return array
     */
    public function getAll()
    {
        $doctors = [];
        $result = $this->conn->query("SELECT * FROM " . $this->table_name . " ORDER BY name ASC");
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $doctors[] = $row;
            }
        } else {
            error_log("ERROR en PublicWeb/Doctor::getAll: " . $this->conn->error);
        }
        return $doctors;
    }

    /**
     * Obtiene un doctor por su ID.
     * @param int $id
     * @return array|null
     */
    public function findById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 1");
        if ($stmt === false) {
            error_log("ERROR en PublicWeb/Doctor::findById al preparar: " . $this->conn->error);
            return null;
        }
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $doctor = $result->fetch_assoc();
        $stmt->close();
        return $doctor;
    }

    /**
     * Crea un nuevo doctor.
     * @param string $name
     * @return int|false
     */
    public function create($name)
    {
        $stmt = $this->conn->prepare("INSERT INTO " . $this->table_name . " (name) VALUES (?)");
        if ($stmt === false) {
            error_log("ERROR en PublicWeb/Doctor::create al preparar: " . $this->conn->error);
            return false;
        }
        $stmt->bind_param("s", $name);
        if ($stmt->execute()) {
            $newId = $stmt->insert_id;
            $stmt->close();
            return $newId;
        }
        $stmt->close();
        return false;
    }

    /**
     * Actualiza un doctor existente.
     * @param int $id
     * @param string $name
     * @return bool
     */
    public function update($id, $name)
    {
        $stmt = $this->conn->prepare("UPDATE " . $this->table_name . " SET name = ? WHERE id = ?");
        if ($stmt === false) {
            error_log("ERROR en PublicWeb/Doctor::update al preparar: " . $this->conn->error);
            return false;
        }
        $stmt->bind_param("si", $name, $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    /**
     * Elimina un doctor.
     * @param int $id
     * @return bool
     */
    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM " . $this->table_name . " WHERE id = ?");
        if ($stmt === false) {
            error_log("ERROR en PublicWeb/Doctor::delete al preparar: " . $this->conn->error);
            return false;
        }
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
}
