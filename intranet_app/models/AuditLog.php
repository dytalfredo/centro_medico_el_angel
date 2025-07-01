<?php
require_once __DIR__ . '/../Core/IntranetDatabase.php';

class AuditLog {
    private $conn;
    private $table_name = "audit_logs";

    public function __construct() {
        $db = IntranetDatabase::getInstance();
        $this->conn = $db->getConnection();
    }

    /**
     * Registra una acción en el log de auditoría.
     * @param int|null $user_id ID del usuario que realizó la acción (o null si no hay usuario).
     * @param string $action Tipo de acción (e.g., 'user_login', 'invoice_created').
     * @param string $details Detalles de la acción.
     * @return int ID del log o false si falla.
     */
    public function logAction($user_id, $action, $details) {
        $ip_address = $_SERVER['REMOTE_ADDR'] ?? null;
        $stmt = $this->conn->prepare("INSERT INTO " . $this->table_name . " (user_id, action, details, ip_address) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $user_id, $action, $details, $ip_address);
        if ($stmt->execute()) {
            return $stmt->insert_id;
        }
        return false;
    }

    /**
     * Obtiene los últimos N logs de auditoría.
     * @param int $limit
     * @return array
     */
    public function getLatest($limit = 10) {
        $logs = [];
        $stmt = $this->conn->prepare("
            SELECT al.id, al.action, al.details, al.timestamp, al.ip_address, u.username
            FROM " . $this->table_name . " al
            LEFT JOIN users u ON al.user_id = u.id
            ORDER BY al.timestamp DESC LIMIT ?
        ");
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $logs[] = $row;
        }
        return $logs;
    }
}
?>
