<?php
// Define la base de la URL para los enlaces de la intranet
// Asegúrate de que esta URL sea la URL real a tu carpeta 'admin'
$intranetBaseUrl = '/elangel_medical_center/admin';

// Obtiene el usuario logueado para mostrar su nombre
$loggedInUser = Auth::user(); // Accede a la sesión a través de la clase Auth

// Determina la página activa para resaltar el menú lateral
$requestUri = $_SERVER['REQUEST_URI'];
$currentPageSegment = basename(parse_url($requestUri, PHP_URL_PATH));
if ($currentPageSegment === 'admin' || $currentPageSegment === '') {
    $currentPageSegment = 'dashboard';
}

?>
<aside class="degradado-horizontal text-white w-50  p-6 pr-0 flex flex-col items-center h-screen shadow-lg">
    <div class="mb-8 bg-white p-2 rounded-2xl flex flex-col items-center">
        <img src="http://localhost/elangel_medical_center/public/assets/img/logogrande.png" alt="Logo Centro Médico El Ángel" class="h-12 saturado-200 mb-2">
        
    </div>

    <nav class="w-full flex-grow text-sm font-bold uppercase">
        <ul>
            <li class="mb-2  ">
                <a href="<?php echo $intranetBaseUrl; ?>/dashboard" class="flex items-center p-3 rounded-lg transition-colors  duration-200
                    <?php echo ($currentPageSegment == 'dashboard' ? 'bg-gray-100 shadow text-black' : 'hover:bg-gray-100 hover:text-black'); ?>">
                    <!-- Icono Lucide: Gauge (Medidor) -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"  class="mr-6"><path d="m12 14 4-4"/><path xmlns="http://www.w3.org/2000/svg" d="M21.062,6.729c-.035-.075-.088-.139-.154-.188-.174-.129-.389-.288-.636-.469V3.114c0-.276-.224-.5-.5-.5s-.5,.224-.5,.5v2.231c-2.437-1.752-6.221-4.346-7.272-4.346-1.364,0-7.164,4.242-8.909,5.542-.066,.05-.12,.115-.155,.191-.047,.103-1.165,2.58-1.165,7.727,0,2.249,.209,5.575,.448,7.117,.029,.19,.165,.346,.35,.402,.138,.042,3.442,1.021,9.432,1.021s9.292-.98,9.429-1.021c.185-.056,.32-.212,.35-.402,.24-1.545,.451-4.871,.451-7.117,0-5.191-1.119-7.629-1.167-7.73Zm-11.811,15.196v-4.8c0-1.575,1.232-2.856,2.748-2.856s2.748,1.281,2.748,2.856v4.8c-.84,.047-1.757,.075-2.748,.075s-1.908-.028-2.748-.075Zm11.584-.823c-.665,.167-2.409,.549-5.088,.757v-4.733c0-2.126-1.682-3.856-3.748-3.856s-3.748,1.73-3.748,3.856v4.733c-2.68-.208-4.425-.591-5.091-.757-.213-1.607-.391-4.581-.391-6.643,0-4.312,.823-6.678,1.028-7.198,3.486-2.591,7.479-5.249,8.2-5.261,.719,.019,4.713,2.676,8.204,5.263,.204,.513,1.026,2.85,1.026,7.197,0,2.021-.183,5.046-.394,6.643Z"/></svg>
                    Inicio
                </a>
            </li>
            <?php if (Auth::hasRole('admin') || Auth::hasRole('assistant')): ?>
            <li class="mb-2">
                <a href="<?php echo $intranetBaseUrl; ?>/facturas" class="flex items-center  p-3 rounded-lg transition-colors duration-200
                    <?php echo ($currentPageSegment == 'facturas' ? 'bg-gray-100 shadow text-black' : 'hover:bg-gray-100 hover:text-black'); ?>">
                    <!-- Icono Lucide: Wallet (Billetera) -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"  class="mr-6"><path d="M5,9h5v1H5v-1Zm5-4H5v1h5v-1ZM2,23h15v1H1V2.5C1,1.122,2.122,0,3.5,0H13.707l7.293,7.293v.707H13V1H3.5c-.827,0-1.5,.673-1.5,1.5V23ZM14,7h5.293L14,1.707V7Zm10,8v-.5c0-1.378-1.121-2.5-2.5-2.5h-1v-2h-1v2h-1c-1.379,0-2.5,1.122-2.5,2.5,0,1.37,.981,2.521,2.334,2.739l3.174,.51c.864,.139,1.492,.875,1.492,1.751,0,.827-.673,1.5-1.5,1.5h-3c-.827,0-1.5-.673-1.5-1.5v-.5h-1v.5c0,1.378,1.121,2.5,2.5,2.5h1v2h1v-2h1c1.379,0,2.5-1.122,2.5-2.5,0-1.37-.981-2.521-2.334-2.739l-3.174-.51c-.864-.139-1.492-.875-1.492-1.751,0-.827,.673-1.5,1.5-1.5h3c.827,0,1.5,.673,1.5,1.5v.5h1Zm-9.949-1c.039-.347,.112-.681,.226-1H5v6H14v-1H6v-4H14.051Z"/></svg>
                    Facturas
                </a>
            </li>
            <?php endif; ?>
            <?php if (Auth::hasRole('admin')): ?>
            <li class="mb-2">
                <a href="<?php echo $intranetBaseUrl; ?>/medicos" class="flex items-center  p-3 rounded-lg transition-colors duration-200
                    <?php echo ($currentPageSegment == 'medicos' ? 'bg-gray-100 shadow text-black' : 'hover:bg-gray-100 hover:text-black'); ?>">
                    <!-- Icono Lucide: User Cog (Usuario con Engranaje) -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"  class="mr-6"><path d="m12.021,12c3.309,0,6-2.691,6-6S15.33,0,12.021,0s-6,2.691-6,6,2.691,6,6,6Zm0-11c2.757,0,5,2.243,5,5s-2.243,5-5,5-5-2.243-5-5S9.265,1,12.021,1Zm4.479,13H7.5c-2.481,0-4.5,2.019-4.5,4.5v5.5h1v-5.5c0-1.93,1.57-3.5,3.5-3.5h.5v3.064c-.871.216-1.521.999-1.521,1.936,0,1.103.897,2,2,2s2-.897,2-2c0-.921-.63-1.691-1.479-1.922v-3.078h6v3.551c-1.14.232-2,1.242-2,2.449v3h1v-3c0-.827.673-1.5,1.5-1.5s1.5.673,1.5,1.5v3h1v-3c0-1.208-.86-2.217-2-2.449v-3.551h.5c1.93,0,3.5,1.57,3.5,3.5v5.5h1v-5.5c0-2.481-2.019-4.5-4.5-4.5Zm-7.021,6c0,.552-.448,1-1,1s-1-.448-1-1,.448-1,1-1,1,.448,1,1Z"/></svg>
                    Médicos
                </a>
            </li>
            <li class="mb-2">
                <a href="<?php echo $intranetBaseUrl; ?>/servicios" class="flex items-center  p-3 rounded-lg transition-colors duration-200
                    <?php echo ($currentPageSegment == 'servicios' ? 'bg-gray-100 shadow text-black' : 'hover:bg-gray-100 hover:text-black'); ?>">
                    <!-- Icono Lucide: Microscope (Microscopio) -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"  class="mr-6"><path d="m4,4c1.103,0,2-.897,2-2s-.897-2-2-2-2,.897-2,2,.897,2,2,2Zm0-3c.552,0,1,.449,1,1s-.448,1-1,1-1-.449-1-1,.448-1,1-1Zm5,4c1.103,0,2-.897,2-2s-.897-2-2-2-2,.897-2,2,.897,2,2,2Zm0-3c.552,0,1,.449,1,1s-.448,1-1,1-1-.449-1-1,.448-1,1-1Zm5,2c1.103,0,2-.897,2-2s-.897-2-2-2-2,.897-2,2,.897,2,2,2Zm0-3c.552,0,1,.449,1,1s-.448,1-1,1-1-.449-1-1,.448-1,1-1Zm-7.938,7.398c.287-1.39,1.545-2.398,2.989-2.398s2.7,1.009,2.989,2.398c.057.27-.069.602-.49.602-.231,0-.439-.162-.488-.398-.193-.928-1.039-1.602-2.011-1.602s-1.818.673-2.011,1.602c-.056.271-.324.44-.591.388-.271-.056-.444-.321-.388-.591Zm7.938-3.398c1.444,0,2.702,1.009,2.989,2.398.057.271-.114.611-.49.602-.231-.006-.439-.163-.488-.398-.192-.928-1.038-1.602-2.011-1.602-.28,0-.552.054-.808.161-.251.107-.548-.014-.654-.269-.105-.255.015-.547.27-.654.378-.158.779-.238,1.192-.238ZM1.011,7.398c.287-1.39,1.545-2.398,2.989-2.398.413,0,.814.08,1.192.238.255.106.375.399.27.654-.106.255-.403.375-.654.269-.256-.106-.527-.161-.808-.161-.973,0-1.818.673-2.011,1.602-.049.236-.259.431-.488.398-.405-.058-.547-.331-.49-.602Zm22.17,2.256c-.496-.452-1.141-.674-1.809-.652-.67.032-1.288.322-1.739.818l-3.732,4.102c-.314-1.108-1.335-1.922-2.543-1.922H3.5c-1.93,0-3.5,1.57-3.5,3.5v5c0,1.93,1.57,3.5,3.5,3.5h5.965c2.707,0,5.292-1.159,7.093-3.181l6.806-7.639c.911-1.022.829-2.604-.183-3.526Zm-.563,2.861l-6.806,7.639c-1.611,1.809-3.925,2.846-6.347,2.846H3.5c-1.379,0-2.5-1.122-2.5-2.5v-5c0-1.378,1.121-2.5,2.5-2.5h9.857c.905,0,1.643.737,1.643,1.642,0,.812-.606,1.511-1.398,1.624l-6.161.737c-.274.033-.47.282-.437.556.032.274.282.464.556.437l6.173-.739c1.021-.146,1.844-.878,2.145-1.824l4.496-4.94c.271-.298.643-.473,1.046-.492.398-.018.789.12,1.088.393.609.555.658,1.506.11,2.122Z"/></svg>
                    Servicios
                </a>
            </li>
            <li class="mb-2">
                <a href="<?php echo $intranetBaseUrl; ?>/usuarios" class="flex items-center  p-3 rounded-lg transition-colors duration-200
                    <?php echo ($currentPageSegment == 'usuarios' ? 'bg-gray-100 shadow text-black' : 'hover:bg-gray-100 hover:text-black'); ?>">
                    <!-- Icono Lucide: User Circle 2 (Círculo de Usuario 2) -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"  class="mr-6"><path xmlns="http://www.w3.org/2000/svg" d="m9,16v-2.5c0-1.378,1.121-2.5,2.5-2.5h5c1.379,0,2.5,1.122,2.5,2.5v2.5h-1v-2.5c0-.827-.673-1.5-1.5-1.5h-5c-.827,0-1.5.673-1.5,1.5v2.5h-1Zm2-9c0-1.654,1.346-3,3-3s3,1.346,3,3-1.346,3-3,3-3-1.346-3-3Zm1,0c0,1.103.897,2,2,2s2-.897,2-2-.897-2-2-2-2,.897-2,2ZM22,1.5v22.5H4.5c-1.379,0-2.5-1.122-2.5-2.5V2.5c0-1.378,1.121-2.5,2.5-2.5h16c.827,0,1.5.673,1.5,1.5Zm-1.5-.5H7v18h14V1.5c0-.276-.225-.5-.5-.5ZM3,2.5v17.001c.418-.315.938-.501,1.5-.501h1.5V1h-1.5c-.827,0-1.5.673-1.5,1.5Zm18,20.5v-3H4.5c-.827,0-1.5.673-1.5,1.5s.673,1.5,1.5,1.5h16.5Z"/></svg>
                    Usuarios
                </a>
            </li>
            
            <?php endif; ?>
            <?php if (Auth::hasRole('admin')): ?>
            <li class="mb-2">
                <a href="<?php echo $intranetBaseUrl; ?>/reportes" class="flex items-center  p-3 rounded-lg transition-colors duration-200
                    <?php echo ($currentPageSegment == 'reportes' ? 'bg-gray-100 shadow text-black' : 'hover:bg-gray-100 hover:text-black'); ?>">
                    <!-- Icono Lucide: Trending Up (Tendencia al Alza) -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"  class="mr-6"><path xmlns="http://www.w3.org/2000/svg" d="m21.641 7.423c-.017-.109-.07-.209-.149-.285l-6.039-5.745c-.068-.064-.154-.109-.246-.128-.873-.176-1.952-.265-3.207-.265-4.561 0-8.343.802-8.501.836-.184.04-.331.18-.378.361-.046.173-1.12 4.301-1.12 9.803l.003.506c.003.276.223.476.506.494.276-.003.498-.229.494-.506l-.003-.494c0-4.538.77-8.201 1.014-9.247.989-.188 4.257-.753 7.986-.753.748 0 1.435.037 2.047.104-.171 3.696.231 5.615.249 5.697.043.198.202.35.402.385.097.018 1.825.315 3.986.315.66 0 1.362-.037 2.068-.109.155 1.207.248 2.397.248 3.608 0 4.53-.641 7.904-.846 8.869-.975.27-4.393 1.131-8.154 1.131s-7.062-.864-7.986-1.131c-.103-.401-.306-1.254-.501-2.45-.045-.272-.302-.462-.574-.412-.272.044-.458.302-.413.574.288 1.76.586 2.785.598 2.828.047.159.169.284.327.335.159.052 3.953 1.256 8.55 1.256s8.557-1.202 8.724-1.253c.168-.052.297-.188.339-.359.038-.156.937-3.891.937-9.388 0-1.536-.118-3.033-.359-4.577zm-6.426-.169c-.1-.661-.284-2.313-.181-4.879l5.314 5.055c-2.194.184-4.321-.061-5.133-.176zm-9.409 8.746h-4.306c-.276 0-.5-.224-.5-.5s.224-.5.5-.5h3.931c.438-1.285 1.479-2.443 2.317-2.932.241-.146.579-.047.699.208l2.732 5.464c.85-.695 1.567-1.604 1.895-2.426.076-.189.26-.314.464-.314h2.961c.276 0 .5.224.5.5s-.224.5-.5.5h-2.635c-.599 1.246-1.702 2.308-2.582 2.912-.241.173-.604.078-.73-.188l-2.737-5.476c-.639.547-1.309 1.471-1.523 2.368-.054.225-.255.384-.486.384z"/></svg>
                    Reportes
                </a>
            </li>
            <?php endif; ?>
        </ul>
    </nav>

    <div class="mt-auto ">
        <p class="text-xs text-blue-200 text-center mt-4">© 2025 CMIEA</p>
    </div>
</aside>
