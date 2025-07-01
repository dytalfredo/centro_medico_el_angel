<?php
class Database {
    private static $instance = null;
    private $connection;

    private function __construct() {
        // Establece la conexión a la base de datos usando MySQLi
        $this->connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        // Verifica si hay errores en la conexión
        if ($this->connection->connect_error) {
            die("Error de conexión a la base de datos: " . $this->connection->connect_error);
        }

        // Establece el conjunto de caracteres a utf8mb4 para evitar problemas con tildes y caracteres especiales
        $this->connection->set_charset("utf8mb4");
    }

    // Método para obtener la única instancia de la clase Database (Singleton Pattern)
    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    // Método para obtener la conexión a la base de datos
    public function getConnection() {
        return $this->connection;
    }

    // Método para cerrar la conexión (opcional, PHP la cierra automáticamente al final del script)
    public function closeConnection() {
        if ($this->connection) {
            $this->connection->close();
        }
    }
}
?>
