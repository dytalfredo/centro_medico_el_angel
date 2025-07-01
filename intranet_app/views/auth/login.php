<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Intranet El Ángel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        .degradado-horizontal {
  background: linear-gradient(to right,hsl(240, 93.90%, 6.50%), #006400);
}
    </style>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-xl shadow-2xl w-full max-w-md border border-blue-200">
        <div class="flex flex-col items-center mb-8">
            <img src="/elangel_medical_center/public/assets/img/logocompacto.png" alt="Logo El Ángel" class="h-16 mb-4">
            <h2 class="text-3xl font-bold text-blue-800">Iniciar Sesión</h2>
            <p class="text-gray-600 mt-2 text-center">Accede al sistema de facturación y honorarios.</p>
        </div>

        <?php if (isset($flash_message) && $flash_message): ?>
            <div class="<?php echo $flash_message['type'] === 'error' ? 'bg-red-100 border-red-400 text-red-700' : 'bg-green-100 border-green-400 text-green-700'; ?> border px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline"><?php echo htmlspecialchars($flash_message['message']); ?></span>
            </div>
        <?php endif; ?>

        <form action="/elangel_medical_center/admin/login" method="POST" class="space-y-6">
            <div>
                <label for="username" class="block text-gray-700 text-sm font-semibold mb-2">Usuario:</label>
                <input type="text" id="username" name="username" class="shadow-sm appearance-none border rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-200" placeholder="Tu nombre de usuario" required>
            </div>
            <div>
                <label for="password" class="block text-gray-700 text-sm font-semibold mb-2">Contraseña:</label>
                <input type="password" id="password" name="password" class="shadow-sm appearance-none border rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-200" placeholder="Tu contraseña" required>
            </div>
            <button type="submit" class="w-full degradado-horizontal hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg shadow-lg transition-colors duration-300 transform hover:scale-105">
                Acceder
            </button>
        </form>
    </div>
</body>
</html>
