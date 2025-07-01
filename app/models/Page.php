<?php
require_once __DIR__ . '/Database.php';

class Page {
    private $conn;

    public function __construct() {
        $db = Database::getInstance();
        $this->conn = $db->getConnection();
    }

    /**
     * Obtiene el contenido de una página específica por su clave.
     * @param string $page_key La clave única de la página (e.g., 'home_mission', 'about_mission').
     * @return string El contenido de la página o una cadena vacía si no se encuentra.
     */
    public function getContent($page_key) {
        $stmt = $this->conn->prepare("SELECT content FROM pages WHERE page_key = ?");
        $stmt->bind_param("s", $page_key);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            return $row['content'];
        }
        return '';
    }

    public function saveContent($page_key, $content) {
        // Primero, verificamos si la page_key ya existe
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM pages WHERE page_key = ?");
        $stmt->bind_param("s", $page_key);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_row();
        $count = $row[0];
        $stmt->close(); // Siempre cierra el statement después de usarlo

        if ($count > 0) {
            // Si la clave ya existe, actualizamos el contenido
            $stmt = $this->conn->prepare("UPDATE pages SET content = ? WHERE page_key = ?");
            // "ss" porque ambos parámetros son strings
            $stmt->bind_param("ss", $content, $page_key);
        } else {
            // Si la clave no existe, insertamos una nueva fila
            $stmt = $this->conn->prepare("INSERT INTO pages (page_key, content) VALUES (?, ?)");
            // "ss" porque ambos parámetros son strings
            $stmt->bind_param("ss", $page_key, $content);
        }

        // Ejecutar la operación (UPDATE o INSERT)
        $success = $stmt->execute();

        $stmt->close(); // Cierra el statement
        return $success; // Devuelve true si la operación fue exitosa, false si hubo un error
    }
}
?>