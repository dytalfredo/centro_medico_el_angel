<?php
require_once __DIR__ . '/Database.php';

class FAQ {
    private $conn;

    public function __construct() {
        $db = Database::getInstance();
        $this->conn = $db->getConnection();
    }

    /**
     * Obtiene todas las preguntas frecuentes.
     * @return array Un array de objetos con las preguntas y respuestas.
     */
    public function getAll() {
        $faqs = [];
        $result = $this->conn->query("SELECT question, answer FROM faqs ORDER BY id ASC");
        while ($row = $result->fetch_assoc()) {
            $faqs[] = $row;
        }
        return $faqs;
    }
}
?>
