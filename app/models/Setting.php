<?php
require_once __DIR__ . '/Database.php';

class Setting {
    private $conn;

    public function __construct() {
        $db = Database::getInstance();
        $this->conn = $db->getConnection();
    }

    /**
     * Obtiene el valor de una configuración específica por su clave.
     * @param string $setting_key La clave única de la configuración (e.g., 'site_logo_url').
     * @return string El valor de la configuración o una cadena vacía si no se encuentra.
     */
    public function getValue($setting_key) {
        $stmt = $this->conn->prepare("SELECT setting_value FROM settings WHERE setting_key = ?");
        $stmt->bind_param("s", $setting_key);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            return $row['setting_value'];
        }
        return '';
    }

    /**
     * Obtiene todas las configuraciones.
     * @return array Un array asociativo de todas las configuraciones (clave => valor).
     */
    public function getAllSettings() {
        $settings = [];
        $result = $this->conn->query("SELECT setting_key, setting_value FROM settings");
        while ($row = $result->fetch_assoc()) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
        return $settings;
    }

    public function saveSetting($setting_key, $setting_value) {
        // 1. Verificar si el setting_key ya existe
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM settings WHERE setting_key = ?");
        $stmt->bind_param("s", $setting_key);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_row();
        $count = $row[0];
        $stmt->close(); // Cierra el primer statement

        $success = false; // Variable para almacenar el resultado de la operación

        if ($count > 0) {
            // 2. Si la clave existe, actualiza el setting_value
            $stmt = $this->conn->prepare("UPDATE settings SET setting_value = ? WHERE setting_key = ?");
            // "ss" porque tanto setting_value como setting_key son strings
            $stmt->bind_param("ss", $setting_value, $setting_key);
        } else {
            // 3. Si la clave no existe, inserta una nueva fila
            $stmt = $this->conn->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?)");
            // "ss" porque setting_key y setting_value son strings
            $stmt->bind_param("ss", $setting_key, $setting_value);
        }

        // 4. Ejecutar la operación (UPDATE o INSERT)
        $success = $stmt->execute();

        $stmt->close(); // Cierra el segundo statement
        return $success; // Retorna true si la operación fue exitosa, false si hubo un error
    }
}
?>
