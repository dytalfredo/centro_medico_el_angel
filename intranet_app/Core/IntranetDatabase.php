<?php
// Carga la configuración de la base de datos de la intranet
require_once __DIR__ . '/../config/intranet_database.php';

class IntranetDatabase {
    private static $instance = null;
    private $connection;

    private function __construct() {
        $this->connection = new mysqli(INTRANET_DB_HOST, INTRANET_DB_USER, INTRANET_DB_PASS, INTRANET_DB_NAME);

        if ($this->connection->connect_error) {
            die("Error de conexión a la base de datos de la intranet: " . $this->connection->connect_error);
        }

        $this->connection->set_charset("utf8mb4");
    }

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new IntranetDatabase();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->connection;
    }

    public function closeConnection() {
        if ($this->connection) {
            $this->connection->close();
        }
    }
}
?>
