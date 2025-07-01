<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Centro Médico Integral El Ángel'; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:ital,opsz,wght@0,18..144,300..900;1,18..144,300..900&family=Roboto+Slab:wght@100..900&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: "Roboto Slab", serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        p{
            font-family: "Merriweather", serif;
        }
        .slider-container {
            position: relative;
         
            height: 70vh; /* Responsive height */
            overflow: hidden;
        }
        .slider-wrapper {
            display: flex;
            transition: transform 0.5s ease-in-out;
            height: 100%;
        }
        .slider-slide {
            min-width: 100vw;
            flex-shrink: 0;
            position: relative;
            height: 100%;
        }
        .slider-slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .slider-overlay {
            position: absolute;
            inset: 0;
            background-color: rgba(0, 0, 0, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .slider-button {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background-color: rgba(255, 255, 255, 0.7);
            padding: 0.5rem;
            border-radius: 9999px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            transition: background-color 0.3s ease-in-out;
            z-index: 10;
        }
        .slider-button:hover {
            background-color: rgba(255, 255, 255, 1);
        }
        .slider-button.left {
            left: 1rem;
        }
        .slider-button.right {
            right: 1rem;
        }
        .icon {
            width: 24px;
            height: 24px;
            stroke: currentColor;
            stroke-width: 2;
            fill: none;
            stroke-linecap: round;
            stroke-linejoin: round;
        }
        /* Ajuste para pantallas más pequeñas para asegurar visibilidad */
        @media (max-width: 768px) {
            .slider-container {
                height: 50vh; /* Altura más pequeña en móvil */
            }
        }
        /* Estilos para el acordeón de FAQ */
        .faq-answer {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
        }
        .faq-answer.expanded {
            /* Max height será ajustado por JS */
        }
        .logo{
            max-width: 200px;
        }
        .degradado-horizontal {
  background: linear-gradient(to right,hsl(240, 93.90%, 6.50%), #006400);
}
    </style>
</head>
<body class="font-inter antialiased  text-gray-800">
    <!-- Header y Navegación -->
    <header class="degradado-horizontal text-white shadow-md py-4 sticky top-0 z-50">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <img src="http://localhost/elangel_medical_center/public/assets/img/logogrande.png" alt="Logo del Centro Medico Integral " srcset="" class="logo">
<!--             <div class="text-3xl font-bold flex items-center gap-2">
                El Ángel
            </div> -->
            <nav>
                <ul class="flex space-x-6 text-lg font-medium">
                    <li><a href="<?php echo BASE_URL; ?>" class="hover:text-indigo-200 transition-colors duration-200 <?php echo ($currentPage === 'home') ? 'text-indigo-200 border-b-2 border-indigo-200 pb-1' : ''; ?>">Inicio</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/quienes-somos" class="hover:text-indigo-200 transition-colors duration-200 <?php echo ($currentPage === 'quienes-somos') ? 'text-indigo-200 border-b-2 border-indigo-200 pb-1' : ''; ?>">Quienes Somos</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/contacto" class="hover:text-indigo-200 transition-colors duration-200 <?php echo ($currentPage === 'contacto') ? 'text-indigo-200 border-b-2 border-indigo-200 pb-1' : ''; ?>">Contacto</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/faq" class="hover:text-indigo-200 transition-colors duration-200 <?php echo ($currentPage === 'faq') ? 'text-indigo-200 border-b-2 border-indigo-200 pb-1' : ''; ?>">FAQ</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/servicios" class="hover:text-indigo-200 transition-colors duration-200 <?php echo ($currentPage === 'servicios') ? 'text-indigo-200 border-b-2 border-indigo-200 pb-1' : ''; ?>">Servicios</a></li>
                    <li><a href="<?php echo INT_BASE_URL; ?>" class="hover:text-indigo-200 transition-colors duration-200">Intranet</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <main>
