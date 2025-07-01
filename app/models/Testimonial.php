<?php
require_once __DIR__ . '/Database.php';

class Testimonial {
    private $conn;

    public function __construct() {
        $db = Database::getInstance();
        $this->conn = $db->getConnection();
    }

    /**
     * Obtiene todos los testimonios.
     * @return array Un array de objetos con los datos de los testimonios.
     */
    public function getAll() {
        $testimonials = [];
        $result = $this->conn->query("SELECT quote, author FROM testimonials ORDER BY id DESC");
        while ($row = $result->fetch_assoc()) {
            $testimonials[] = $row;
        }
        return $testimonials;
    }
}
?>