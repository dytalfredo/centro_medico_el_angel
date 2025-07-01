<?php
// intranet_app/models/PublicWeb/Service.php
require_once __DIR__ . '/../../Core/PublicWebDatabase.php';

class Service
{
    private $conn;
    private $table_name = "services";

    public function __construct()
    {
        $db = PublicWebDatabase::getInstance();
        $this->conn = $db->getConnection();
    }

    /**
     * Obtiene todos los servicios.
     * @return array
     */
    public function getAll()
    {
        $services = [];
        $result = $this->conn->query("SELECT * FROM " . $this->table_name . " ORDER BY name ASC");
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $services[] = $row;
            }
        } else {
            error_log("ERROR en PublicWeb/Service::getAll: " . $this->conn->error);
        }
        return $services;
    }

    /**
     * Obtiene un servicio por su ID.
     * @param int $id
     * @return array|null
     */
    public function findById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 1");
        if ($stmt === false) {
            error_log("ERROR en PublicWeb/Service::findById al preparar: " . $this->conn->error);
            return null;
        }
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $service = $result->fetch_assoc();
        $stmt->close();
        return $service;
    }

    /**
     * Crea un nuevo servicio.
     * @param string $name
     * @param string|null $description
     * @return int|false
     */
    public function create($name, $description = null)
    {
        $stmt = $this->conn->prepare("INSERT INTO " . $this->table_name . " (name, description) VALUES (?, ?)");
        if ($stmt === false) {
            error_log("ERROR en PublicWeb/Service::create al preparar: " . $this->conn->error);
            return false;
        }
        $stmt->bind_param("ss", $name, $description);
        if ($stmt->execute()) {
            $newId = $stmt->insert_id;
            $stmt->close();
            return $newId;
        }
        $stmt->close();
        return false;
    }

    /**
     * Actualiza un servicio existente.
     * @param int $id
     * @param string $name
     * @param string|null $description
     * @return bool
     */
    public function update($id, $name, $description = null)
    {
        $stmt = $this->conn->prepare("UPDATE " . $this->table_name . " SET name = ?, description = ? WHERE id = ?");
        if ($stmt === false) {
            error_log("ERROR en PublicWeb/Service::update al preparar: " . $this->conn->error);
            return false;
        }
        $stmt->bind_param("ssi", $name, $description, $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    /**
     * Elimina un servicio.
     * @param int $id
     * @return bool
     */
    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM " . $this->table_name . " WHERE id = ?");
        if ($stmt === false) {
            error_log("ERROR en PublicWeb/Service::delete al preparar: " . $this->conn->error);
            return false;
        }
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
}
