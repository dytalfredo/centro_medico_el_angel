<?php
require_once __DIR__ . '/Database.php';

class SliderImage {
    private $conn;

    public function __construct() {
        $db = Database::getInstance();
        $this->conn = $db->getConnection();
    }

    /**
     * Obtiene todas las imágenes del slider ordenadas.
     * @return array Un array de objetos con los datos de las imágenes del slider.
     */
    public function getAll() {
        $images = [];
        $result = $this->conn->query("SELECT image_url, title FROM slider_images ORDER BY order_display ASC");
        while ($row = $result->fetch_assoc()) {
            $images[] = $row;
        }
        return $images;
    }
}
?>