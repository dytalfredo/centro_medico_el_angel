<?php
require_once __DIR__ . '/IntranetDatabase.php';

class User {
    private $conn;
    private $table_name = "users";

    public function __construct() {
        $db = IntranetDatabase::getInstance();
        $this->conn = $db->getConnection();
    }

    /**
     * Encuentra un usuario por su ID.
     * @param int $id
     * @return array|null Los datos del usuario o null si no se encuentra.
     */
    public function findById($id) {
        $stmt = $this->conn->prepare("SELECT id, username, role, full_name, email, created_at FROM " . $this->table_name . " WHERE id = ? LIMIT 1");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    /**
     * Encuentra un usuario por su nombre de usuario.
     * @param string $username
     * @return array|null Los datos del usuario (incluyendo el hash de la contraseña) o null.
     */
    public function findByUsername($username) {
        $stmt = $this->conn->prepare("SELECT id, username, password, role, full_name, email, created_at FROM " . $this->table_name . " WHERE username = ? LIMIT 1");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    /**
     * Obtiene todos los usuarios del sistema.
     * @return array Un array de objetos con los datos de los usuarios.
     */
    public function getAll() {
        $users = [];
        $result = $this->conn->query("SELECT id, username, role, full_name, email, created_at FROM " . $this->table_name . " ORDER BY username ASC");
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        return $users;
    }

    /**
     * Cuenta el número total de usuarios por rol (admin y assistant).
     * @return int
     */
    public function countUsersByRole() {
        $result = $this->conn->query("SELECT COUNT(*) as count FROM " . $this->table_name);
        $row = $result->fetch_assoc();
        return $row['count'];
    }

    /**
     * Crea un nuevo usuario.
     * @param array $data Array asociativo con 'username', 'password' (sin hashear), 'role', 'full_name', 'email'.
     * @return int ID del nuevo usuario o false si falla.
     */
    public function create($data) {
        // Hashear la contraseña antes de guardar
        $hashed_password = password_hash($data['password'], PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("INSERT INTO " . $this->table_name . " (username, password, role, full_name, email) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $data['username'], $hashed_password, $data['role'], $data['full_name'], $data['email']);
        if ($stmt->execute()) {
            return $stmt->insert_id;
        }
        return false;
    }

    /**
     * Actualiza un usuario existente.
     * @param int $id ID del usuario a actualizar.
     * @param array $data Array asociativo con los campos a actualizar (username, role, full_name, email).
     * @return bool True si tiene éxito, false si falla.
     */
    public function update($id, $data) {
        $sql = "UPDATE " . $this->table_name . " SET username = ?, role = ?, full_name = ?, email = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssssi", $data['username'], $data['role'], $data['full_name'], $data['email'], $id);
        return $stmt->execute();
    }

    /**
     * Actualiza la contraseña de un usuario.
     * @param int $id ID del usuario.
     * @param string $new_password_hash El hash de la nueva contraseña.
     * @return bool True si tiene éxito, false si falla.
     */
    public function updatePassword($id, $new_password_hash) {
        $stmt = $this->conn->prepare("UPDATE " . $this->table_name . " SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $new_password_hash, $id);
        return $stmt->execute();
    }

    /**
     * Elimina un usuario.
     * @param int $id ID del usuario a eliminar.
     * @return bool True si tiene éxito, false si falla.
     */
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM " . $this->table_name . " WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>
