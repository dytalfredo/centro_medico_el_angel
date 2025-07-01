<?php
// intranet_app/models/PublicWeb/Testimonial.php
require_once __DIR__ . '/../../Core/PublicWebDatabase.php';

class Testimonial
{
    private $conn;
    private $table_name = "testimonials";

    public function __construct()
    {
        $db = PublicWebDatabase::getInstance();
        $this->conn = $db->getConnection();
    }

    /**
     * Obtiene todos los testimonios.
     * @return array
     */
    public function getAll()
    {
        $testimonials = [];
        $result = $this->conn->query("SELECT * FROM " . $this->table_name . " ORDER BY id ASC");
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $testimonials[] = $row;
            }
        } else {
            error_log("ERROR en PublicWeb/Testimonial::getAll: " . $this->conn->error);
        }
        return $testimonials;
    }

    /**
     * Obtiene un testimonio por su ID.
     * @param int $id
     * @return array|null
     */
    public function findById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 1");
        if ($stmt === false) {
            error_log("ERROR en PublicWeb/Testimonial::findById al preparar: " . $this->conn->error);
            return null;
        }
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $testimonial = $result->fetch_assoc();
        $stmt->close();
        return $testimonial;
    }

    /**
     * Crea un nuevo testimonio.
     * @param string $quote
     * @param string $author
     * @return int|false
     */
    public function create($quote, $author)
    {
        $stmt = $this->conn->prepare("INSERT INTO " . $this->table_name . " (quote, author) VALUES (?, ?)");
        if ($stmt === false) {
            error_log("ERROR en PublicWeb/Testimonial::create al preparar: " . $this->conn->error);
            return false;
        }
        $stmt->bind_param("ss", $quote, $author);
        if ($stmt->execute()) {
            $newId = $stmt->insert_id;
            $stmt->close();
            return $newId;
        }
        $stmt->close();
        return false;
    }

    /**
     * Actualiza un testimonio existente.
     * @param int $id
     * @param string $quote
     * @param string $author
     * @return bool
     */
    public function update($id, $quote, $author)
    {
        $stmt = $this->conn->prepare("UPDATE " . $this->table_name . " SET quote = ?, author = ? WHERE id = ?");
        if ($stmt === false) {
            error_log("ERROR en PublicWeb/Testimonial::update al preparar: " . $this->conn->error);
            return false;
        }
        $stmt->bind_param("ssi", $quote, $author, $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    /**
     * Elimina un testimonio.
     * @param int $id
     * @return bool
     */
    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM " . $this->table_name . " WHERE id = ?");
        if ($stmt === false) {
            error_log("ERROR en PublicWeb/Testimonial::delete al preparar: " . $this->conn->error);
            return false;
        }
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
}
