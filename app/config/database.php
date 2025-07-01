<?php
// Configuración de la base de datos
define('DB_HOST', 'localhost'); // Host de la base de datos (generalmente 'localhost')
define('DB_NAME', 'elangel_medical_center'); // Nombre de la base de datos
define('DB_USER', 'root');     // Usuario de la base de datos
define('DB_PASS', '');         // Contraseña del usuario de la base de datos

// Opcional: para reportar errores de MySQLi
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
?>
