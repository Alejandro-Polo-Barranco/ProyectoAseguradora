<?php
// Se incluye el archivo con las funciones necesarias para manejar la lógica de la aplicación
include("modelo/funciones.php");

// Se inicia la sesión para gestionar la autenticación de usuarios
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php?view=identificacion"); // Se redirige a identificación si no hay sesión activa
    exit();
}

$usuario = $_SESSION['usuario']; // Se guarda el usuario activo

// Se verifica si la petición es de tipo POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Se maneja la publicación de contenido
    if (isset($_POST['publicar'])) {
        $contenido = $_POST['contenido']; // Se obtiene el contenido de la publicación
        $imagen = $_FILES['imagen']; // Se obtiene la imagen adjunta
        $directorioUsuario = "usuarios/$usuario"; // Se define la ruta del directorio del usuario

        // Se crea el directorio del usuario si no existe
        if (!is_dir($directorioUsuario)) {
            mkdir($directorioUsuario, 0777, true); // Se crea el directorio con permisos
        }

        $nombreArchivo = $directorioUsuario . "/" . uniqid() . ".txt"; // Se genera un nombre de archivo único
        $publicacion = $contenido; // Se guarda el contenido de la publicación

        // Se maneja la carga de imagen
        if ($imagen['error'] === UPLOAD_ERR_OK) {
            $nombreImagen = $directorioUsuario . "/" . basename($imagen['name']);
            move_uploaded_file($imagen['tmp_name'], $nombreImagen); // Se mueve la imagen a la carpeta del usuario
            $publicacion .= "\n<img src='$nombreImagen' />"; // Se añade la imagen a la publicación
        }

        file_put_contents($nombreArchivo, $publicacion); // Se guarda la publicación en archivo
        logAccion("El usuario $usuario creó una nueva publicación."); // Se registra la acción
        header("Location: index.php?view=mostrarbloc"); // Se redirige al muro
        exit();
    // Se maneja la adición de comentarios
    } elseif (isset($_POST['comentar'])) {
        $archivoPublicacion = $_POST['publicacion']; // Se obtiene el archivo de la publicación
        $comentario = $_POST['comentario']; // Se obtiene el contenido del comentario
        $contenidoComentario = "\nComentario: $usuario - $comentario"; // Se define el formato del comentario
        
        // Se añade el comentario al final del archivo de la publicación original
        file_put_contents($archivoPublicacion, $contenidoComentario, FILE_APPEND); 
        
        logAccion("El usuario $usuario comentó en la publicación: $archivoPublicacion."); // Se registra la acción
        header("Location: " . $_SERVER['HTTP_REFERER']); // Se vuelve a la página anterior
        exit();
    // Se maneja la eliminación de publicaciones
    } elseif (isset($_POST['eliminar'])) {
        $archivoPublicacion = $_POST['publicacion']; // Se obtiene el archivo de la publicación
        if (file_exists($archivoPublicacion)) {
            unlink($archivoPublicacion); // Se elimina el archivo de la publicación
            logAccion("El usuario $usuario eliminó una publicación."); // Se registra la acción
        }
        header("Location: index.php?view=mostrarbloc"); // Se redirige al muro
        exit();
    // Se manejan los "Me gusta"
    } elseif (isset($_POST['like'])) {
        $archivoPublicacion = $_POST['publicacion']; // Se obtiene el archivo de la publicación
        agregarLike($usuario, basename($archivoPublicacion)); // Se añade "Me gusta"
        logAccion("El usuario $usuario dio 'Me gusta' a la publicación: $archivoPublicacion."); // Se registra la acción
        header("Location: " . $_SERVER['HTTP_REFERER']); // Se vuelve a la página anterior
        exit();
    }
}
?>
