<!-- vistas/explorar.php -->
<h2>Explorar Publicaciones</h2> <!-- Se muestra el título de la sección de exploración. -->

<?php
// Se obtienen los nombres de las carpetas dentro del directorio 'usuarios'.
// Esto permite identificar a los usuarios que tienen publicaciones.
$usuarios = scandir('usuarios');

// Se itera sobre cada nombre de usuario encontrado en el directorio.
foreach ($usuarios as $usuario) {
    // Se filtran las entradas que no son directorios válidos.
    if ($usuario !== '.' && $usuario !== '..' && is_dir("usuarios/$usuario")) {
        // Se muestra el título de las publicaciones de cada usuario.
        echo "<h3>Publicaciones de $usuario</h3>";

        // Se obtienen todos los archivos de publicaciones de tipo '.txt' del usuario.
        $publicaciones = glob("usuarios/$usuario/*.txt");
        
        // Se itera sobre cada publicación encontrada.
        foreach ($publicaciones as $publicacion) {
            // Se lee el contenido de la publicación.
            $contenido = file_get_contents($publicacion);
            echo "<div class='publicacion'>"; // Se inicia un contenedor para la publicación.
            echo "<p>$contenido</p>"; // Se muestra el contenido de la publicación.

            // Se crea un formulario para agregar comentarios a la publicación.
            // Este formulario enviará los datos al controlador 'controladorBloc.php'.
            echo "<form method='post' action='controladorBloc.php'>";
            echo "<input type='hidden' name='publicacion' value='$publicacion'>"; // Se incluye la publicación a comentar.
            echo "<textarea name='comentario' placeholder='Añadir un comentario...' required></textarea>"; // Se proporciona un área de texto para el comentario.
            echo "<button type='submit' name='comentar'>Comentar</button>"; // Se añade un botón para enviar el comentario.
            echo "</form>";

            // Se define la ruta del archivo de comentarios relacionados con la publicación.
            $archivoComentarios = $publicacion . "_comentarios.txt";
            // Se verifica si el archivo de comentarios existe.
            if (file_exists($archivoComentarios)) {
                // Se cargan los comentarios existentes en un array.
                $comentarios = file($archivoComentarios, FILE_IGNORE_NEW_LINES);
                // Se itera sobre cada comentario existente y se muestra.
                foreach ($comentarios as $comentario) {
                    echo "<p class='comentario'>$comentario</p>"; // Se muestra cada comentario.
                }
            }

            echo "</div>"; // Se cierra el contenedor de la publicación.
        }
    }
}
?>
