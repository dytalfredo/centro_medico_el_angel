<?php
// Define la base de la URL para los enlaces de la intranet
// Asegúrate de que esta URL sea la URL real a tu carpeta 'admin'
$intranetBaseUrl = '/elangel_medical_center/admin';

// Obtiene el usuario logueado para mostrar su nombre
$loggedInUser = Auth::user();

// Determina la página activa para resaltar el menú lateral
$requestUri = $_SERVER['REQUEST_URI'];
$currentPageSegment = basename(parse_url($requestUri, PHP_URL_PATH));
// Si la URI es '/admin/', o '/admin/dashboard', el segmento es 'dashboard'
if ($currentPageSegment === 'admin' || $currentPageSegment === '') {
    $currentPageSegment = 'dashboard';
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - Intranet El Ángel' : 'Intranet El Ángel'; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: "Roboto Slab", serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            margin: 0;
            padding: 0;
            display: flex;
            min-height: 100vh;
            flex-direction: column;
            height: 100vh;
            overflow: hidden;
        }

        p{
            font-family: "Merriweather", serif;
        }
        #main-content {
            flex-grow: 1; /* Permite que el contenido principal ocupe el espacio restante */
            padding: 0rem 1.5rem 0rem 1.5rem; /* p-6 */
        }
        /* Estilos del modal */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.75);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }
        .modal-overlay.show {
            opacity: 1;
            visibility: visible;
        }
        .modal-content {
            background-color: white;
            padding: 2rem;
            border-radius: 0.75rem; /* rounded-xl */
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); /* shadow-2xl */
            width: 90%;
            max-width: 800px; /* Ancho máximo para el modal de formulario */
            max-height: 90vh; /* Altura máxima para permitir scroll */
            overflow-y: auto; /* Habilitar scroll si el contenido es largo */
            transform: translateY(-20px);
            opacity: 0;
            transition: transform 0.3s ease-out, opacity 0.3s ease-out;
        }
        .modal-overlay.show .modal-content {
            transform: translateY(0);
            opacity: 1;
        }
        .degradado-horizontal {
  background: linear-gradient(to bottom,hsl(240, 93.90%, 6.50%), #006400);
}
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex ">
        <!-- Sidebar -->
        <?php require_once __DIR__ . '/intranet_sidebar.php'; ?>

        <!-- Contenido principal y Header superior -->
        <div class="flex flex-col flex-1">
            <header class="bg-white shadow py-2 px-8 flex justify-between items-center z-10">
            <a href="<?php echo $intranetBaseUrl; ?>/configuracion-web" class="flex items-center  py-2 pr-4 rounded-lg transition-all duration-200
                    <?php echo ($currentPageSegment == 'configuracion-web' ? 'bg-blue-700 text-white shadow' : 'hover:bg-blue-700 hover:text-white'); ?>">
                    <!-- Icono Lucide: Cog (Engranaje) -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"  class="mr-3"><path xmlns="http://www.w3.org/2000/svg" d="m23.2 14.608c.103-.387.047-.791-.155-1.137-.392-.704-1.363-.961-2.051-.54l-.642.374c-.537-.488-1.166-.851-1.853-1.067v-.738c0-.827-.673-1.5-1.5-1.5s-1.5.673-1.5 1.5v.738c-.687.216-1.315.579-1.853 1.067l-.642-.374c-.688-.421-1.659-.165-2.051.54-.421.688-.166 1.659.539 2.052l.646.376c-.094.385-.14.748-.14 1.101s.046.716.14 1.1l-.646.377c-.705.392-.96 1.364-.539 2.052.392.705 1.363.961 2.051.539l.642-.374c.536.489 1.166.852 1.853 1.068v.738c0 .827.673 1.5 1.5 1.5s1.5-.673 1.5-1.5v-.738c.687-.216 1.316-.579 1.853-1.068l.641.374c.69.421 1.659.166 2.052-.539.421-.688.166-1.659-.539-2.052l-.646-.377c.094-.384.14-.747.14-1.1s-.046-.716-.14-1.101l.646-.376c.347-.202.593-.527.694-.915zm-.967-.254c-.034.129-.116.237-.231.304l-.979.571c-.201.118-.295.359-.225.582.138.439.202.817.202 1.188s-.064.749-.202 1.187c-.07.223.022.464.225.582l.979.572c.115.067.197.176.231.305s.016.264-.052.379c-.132.235-.451.32-.684.18l-.972-.567c-.199-.117-.457-.082-.616.09-.55.586-1.249.989-2.021 1.165-.228.052-.389.254-.389.487v1.12c0 .276-.225.5-.5.5s-.5-.224-.5-.5v-1.12c0-.233-.161-.436-.389-.487-.772-.176-1.472-.579-2.021-1.165-.097-.104-.229-.158-.364-.158-.087 0-.174.022-.252.068l-.973.567c-.23.141-.552.055-.683-.18-.141-.23-.055-.553.18-.684l.979-.572c.202-.118.295-.359.225-.582-.138-.438-.202-.816-.202-1.187s.064-.749.202-1.188c.07-.223-.023-.464-.225-.582l-.979-.571c-.235-.13-.32-.454-.18-.684.129-.235.458-.319.684-.18l.972.567c.199.117.457.081.616-.089.55-.586 1.249-.989 2.021-1.165.228-.052.389-.254.389-.487v-1.12c0-.276.225-.5.5-.5s.5.224.5.5v1.12c0 .233.161.436.389.487.772.176 1.472.579 2.021 1.165.159.17.417.207.616.089l.972-.567c.362-.23.862.146.735.56zm1.767-4.793v.939c0 .276-.224.5-.5.5s-.5-.224-.5-.5v-.939c0-1.166-.576-2.25-1.542-2.901l-7.5-5.061c-1.189-.803-2.727-.803-3.916 0l-7.5 5.061c-.966.651-1.542 1.736-1.542 2.901v9.939c0 1.93 1.57 3.5 3.5 3.5h7c.276 0 .5.224.5.5s-.224.5-.5.5h-7c-2.481 0-4.5-2.019-4.5-4.5v-9.939c0-1.499.741-2.893 1.983-3.73l7.5-5.061c1.527-1.031 3.504-1.032 5.033 0l7.5 5.061c1.242.837 1.983 2.232 1.983 3.73zm-7 5.439c-1.103 0-2 .897-2 2s.897 2 2 2 2-.897 2-2-.897-2-2-2zm0 3c-.552 0-1-.449-1-1s.448-1 1-1 1 .449 1 1-.448 1-1 1z"/></svg>
                    Configuración de pagina web
                </a>    
                <div class="flex items-center space-x-4 text-sm">
                     <!-- Display de la tasa de cambio -->
                      
                     <div class="text-gray-700 text-sm flex items-center space-x-2 bg-gray-100 px-3 py-1 rounded-full shadow-inner">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-dollar-sign text-green-600">
                            <line x1="12" x2="12" y1="2" y2="22"/>
                            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                        </svg>
                        <span id="headerExchangeRate">Cargando Tasa...</span>
                    </div>
                    <span class="text-gray-700 text-xs">Bienvenido, <span class="font-semibold"><?php echo htmlspecialchars($loggedInUser['full_name'] ?? $loggedInUser['username']); ?></span></span>
                    <a href="<?php echo $intranetBaseUrl; ?>/logout" class="bg-red-900 hover:bg-red-600 text-xs text-white font-semibold py-2 px-4 rounded-md transition-colors duration-200">
                        Cerrar Sesión
                    </a>
                </div>
            </header>

            <main id="main-content" class="flex-1 overflow-y-auto bg-gray-100">
                <!-- Flash Messages -->
                <?php if (isset($flash_message) && $flash_message): ?>
                    <div class="<?php echo $flash_message['type'] === 'error' ? 'bg-red-100 border-red-400 text-red-700' : 'bg-green-100 border-green-400 text-green-700'; ?> border px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline"><?php echo htmlspecialchars($flash_message['message']); ?></span>
                    </div>
                <?php endif; ?>
