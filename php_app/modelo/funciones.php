<?php
// modelo/funciones.php

// Función que registra un nuevo usuario en el sistema.
// Esta función recibe un nombre de usuario y una contraseña.
// Primero verifica si el nombre de usuario ya existe en el archivo.
// Si no existe, lo agrega al archivo de usuarios.
// Retorna true si el registro es exitoso y false si ya existe el usuario.
/* The `registrarUsuario` function in the provided PHP code is responsible for registering a new user
in the system. Here's a breakdown of what the function does: */
function registrarUsuario($nombre, $password) {
    $usuarios = file("usuarios/usuarios.ini", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES); // Se carga el archivo de usuarios en un array.

    // Se itera a través de los usuarios registrados.
    for ($i = 0; $i < count($usuarios); $i += 3) {
        // Se verifica si el nombre de usuario ya existe.
        if (strpos($usuarios[$i], "nombre = \"$nombre\"") !== false) {
            echo "El usuario ya existe. Elige otro nombre."; // Mensaje de error si el usuario existe.
            return false; // Se retorna false si el usuario ya está registrado.
        }
    }

    // Se crea un nuevo registro para el usuario.
    $nuevoUsuario = "nombre = \"$nombre\"\n";
    $nuevoUsuario .= "password = \"$password\"\n";
    $nuevoUsuario .= "visitas = 0\n\n";
    file_put_contents("usuarios/usuarios.ini", $nuevoUsuario, FILE_APPEND); // Se guarda el nuevo usuario en el archivo.
    echo "Usuario registrado con éxito."; // Mensaje de éxito.
    return true; // Se retorna true si el registro fue exitoso.
}

// Función que identifica a un usuario en el sistema.
// Esta función recibe un nombre de usuario y una contraseña y verifica si son correctos.
// Retorna true si la identificación es exitosa y false si hay un error.
function identificarUsuario($nombre, $password) {
    $usuarios = file("usuarios/usuarios.ini", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES); // Se carga el archivo de usuarios en un array.

    // Se itera a través de los usuarios registrados.
    for ($i = 0; $i < count($usuarios); $i += 3) {
        $nombreLinea = $usuarios[$i]; // Se obtiene la línea del nombre.
        $passwordLinea = $usuarios[$i + 1]; // Se obtiene la línea de la contraseña.
        preg_match('/nombre = "(.*)"/', $nombreLinea, $nombreMatch); // Se extrae el nombre.
        preg_match('/password = "(.*)"/', $passwordLinea, $passwordMatch); // Se extrae la contraseña.

        // Se verifica si el nombre y la contraseña coinciden.
        if ($nombreMatch[1] === $nombre && $passwordMatch[1] === $password) {
            echo "Usuario identificado exitosamente."; // Mensaje de éxito.
            return true; // Se retorna true si la identificación es exitosa.
        }
    }
    echo "Usuario o contraseña incorrectos."; // Mensaje de error si hay una coincidencia incorrecta.
    return false; // Se retorna false si la identificación falla.
}

// Función que incrementa el contador de visitas de un usuario.
// Esta función recibe el nombre del usuario y actualiza su contador de visitas en el archivo.
// Retorna true si se actualiza correctamente y false si no se encuentra el usuario.
function incrementarVisitas($nombre) {
    $usuarios = file("usuarios/usuarios.ini", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES); // Se carga el archivo de usuarios en un array.

    // Se itera a través de los usuarios registrados.
    for ($i = 0; $i < count($usuarios); $i += 3) {
        // Se verifica si el nombre de usuario coincide.
        if (strpos($usuarios[$i], "nombre = \"$nombre\"") !== false) {
            $visitasLinea = $usuarios[$i + 2]; // Se obtiene la línea de visitas.
            preg_match('/visitas = (\d+)/', $visitasLinea, $visitasMatch); // Se extrae el número de visitas.
            $nuevasVisitas = $visitasMatch[1] + 1; // Se incrementa el contador de visitas.
            $usuarios[$i + 2] = "visitas = $nuevasVisitas"; // Se actualiza la línea de visitas.
            file_put_contents("usuarios/usuarios.ini", implode("\n", $usuarios) . "\n"); // Se guarda el archivo actualizado.
            return true; // Se retorna true si se actualiza correctamente.
        }
    }
    return false; // Se retorna false si el usuario no se encuentra.
}

// Función que registra acciones en el archivo log.
// Esta función recibe un mensaje y lo escribe en un archivo de log, incluyendo la fecha y hora.
function logAccion($mensaje) {
    $file = fopen("log.txt", "a"); // Se abre el archivo de log en modo append.
    fwrite($file, date("Y-m-d H:i:s") . " - " . $mensaje . "\n"); // Se escribe el mensaje con la fecha y hora.
    fclose($file); // Se cierra el archivo.
}

// Función que agrega un "Me gusta" a una publicación.
// Esta función recibe el nombre de un usuario y el nombre de la publicación, 
// y actualiza el contador de "Me gusta" en el archivo de la publicación.
function agregarLike($usuario, $publicacion) {
    $rutaPublicacion = "usuarios/$usuario/$publicacion"; // Se define la ruta de la publicación.
    $rutaLikes = $rutaPublicacion . "_likes.txt"; // Se define la ruta para los likes.

    // Se crea el archivo de likes si no existe.
    if (!file_exists($rutaLikes)) {
        file_put_contents($rutaLikes, ""); // Se crea el archivo vacío.
    }

    $usuariosLikes = file($rutaLikes, FILE_IGNORE_NEW_LINES); // Se carga el archivo de likes en un array.
    // Se verifica si el usuario ya ha dado "Me gusta".
    if (!in_array($usuario, $usuariosLikes)) {
        $usuariosLikes[] = $usuario; // Se añade el usuario a la lista de likes.
        file_put_contents($rutaLikes, implode("\n", $usuariosLikes)); // Se guarda la lista de likes actualizada.

        $contenido = file_get_contents($rutaPublicacion); // Se obtiene el contenido de la publicación.
        preg_match('/Likes: (\d+)/', $contenido, $matches); // Se extrae el número de "Me gusta".
        $likes = isset($matches[1]) ? (int)$matches[1] + 1 : 1; // Se incrementa el número de "Me gusta".
        $contenido = preg_replace('/Likes: \d+/', "Likes: $likes", $contenido); // Se actualiza el contenido de la publicación.
        file_put_contents($rutaPublicacion, $contenido); // Se guarda la publicación actualizada.
    }
}
?>
