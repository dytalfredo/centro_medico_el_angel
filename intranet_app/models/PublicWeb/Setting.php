<?php
// intranet_app/models/PublicWeb/Setting.php
require_once __DIR__ . '/../../Core/PublicWebDatabase.php';

class Setting {
    private $conn;
    private $table_name = "settings";

    public function __construct() {
        $db = PublicWebDatabase::getInstance();
        $this->conn = $db->getConnection();
    }

    /**
     * Obtiene todas las configuraciones.
     * @return array
     */
    public function getAll() {
        $settings = [];
        $result = $this->conn->query("SELECT id, setting_key, setting_value FROM " . $this->table_name . " ORDER BY setting_key ASC");
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $settings[] = $row;
            }
        } else {
            error_log("ERROR en Setting::getAll: " . $this->conn->error);
        }
        return $settings;
    }

    /**
     * Obtiene una configuración por su clave.
     * @param string $key La clave de la configuración.
     * @return array|null
     */
    public function findByKey($key) {
        $stmt = $this->conn->prepare("SELECT id, setting_key, setting_value FROM " . $this->table_name . " WHERE setting_key = ? LIMIT 1");
        if ($stmt === false) {
            error_log("ERROR en Setting::findByKey al preparar: " . $this->conn->error);
            return null;
        }
        $stmt->bind_param("s", $key);
        $stmt->execute();
        $result = $stmt->get_result();
        $setting = $result->fetch_assoc();
        $stmt->close();
        return $setting;
    }

    /**
     * Actualiza una configuración existente.
     * @param int $id El ID de la configuración.
     * @param string $value El nuevo valor de la configuración.
     * @return bool True si tiene éxito, false si falla.
     */
    public function update($id, $value) {
        $stmt = $this->conn->prepare("UPDATE " . $this->table_name . " SET setting_value = ? WHERE id = ?");
        if ($stmt === false) {
            error_log("ERROR en Setting::update al preparar: " . $this->conn->error);
            return false;
        }
        $stmt->bind_param("si", $value, $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    /**
     * Crea una nueva configuración (raro para settings, pero posible).
     * @param string $key La clave de la configuración.
     * @param string $value El valor de la configuración.
     * @return int|false El ID del nuevo registro o false si falla.
     */
    public function create($key, $value) {
        $stmt = $this->conn->prepare("INSERT INTO " . $this->table_name . " (setting_key, setting_value) VALUES (?, ?)");
        if ($stmt === false) {
            error_log("ERROR en Setting::create al preparar: " . $this->conn->error);
            return false;
        }
        $stmt->bind_param("ss", $key, $value);
        if ($stmt->execute()) {
            $newId = $stmt->insert_id;
            $stmt->close();
            return $newId;
        }
        $stmt->close();
        return false;
    }
}
