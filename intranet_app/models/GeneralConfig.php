<?php

class GeneralConfig {
    private $configFilePath;

    public function __construct() {
        // Ruta al archivo de configuración de la base de datos principal
        // Ajusta esta ruta si tu 'elangel_medical_center' no está en el mismo nivel que 'intranet_app'
        $this->configFilePath = __DIR__ . '../../app/config/database.php';
    }

    /**
     * Lee el archivo de configuración y devuelve sus datos.
     * @return array|null Los datos de configuración, o null si el archivo no existe o hay un error.
     */
    public function readConfig() {
        if (!file_exists($this->configFilePath)) {
            error_log("ERROR: El archivo de configuración de la base de datos principal no existe: " . $this->configFilePath);
            return null;
        }

        // Usar include para obtener el array de configuración
        // Suprimimos errores para manejarlo manualmente
        $config = @include $this->configFilePath;

        if (!is_array($config)) {
            error_log("ERROR: El archivo de configuración de la base de datos principal no devuelve un array: " . $this->configFilePath);
            return null;
        }

        return $config;
    }

    /**
     * Escribe nuevos datos en el archivo de configuración.
     * Se realiza de forma segura (escritura atómica).
     * @param array $newConfig Los nuevos datos de configuración.
     * @return bool True si la escritura fue exitosa, false si falla.
     */
    public function writeConfig(array $newConfig) {
        $tempFilePath = $this->configFilePath . '.tmp';
        $oldConfigContent = file_exists($this->configFilePath) ? file_get_contents($this->configFilePath) : '';

        // Formato del contenido del archivo PHP
        $content = "<?php\n";
        $content .= "// " . basename($this->configFilePath) . "\n";
        $content .= "// Este archivo contiene la configuración de la base de datos principal de la aplicación.\n";
        $content .= "return [\n";
        foreach ($newConfig as $key => $value) {
            $content .= "    '" . addslashes($key) . "' => '" . addslashes($value) . "',\n";
        }
        $content .= "];\n";

        // Intentar escribir en un archivo temporal
        if (file_put_contents($tempFilePath, $content, LOCK_EX) === false) {
            error_log("ERROR: No se pudo escribir en el archivo temporal de configuración: " . $tempFilePath);
            return false;
        }

        // Renombrar el archivo temporal al archivo original (operación atómica)
        if (!rename($tempFilePath, $this->configFilePath)) {
            error_log("ERROR: No se pudo renombrar el archivo temporal a la ruta final: " . $this->configFilePath);
            // Intentar restaurar el archivo original si existía
            if ($oldConfigContent !== '' && file_put_contents($this->configFilePath, $oldConfigContent) === false) {
                 error_log("CRITICAL ERROR: Fallo al restaurar el archivo de configuración original.");
            }
            return false;
        }

        // Asegurarse de que los permisos sean correctos (ej. legibles por el servidor web)
        // file_put_contents y rename suelen preservar permisos, pero es buena práctica asegurarlo.
        chmod($this->configFilePath, 0644); // rw-r--r--

        return true;
    }
}
