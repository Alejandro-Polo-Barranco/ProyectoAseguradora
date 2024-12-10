<?php
// Se verifica si no hay un usuario autenticado en la sesión.
// Si no está autenticado, se redirige a la página de identificación.
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php?view=identificacion");
    exit(); // Se detiene la ejecución del script después de la redirección.
}

// Se almacena el nombre del usuario autenticado en una variable.
$usuario = $_SESSION['usuario'];
echo "<h2>Mis Mensajes</h2>"; // Se muestra el título de la sección de mensajes.

// Se define la ruta del archivo que contiene los mensajes recibidos del usuario.
$archivoMensajes = "usuarios/$usuario/mensajes/mensajes_recibidos.txt";
// Se verifica si el archivo de mensajes existe.
if (file_exists($archivoMensajes)) {
    // Se cargan los mensajes desde el archivo en un array, ignorando líneas vacías.
    $mensajes = file(htmlspecialchars($archivoMensajes), FILE_IGNORE_NEW_LINES);
    // Se itera sobre cada mensaje y se muestra en un contenedor.
    foreach ($mensajes as $mensaje) {
        echo "<div class='mensaje'>$mensaje</div>"; // Se muestra cada mensaje en un div.
    }
} else {
    // Si no hay mensajes, se muestra un mensaje informando que no tiene mensajes.
    echo "<p>No tienes mensajes.</p>";
}

// Se muestra el título de la sección para enviar mensajes.
echo "<h3>Enviar Mensaje</h3>";
?>

<!-- Se inicia un formulario para enviar mensajes privados. -->
<form method="post" action="controladorMensajes.php">
    <label for="destinatario">Destinatario:</label>
    <select name="destinatario" required> <!-- Se proporciona un selector para elegir el destinatario. -->
        <?php
        // Se listan los usuarios disponibles para enviar mensajes, excluyendo el propio usuario.
        $usuarios = scandir("usuarios");
        foreach ($usuarios as $user) {
            if ($user !== '.' && $user !== '..' && $user !== $usuario) {
                // Se agrega una opción en el selector para cada usuario válido.
                echo "<option value='$user'>$user</option>";
            }
        }
        ?>
    </select>

    <label for="mensaje">Mensaje:</label>
    <textarea name="mensaje" required></textarea> <!-- Se proporciona un área de texto para el mensaje. -->
    <button type="submit" name="enviar">Enviar</button> <!-- Se agrega un botón para enviar el mensaje. -->
</form>
