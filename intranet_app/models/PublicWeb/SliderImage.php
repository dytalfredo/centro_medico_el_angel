<?php
// intranet_app/models/PublicWeb/SliderImage.php
require_once __DIR__ . '/../../Core/PublicWebDatabase.php';

class SliderImage
{
    private $conn;
    private $table_name = "slider_images";

    public function __construct()
    {
        $db = PublicWebDatabase::getInstance();
        $this->conn = $db->getConnection();
    }

    /**
     * Obtiene todas las imágenes del slider.
     * @return array
     */
    public function getAll()
    {
        $images = [];
        $result = $this->conn->query("SELECT * FROM " . $this->table_name . " ORDER BY order_display ASC, id ASC");
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $images[] = $row;
            }
        } else {
            error_log("ERROR en PublicWeb/SliderImage::getAll: " . $this->conn->error);
        }
        return $images;
    }

    /**
     * Obtiene una imagen de slider por su ID.
     * @param int $id
     * @return array|null
     */
    public function findById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 1");
        if ($stmt === false) {
            error_log("ERROR en PublicWeb/SliderImage::findById al preparar: " . $this->conn->error);
            return null;
        }
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $image = $result->fetch_assoc();
        $stmt->close();
        return $image;
    }

    /**
     * Crea una nueva imagen de slider.
     * @param string $image_url
     * @param string $title
     * @param int $order_display
     * @return int|false
     */
    public function create($image_url, $title, $order_display)
    {
        $stmt = $this->conn->prepare("INSERT INTO " . $this->table_name . " (image_url, title, order_display) VALUES (?, ?, ?)");
        if ($stmt === false) {
            error_log("ERROR en PublicWeb/SliderImage::create al preparar: " . $this->conn->error);
            return false;
        }
        $stmt->bind_param("ssi", $image_url, $title, $order_display);
        if ($stmt->execute()) {
            $newId = $stmt->insert_id;
            $stmt->close();
            return $newId;
        }
        $stmt->close();
        return false;
    }

    /**
     * Actualiza una imagen de slider existente.
     * @param int $id
     * @param string $image_url
     * @param string $title
     * @param int $order_display
     * @return bool
     */
    public function update($id, $image_url, $title, $order_display)
    {
        $stmt = $this->conn->prepare("UPDATE " . $this->table_name . " SET image_url = ?, title = ?, order_display = ? WHERE id = ?");
        if ($stmt === false) {
            error_log("ERROR en PublicWeb/SliderImage::update al preparar: " . $this->conn->error);
            return false;
        }
        $stmt->bind_param("ssii", $image_url, $title, $order_display, $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    /**
     * Elimina una imagen de slider.
     * @param int $id
     * @return bool
     */
    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM " . $this->table_name . " WHERE id = ?");
        if ($stmt === false) {
            error_log("ERROR en PublicWeb/SliderImage::delete al preparar: " . $this->conn->error);
            return false;
        }
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
}
