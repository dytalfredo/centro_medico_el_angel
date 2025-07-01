# **Documentación de la Arquitectura del Proyecto: Centro Médico "El Ángel"**

Este documento detalla la arquitectura del sistema de intranet del Centro Médico "El Ángel", el cual sigue un patrón de diseño **Modelo-Vista-Controlador (MVC)**. El objetivo es proporcionar una comprensión clara de cómo los diferentes componentes del sistema interactúan entre sí para procesar las solicitudes de los usuarios, gestionar los datos y presentar la información.

## **1\. Visión General de la Arquitectura MVC**

El patrón MVC divide la aplicación en tres componentes interconectados:

* **Modelo (Model):** Representa la lógica de negocio y los datos de la aplicación. Se encarga de la interacción con la base de datos, la validación de datos y las operaciones relacionadas con la información. Es independiente de la interfaz de usuario.  
* **Vista (View):** Es responsable de la presentación de los datos al usuario. Genera la interfaz de usuario (HTML en este caso) y no contiene lógica de negocio. Simplemente muestra lo que el Modelo le proporciona, bajo la dirección del Controlador.  
* **Controlador (Controller):** Actúa como intermediario entre el Modelo y la Vista. Recibe las solicitudes del usuario, las procesa (a menudo interactuando con uno o más Modelos para obtener o manipular datos) y luego selecciona la Vista adecuada para mostrar la respuesta.

Esta separación de responsabilidades facilita el desarrollo, la depuración y el mantenimiento del código, permitiendo que los cambios en una parte del sistema tengan un impacto mínimo en las otras.

## **2\. Componentes Clave y Estructura de Archivos**

La estructura de directorios del proyecto está organizada para reflejar el patrón MVC:

/elangel\_medical\_center/  
├── admin/  
│   └── index.php             \<- Punto de entrada principal para la intranet (router)  
├── intranet\_app/  
│   ├── Core/                 \<- Clases base y de utilidad del framework  
│   │   ├── Auth.php          \<- Manejo de autenticación y autorización  
│   │   ├── BaseIntranetController.php \<- Clase base para todos los controladores  
│   │   ├── IntranetDatabase.php \<- Conexión a la base de datos de la intranet  
│   │   └── PublicWebDatabase.php \<- (Si aplica) Conexión a la DB de la web pública  
│   ├── config/               \<- Archivos de configuración  
│   │   └── intranet\_database.php \<- Configuración de la base de datos  
│   ├── controllers/          \<- Todos los controladores de la aplicación  
│   │   ├── InvoiceController.php  
│   │   ├── DoctorController.php  
│   │   ├── MedicalServiceController.php  
│   │   ├── PatientController.php  
│   │   ├── UserController.php  (Nuevo)  
│   │   ├── ReportController.php (Nuevo)  
│   │   └── ...otros controladores  
│   ├── models/               \<- Todos los modelos de datos  
│   │   ├── Invoice.php  
│   │   ├── InvoiceItem.php  
│   │   ├── Patient.php  
│   │   ├── Doctor.php  
│   │   ├── MedicalService.php  
│   │   ├── ServiceDoctorIntranet.php  
│   │   ├── User.php          (Nuevo)  
│   │   ├── AuditLog.php  
│   │   └── ...otros modelos  
│   ├── views/                \<- Todas las vistas (plantillas HTML/PHP)  
│   │   ├── auth/  
│   │   │   └── login.php  
│   │   ├── invoices/  
│   │   │   └── index.php  
│   │   ├── doctors/  
│   │   │   └── index.php  
│   │   ├── medical\_services/  
│   │   │   └── index.php  
│   │   ├── patients/  
│   │   │   └── index.php  
│   │   ├── users/            (Nuevo)  
│   │   │   └── index.php  
│   │   ├── reports/          (Nuevo)  
│   │   │   └── index.php  
│   │   ├── layouts/          \<- Plantillas de diseño comunes (ej. header, footer)  
│   │   │   ├── admin\_layout.php  
│   │   │   └── ...  
│   │   └── ...otras vistas  
│   └── public/               \<- Activos públicos (CSS, JS, imágenes)  
│       ├── css/  
│       ├── js/  
│       └── img/  
└── .htaccess                 \<- Reglas de reescritura de URL

