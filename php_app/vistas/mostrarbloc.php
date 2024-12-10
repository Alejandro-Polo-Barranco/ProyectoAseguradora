<h2>Mi Muro</h2>
<!-- Se define un formulario para crear nuevas publicaciones. -->
<form method="post" action="controladorBloc.php" enctype="multipart/form-data">
    <textarea name="contenido" placeholder="¿Qué estás pensando?" required></textarea> <!-- Área de texto para que el usuario escriba su publicación. -->
    <input type="file" name="imagen" accept="image/*"> <!-- Permite subir archivos de imagenes // Tengo ciertos fallos aquí ya que en el mismo usuario 
    si muestra pero en otros muestra <img src='usuarios/maria/tres.jpg' /> -->
    <button type="submit" name="publicar">Publicar</button> <!-- Botón que envia la publicación. -->
</form>

<?php
$usuario = $_SESSION['usuario']; // Se obtiene el nombre del usuario autenticado.
$publicaciones = glob("usuarios/$usuario/*.txt"); // Se buscan todos los archivos de publicaciones del usuario en el directorio correspondiente.

foreach ($publicaciones as $publicacion) { // Se itera sobre cada publicación encontrada.
    $contenido = file_get_contents($publicacion); // Se lee el contenido de la publicación.
    echo "<div class='publicacion'>"; // Se crea un contenedor para la publicación.
    echo "<pre>$contenido</pre>"; // Se muestra el contenido de la publicación.

    //Formulario para comentarios en la publicación.
    echo "<form method='post' action='controladorBloc.php'>";
    echo "<input type='hidden' name='publicacion' value='$publicacion'>";
    echo "<textarea name='comentario' placeholder='Añadir un comentario...' required></textarea>";
    echo "<button type='submit' name='comentar'>Comentar</button>";
    echo "</form>";

    //Formulario para dar "Me gusta" a la publicación.
    echo "<form method='post' action='controladorBloc.php'>";
    echo "<input type='hidden' name='publicacion' value='basename($publicacion)'>";
    echo "<button type='submit' name='like'>Me gusta</button>";
    echo "</form>";

    // Formulario para eliminar la publicación.
    echo "<form method='post' action='controladorBloc.php'>";
    echo "<input type='hidden' name='publicacion' value='$publicacion'>";
    echo "<button type='submit' name='eliminar'>Eliminar Publicación</button>";
    echo "</form>";

    echo "</div>";
}
?>
