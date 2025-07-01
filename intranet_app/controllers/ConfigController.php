<?php
require_once __DIR__ . '/../Core/BaseIntranetController.php';
require_once __DIR__ . '/../Core/Auth.php';
require_once __DIR__ . '/../models/GeneralConfig.php'; // Nuestro nuevo modelo de configuración
require_once __DIR__ . '/../models/AuditLog.php';

class ConfigController extends BaseIntranetController {
    private $generalConfigModel;
    private $auditLogModel;
    private $baseUrl = '/elangel_medical_center/admin';

    public function __construct() {
        $this->generalConfigModel = new GeneralConfig();
        $this->auditLogModel = new AuditLog();
    }

    /**
     * Muestra la página de configuración general de la base de datos.
     * Solo accesible para administradores.
     */
    public function index() {
        Auth::requireLogin($this->baseUrl . '/login', 'admin'); // Solo admin puede acceder
        
        $config = $this->generalConfigModel->readConfig();
        if ($config === null) {
            $this->setFlashMessage('error', 'No se pudo cargar la configuración actual de la base de datos principal. Verifica el archivo de configuración.');
            // Puedes establecer valores por defecto o redirigir
            $config = [
                'DB_HOST' => 'localhost',
                'DB_USER' => '',
                'DB_PASS' => '',
                'DB_NAME' => ''
            ];
        }

        $this->render('config/index.php', [
            'dbConfig' => $config,
            'flash_message' => $this->getFlashMessage(),
            'user' => Auth::user()
        ]);
    }

    /**
     * Procesa la actualización de la configuración de la base de datos principal.
     * Solo accesible para administradores.
     */
    public function update() {
        Auth::requireLogin($this->baseUrl . '/login', 'admin');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dbHost = trim($_POST['db_host'] ?? '');
            $dbUser = trim($_POST['db_user'] ?? '');
            $dbPass = $_POST['db_pass'] ?? ''; // La contraseña puede estar vacía
            $dbName = trim($_POST['db_name'] ?? '');

            // Validación básica
            if (empty($dbHost) || empty($dbUser) || empty($dbName)) {
                $this->setFlashMessage('error', 'Todos los campos de Host, Usuario y Nombre de la Base de Datos son requeridos.');
                $this->redirect($this->baseUrl . '/configuracion');
                return;
            }

            $newConfig = [
                'DB_HOST' => $dbHost,
                'DB_USER' => $dbUser,
                'DB_PASS' => $dbPass,
                'DB_NAME' => $dbName
            ];

            if ($this->generalConfigModel->writeConfig($newConfig)) {
                $this->setFlashMessage('success', 'Configuración de la base de datos principal actualizada exitosamente.');
                $this->auditLogModel->logAction(Auth::user()['id'], 'config_updated', "Configuración de DB principal actualizada por " . Auth::user()['username']);
            } else {
                $this->setFlashMessage('error', 'Error al actualizar la configuración de la base de datos principal. Revisa los permisos de escritura del archivo.');
            }
            $this->redirect($this->baseUrl . '/configuracion');
        } else {
            $this->redirect($this->baseUrl . '/configuracion');
        }
    }
}
