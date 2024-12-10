<?php
// Se verifica si se ha especificado un usuario en la URL.
if (!isset($_GET['user'])) {
    echo "<h2>Usuario no especificado</h2>"; // Se muestra un mensaje indicando que no se ha especificado el usuario.
    exit(); // Se detiene la ejecución del script.
}

// Se obtiene el nombre del otro usuario de la URL y se evita la inyección de HTML.
$otroUsuario = htmlspecialchars($_GET['user']); 
echo "<h2>Muro de $otroUsuario</h2>"; // Se muestra el encabezado del muro del usuario específico.

// Se obtienen todas las publicaciones del usuario específico.
$publicaciones = glob("usuarios/" . basename($otroUsuario) . "/*.txt"); // Se busca en el directorio del usuario.

foreach ($publicaciones as $publicacion) {
    // Esto evita la inyección de HTML.
    $contenido = htmlspecialchars(file_get_contents($publicacion)); 

    echo "<div class='publicacion'>"; // Se crea un contenedor para la publicación.
    echo "<pre>$contenido</pre>"; // Se muestra el contenido de la publicación en formato preformateado.

    // Formulario para permitir que otros usuarios añadan comentarios a la publicación.
    echo "<form method='post' action='controladorBloc.php' class='comentario-form'>";
    echo "<input type='hidden' name='publicacion' value='" . htmlspecialchars(basename($publicacion)) . "'>"; // Se añade un campo oculto con el nombre de la publicación.
    echo "<textarea name='comentario' placeholder='Añadir un comentario...' required></textarea>"; // Se incluye un área de texto para el comentario.
    echo "<button type='submit' name='comentar'>Comentar</button>"; // Se muestra un botón para enviar el comentario.
    echo "</form>";

    //Formulario para dar "Me gusta" a la publicación.
    echo "<form method='post' action='controladorBloc.php' class='like-form'>";
    echo "<input type='hidden' name='publicacion' value='" . htmlspecialchars(basename($publicacion)) . "'>"; // Se añade un campo oculto con el nombre de la publicación.
    echo "<button type='submit' name='like'>Me gusta</button>"; // Se muestra un botón para dar "Me gusta".
    echo "</form>";

    echo "</div>"; // Se cierra el contenedor de la publicación.
}
?>
