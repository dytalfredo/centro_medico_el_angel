<?php
// intranet_app/models/PublicWeb/Faq.php
require_once __DIR__ . '/../../Core/PublicWebDatabase.php';

class Faq
{
    private $conn;
    private $table_name = "faqs";

    public function __construct()
    {
        $db = PublicWebDatabase::getInstance();
        $this->conn = $db->getConnection();
    }

    /**
     * Obtiene todas las FAQs.
     * @return array
     */
    public function getAll()
    {
        $faqs = [];
        $result = $this->conn->query("SELECT * FROM " . $this->table_name . " ORDER BY id ASC");
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $faqs[] = $row;
            }
        } else {
            error_log("ERROR en PublicWeb/Faq::getAll: " . $this->conn->error);
        }
        return $faqs;
    }

    /**
     * Obtiene una FAQ por su ID.
     * @param int $id
     * @return array|null
     */
    public function findById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 1");
        if ($stmt === false) {
            error_log("ERROR en PublicWeb/Faq::findById al preparar: " . $this->conn->error);
            return null;
        }
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $faq = $result->fetch_assoc();
        $stmt->close();
        return $faq;
    }

    /**
     * Crea una nueva FAQ.
     * @param string $question
     * @param string $answer
     * @return int|false
     */
    public function create($question, $answer)
    {
        $stmt = $this->conn->prepare("INSERT INTO " . $this->table_name . " (question, answer) VALUES (?, ?)");
        if ($stmt === false) {
            error_log("ERROR en PublicWeb/Faq::create al preparar: " . $this->conn->error);
            return false;
        }
        $stmt->bind_param("ss", $question, $answer);
        if ($stmt->execute()) {
            $newId = $stmt->insert_id;
            $stmt->close();
            return $newId;
        }
        $stmt->close();
        return false;
    }

    /**
     * Actualiza una FAQ existente.
     * @param int $id
     * @param string $question
     * @param string $answer
     * @return bool
     */
    public function update($id, $question, $answer)
    {
        $stmt = $this->conn->prepare("UPDATE " . $this->table_name . " SET question = ?, answer = ? WHERE id = ?");
        if ($stmt === false) {
            error_log("ERROR en PublicWeb/Faq::update al preparar: " . $this->conn->error);
            return false;
        }
        $stmt->bind_param("ssi", $question, $answer, $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    /**
     * Elimina una FAQ.
     * @param int $id
     * @return bool
     */
    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM " . $this->table_name . " WHERE id = ?");
        if ($stmt === false) {
            error_log("ERROR en PublicWeb/Faq::delete al preparar: " . $this->conn->error);
            return false;
        }
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
}
