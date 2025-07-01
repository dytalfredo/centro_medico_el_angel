<?php
        // Contraseña que queremos hashear
        $password = 'password';

        // Genera el hash de la contraseña
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        echo "El hash para '{$password}' es: <br>";
        echo "<textarea rows='3' cols='60'>{$hashed_password}</textarea><br>";
        echo "Copia este hash y úsalo para actualizar la tabla 'users' en tu base de datos 'elangel_intranet'.";
        ?>