### **Descripción de Componentes:**

* **admin/index.php (Router Principal):** Este archivo es el punto de entrada para todas las solicitudes a la intranet. Contiene la lógica de enrutamiento que analiza la URL de la solicitud y despacha la petición al controlador y método apropiados. Utiliza expresiones regulares (preg\_match) para mapear URLs a acciones de controlador.  
* **Core/:**  
  * **Auth.php:** Centraliza la lógica de autenticación y autorización. Maneja el inicio de sesión, el cierre de sesión, la verificación de roles de usuario y la protección de rutas.  
  * **BaseIntranetController.php:** Proporciona una clase base para todos los controladores, incluyendo métodos comunes como render (para cargar vistas) y setFlashMessage/getFlashMessage (para mensajes temporales).  
  * **IntranetDatabase.php:** Implementa el patrón Singleton para gestionar una única conexión a la base de datos MySQL, asegurando que todas las interacciones con la DB utilicen la misma conexión.  
* **config/intranet\_database.php:** Contiene las credenciales y configuraciones para la conexión a la base de datos.  
* **controllers/:** Cada archivo en este directorio es un controlador que maneja un conjunto específico de funcionalidades (ej. InvoiceController.php gestiona todo lo relacionado con facturas).  
  * Reciben datos de la solicitud (GET, POST).  
  * Interactúan con uno o más modelos para realizar operaciones de datos.  
  * Pasan los datos procesados a la vista.  
  * Redirigen o renderizan una vista.  
* **models/:** Cada archivo aquí representa una tabla de la base de datos o una entidad de negocio (ej. Patient.php interactúa con la tabla patients).  
  * Contienen métodos para operaciones CRUD (Crear, Leer, Actualizar, Eliminar) y otras lógicas de negocio relacionadas con los datos.  
  * Son agnósticos de la interfaz de usuario.  
* **views/:** Contiene las plantillas PHP/HTML que generan la interfaz de usuario.  
  * Las vistas son "tontas"; solo muestran los datos que les son proporcionados por el controlador.  
  * Utilizan PHP para incrustar datos dinámicos y lógica de presentación (bucles, condicionales simples).  
  * Incorporan Tailwind CSS para el estilizado.  
  * Los modales para crear/editar elementos (facturas, médicos, servicios, usuarios) se incluyen directamente en la vista principal de la tabla para una experiencia de usuario fluida.  
* **public/:** Almacena archivos estáticos como hojas de estilo CSS (Tailwind CSS se carga desde CDN), scripts JavaScript y recursos de imagen.

## **3\. Flujo de Datos en la Aplicación**

El flujo de una solicitud típica sigue esta secuencia:

1. **Solicitud del Usuario:** El usuario navega a una URL (ej. /elangel\_medical\_center/admin/facturas).  
2. **admin/index.php (Router):**  
   * El archivo admin/index.php intercepta la solicitud.  
   * Analiza la URL para determinar qué controlador y qué método deben manejar la petición.  
   * Instancia el controlador apropiado (ej. InvoiceController).  
   * Llama al método correspondiente (ej. index()).  
3. **Controlador (ej. InvoiceController):**  
   * El método del controlador (ej. index()) recibe la solicitud.  
   * Puede extraer parámetros de la URL o del cuerpo de la solicitud (ej. filtros de búsqueda).  
   * Interactúa con uno o más Modelos (ej. Invoice.php, Patient.php) para obtener los datos necesarios.  
   * Prepara los datos para la vista (ej. formatea fechas, calcula totales).  
   * Llama al método render() de la clase BaseIntranetController, pasándole la ruta de la vista y los datos.  
4. **Vista (ej. invoices/index.php):**  
   * La vista recibe los datos del controlador.  
   * Utiliza PHP incrustado para mostrar los datos en una estructura HTML.  
   * No realiza lógica de negocio compleja ni interactúa directamente con la base de datos.  
   * Incluye JavaScript para interactividad del lado del cliente (ej. autocompletado, cálculos de totales, manejo de modales).  
5. **Respuesta al Usuario:** El HTML generado por la vista se envía de vuelta al navegador del usuario.

## **4\. Autenticación y Autorización (Auth.php)**

La clase Auth.php es fundamental para la seguridad del sistema:

