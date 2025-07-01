<?php
// intranet_app/Core/PublicWebDatabase.php

class PublicWebDatabase {
    private static $instance = null;
    private $connection;

    private function __construct() {
        // La ruta al archivo de configuración de la base de datos principal de la web
        // Asume que 'intranet_app' y 'elangel_medical_center' están en el mismo nivel.
        $configPath = __DIR__ . '/../../app/config/database.php';

        if (!file_exists($configPath)) {
            error_log("CRITICAL ERROR: Archivo de configuración de la base de datos web no encontrado: " . $configPath);
            throw new Exception("Archivo de configuración de la base de datos web no encontrado.");
        }

        $config = require $configPath;

        $host = $config['DB_HOST'] ?? 'localhost';
        $user = $config['DB_USER'] ?? 'root';
        $pass = $config['DB_PASS'] ?? '';
        $db = $config['DB_NAME'] ?? 'elangel_medical_center';

        // Intentar establecer la conexión
        $this->connection = new mysqli($host, $user, $pass, $db);

        // Verificar la conexión
        if ($this->connection->connect_error) {
            error_log("CRITICAL ERROR: Fallo en la conexión a la base de datos web: " . $this->connection->connect_error);
            throw new Exception("Fallo en la conexión a la base de datos web: " . $this->connection->connect_error);
        }

        // Establecer el charset a utf8mb4 para evitar problemas con caracteres especiales
        $this->connection->set_charset("utf8mb4");
    }

    /**
     * Obtiene la única instancia de la clase PublicWebDatabase.
     * @return PublicWebDatabase
     */
    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new PublicWebDatabase();
        }
        return self::$instance;
    }

    /**
     * Obtiene la conexión mysqli.
     * @return mysqli
     */
    public function getConnection() {
        return $this->connection;
    }
}
