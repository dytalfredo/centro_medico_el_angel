#!/bin/bash

# Define el directorio raíz de tu proyecto
PROJECT_ROOT="." # Asegúrate de ejecutar este script desde el directorio elangel_medical_center/

echo "Creando estructura de directorios y archivos vacíos para la Intranet..."

# --- Directorio admin/ (punto de entrada frontend) ---
mkdir -p "${PROJECT_ROOT}/admin/css"
mkdir -p "${PROJECT_ROOT}/admin/img"
touch "${PROJECT_ROOT}/admin/.htaccess"
touch "${PROJECT_ROOT}/admin/index.php"
echo "Creada la carpeta admin/ y sus archivos principales vacíos."

# --- Directorios y archivos intranet_app/ ---
mkdir -p "${PROJECT_ROOT}/intranet_app/config"
mkdir -p "${PROJECT_ROOT}/intranet_app/Core"
mkdir -p "${PROJECT_ROOT}/intranet_app/controllers"
mkdir -p "${PROJECT_ROOT}/intranet_app/models"
mkdir -p "${PROJECT_ROOT}/intranet_app/views/auth"
mkdir -p "${PROJECT_ROOT}/intranet_app/views/dashboard"
mkdir -p "${PROJECT_ROOT}/intranet_app/views/invoices"
mkdir -p "${PROJECT_ROOT}/intranet_app/views/doctors"
mkdir -p "${PROJECT_ROOT}/intranet_app/views/medical_services"
mkdir -p "${PROJECT_ROOT}/intranet_app/views/reports"
mkdir -p "${PROJECT_ROOT}/intranet_app/views/settings"
mkdir -p "${PROJECT_ROOT}/intranet_app/views/users"
mkdir -p "${PROJECT_ROOT}/intranet_app/views/partials"
echo "Creadas las carpetas de intranet_app/."

# Archivos de configuración
touch "${PROJECT_ROOT}/intranet_app/config/intranet_database.php"
echo "Creado: ${PROJECT_ROOT}/intranet_app/config/intranet_database.php"

# Archivos Core/
touch "${PROJECT_ROOT}/intranet_app/Core/Auth.php"
touch "${PROJECT_ROOT}/intranet_app/Core/BaseIntranetController.php"
touch "${PROJECT_ROOT}/intranet_app/Core/Router.php"
echo "Creados: Archivos en intranet_app/Core/"

# Archivos controllers/
touch "${PROJECT_ROOT}/intranet_app/controllers/AuthController.php"
touch "${PROJECT_ROOT}/intranet_app/controllers/DashboardController.php"
touch "${PROJECT_ROOT}/intranet_app/controllers/InvoiceController.php"
touch "${PROJECT_ROOT}/intranet_app/controllers/DoctorController.php"
touch "${PROJECT_ROOT}/intranet_app/controllers/MedicalServiceController.php"
touch "${PROJECT_ROOT}/intranet_app/controllers/ReportController.php"
touch "${PROJECT_ROOT}/intranet_app/controllers/SettingsController.php"
touch "${PROJECT_ROOT}/intranet_app/controllers/UserController.php"
echo "Creados: Archivos en intranet_app/controllers/"

# Archivos models/
touch "${PROJECT_ROOT}/intranet_app/models/IntranetDatabase.php"
touch "${PROJECT_ROOT}/intranet_app/models/User.php"
touch "${PROJECT_ROOT}/intranet_app/models/Doctor.php"
touch "${PROJECT_ROOT}/intranet_app/models/Patient.php"
touch "${PROJECT_ROOT}/intranet_app/models/MedicalService.php"
touch "${PROJECT_ROOT}/intranet_app/models/Invoice.php"
touch "${PROJECT_ROOT}/intranet_app/models/InvoiceItem.php"
touch "${PROJECT_ROOT}/intranet_app/models/DoctorPayment.php"
touch "${PROJECT_ROOT}/intranet_app/models/AuditLog.php"
touch "${PROJECT_ROOT}/intranet_app/models/ServiceDoctorIntranet.php"
echo "Creados: Archivos en intranet_app/models/"

# Archivos views/auth/
touch "${PROJECT_ROOT}/intranet_app/views/auth/login.php"
echo "Creado: intranet_app/views/auth/login.php"

# Archivos views/dashboard/
touch "${PROJECT_ROOT}/intranet_app/views/dashboard/index.php"
echo "Creado: intranet_app/views/dashboard/index.php"

# Archivos views/invoices/
touch "${PROJECT_ROOT}/intranet_app/views/invoices/index.php"
touch "${PROJECT_ROOT}/intranet_app/views/invoices/form_modal.php"
echo "Creados: Archivos en intranet_app/views/invoices/"

# Archivos views/doctors/
touch "${PROJECT_ROOT}/intranet_app/views/doctors/index.php"
touch "${PROJECT_ROOT}/intranet_app/views/doctors/form_modal.php"
echo "Creados: Archivos en intranet_app/views/doctors/"

# Archivos views/medical_services/
touch "${PROJECT_ROOT}/intranet_app/views/medical_services/index.php"
touch "${PROJECT_ROOT}/intranet_app/views/medical_services/form_modal.php"
echo "Creados: Archivos en intranet_app/views/medical_services/"

# Archivos views/reports/
touch "${PROJECT_ROOT}/intranet_app/views/reports/index.php"
echo "Creado: intranet_app/views/reports/index.php"

# Archivos views/settings/
touch "${PROJECT_ROOT}/intranet_app/views/settings/index.php"
echo "Creado: intranet_app/views/settings/index.php"

# Archivos views/users/
touch "${PROJECT_ROOT}/intranet_app/views/users/index.php"
echo "Creado: intranet_app/views/users/index.php"

# Archivos views/partials/
touch "${PROJECT_ROOT}/intranet_app/views/partials/intranet_header.php"
touch "${PROJECT_ROOT}/intranet_app/views/partials/intranet_footer.php"
touch "${PROJECT_ROOT}/intranet_app/views/partials/intranet_sidebar.php"
echo "Creados: Archivos en intranet_app/views/partials/"

echo "Estructura de la Intranet creada exitosamente."