* **requireLogin($redirectUrl, $requiredRole \= null):** Este método se utiliza en los controladores para asegurar que solo los usuarios autenticados y con el rol requerido puedan acceder a ciertas páginas. Si el usuario no cumple los requisitos, es redirigido a la página de inicio de sesión.  
* **login($username, $password):** Autentica a un usuario verificando sus credenciales contra la base de datos. Si tiene éxito, establece la sesión del usuario.  
* **logout():** Destruye la sesión del usuario.  
* **hasRole($role):** Verifica si el usuario actualmente autenticado tiene un rol específico.  
* **user():** Devuelve los datos del usuario actualmente autenticado.

## **5\. Interacción con la Base de Datos (IntranetDatabase.php)**

La clase IntranetDatabase.php maneja la conexión a la base de datos:

* **Patrón Singleton:** Asegura que solo haya una instancia de la conexión a la base de datos en toda la aplicación, optimizando los recursos.  
* **getConnection():** Devuelve la instancia de la conexión MySQLi.  
* Los modelos (Invoice.php, Doctor.php, etc.) utilizan esta conexión para ejecutar consultas SQL preparadas, lo que ayuda a prevenir ataques de inyección SQL.

## **6\. Mensajes Flash**

El sistema utiliza mensajes flash para proporcionar retroalimentación al usuario después de una acción (ej. "Factura creada exitosamente").

* **setFlashMessage($type, $message):** Almacena un mensaje en la sesión ($\_SESSION) con un tipo (ej. 'success', 'error').  
* **getFlashMessage():** Recupera el mensaje de la sesión y lo elimina, asegurando que el mensaje solo se muestre una vez.  
* Las vistas muestran estos mensajes en un div con clases de Tailwind CSS que cambian el color según el tipo de mensaje.

## **7\. Endpoints de API**

El router principal (admin/index.php) también define endpoints de API (ej. /api/patients/search, /api/doctors/details/{id}, /api/facturas/{id}/items). Estos endpoints son utilizados por JavaScript del lado del cliente (en las vistas) para realizar operaciones asíncronas (AJAX) sin recargar la página, mejorando la experiencia de usuario.

* Estos endpoints son manejados por métodos específicos en los controladores que devuelven respuestas JSON.  
* La autenticación y autorización se aplican también a los endpoints de API.

## **8\. Mejoras Recientes (Filtros)**

Recientemente, se han implementado filtros en las tablas de Facturas, Médicos, Servicios y Usuarios. Esta funcionalidad se integra perfectamente con la arquitectura MVC:

* **Vista:** Se añadió un formulario de filtros en la parte superior de cada tabla (invoices/index.php, doctors/index.php, medical\_services/index.php, users/index.php).  
  * Los campos de filtro (inputs, selects) envían sus valores a través de la URL (método GET).  
  * Se incluyó JavaScript para funcionalidades como autocompletado de pacientes en el filtro de facturas y para limpiar los filtros.  
* **Controlador:** Los controladores (InvoiceController.php, DoctorController.php, MedicalServiceController.php, UserController.php) ahora recuperan los parámetros de filtro de la superglobal $\_GET.  
  * Estos parámetros se pasan al método getAll() del modelo correspondiente.  
* **Modelo:** Los métodos getAll() en los modelos (Invoice.php, Doctor.php, MedicalService.php, User.php) se modificaron para construir dinámicamente la cláusula WHERE de la consulta SQL basada en los filtros recibidos.  
  * Esto permite que la misma función getAll() devuelva datos filtrados o todos los datos si no se aplican filtros.

Este enfoque mantiene la separación de responsabilidades: la vista maneja la entrada del usuario para los filtros, el controlador coordina la solicitud de datos filtrados, y el modelo se encarga de la lógica de la base de datos para recuperar los datos correctos.

## **Conclusión**

La arquitectura MVC del proyecto del Centro Médico "El Ángel" proporciona una base robusta y escalable para el desarrollo de la intranet. La clara separación de preocupaciones, el uso de clases base y el manejo centralizado de la autenticación y la base de datos contribuyen a un código más organizado, mantenible y seguro. Las recientes adiciones de filtros demuestran la flexibilidad del diseño para incorporar nuevas funcionalidades de manera eficiente.
