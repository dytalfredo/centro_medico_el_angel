<?php
// intranet_app/models/PublicWeb/Page.php
require_once __DIR__ . '/../../Core/PublicWebDatabase.php';

class Page
{
    private $conn;
    private $table_name = "pages";

    public function __construct()
    {
        $db = PublicWebDatabase::getInstance();
        $this->conn = $db->getConnection();
    }

    /**
     * Obtiene todas las páginas de contenido.
     * @return array
     */
    public function getAll()
    {
        $pages = [];
        $result = $this->conn->query("SELECT * FROM " . $this->table_name . " ORDER BY page_key ASC");
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $pages[] = $row;
            }
        } else {
            error_log("ERROR en PublicWeb/Page::getAll: " . $this->conn->error);
        }
        return $pages;
    }

    /**
     * Obtiene una página por su ID.
     * @param int $id
     * @return array|null
     */
    public function findById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 1");
        if ($stmt === false) {
            error_log("ERROR en PublicWeb/Page::findById al preparar: " . $this->conn->error);
            return null;
        }
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $page = $result->fetch_assoc();
        $stmt->close();
        return $page;
    }

    /**
     * Crea una nueva entrada de página.
     * @param string $page_key
     * @param string $content
     * @return int|false
     */
    public function create($page_key, $content)
    {
        $stmt = $this->conn->prepare("INSERT INTO " . $this->table_name . " (page_key, content) VALUES (?, ?)");
        if ($stmt === false) {
            error_log("ERROR en PublicWeb/Page::create al preparar: " . $this->conn->error);
            return false;
        }
        $stmt->bind_param("ss", $page_key, $content);
        if ($stmt->execute()) {
            $newId = $stmt->insert_id;
            $stmt->close();
            return $newId;
        }
        $stmt->close();
        return false;
    }

    /**
     * Actualiza una entrada de página existente.
     * @param int $id
     * @param string $content
     * @return bool
     */
    public function update($id, $content)
    {
        $stmt = $this->conn->prepare("UPDATE " . $this->table_name . " SET content = ? WHERE id = ?");
        if ($stmt === false) {
            error_log("ERROR en PublicWeb/Page::update al preparar: " . $this->conn->error);
            return false;
        }
        $stmt->bind_param("si", $content, $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    /**
     * Elimina una entrada de página.
     * @param int $id
     * @return bool
     */
    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM " . $this->table_name . " WHERE id = ?");
        if ($stmt === false) {
            error_log("ERROR en PublicWeb/Page::delete al preparar: " . $this->conn->error);
            return false;
        }
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
}
