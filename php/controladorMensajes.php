<?php
// Se incluye el archivo con las funciones necesarias para manejar la lógica de la aplicación
include("modelo/funciones.php");

// Se inicia la sesión para gestionar la autenticación de usuarios
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php?view=identificacion"); // Se redirige a identificación si no hay sesión activa
    exit();
}

// Se verifica si la petición es de tipo POST y se ha enviado un mensaje
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enviar'])) {
    $remitente = $_SESSION['usuario']; // Se guarda el usuario que envía el mensaje
    $destinatario = $_POST['destinatario']; // Se obtiene el usuario destinatario
    $mensaje = $_POST['mensaje']; // Se obtiene el contenido del mensaje

    $directorioMensajes = "usuarios/$destinatario/mensajes"; // Se define la ruta del directorio de mensajes

    // Se crea el directorio de mensajes si no existe
    if (!is_dir($directorioMensajes)) {
        mkdir($directorioMensajes, 0777, true); // Se crea el directorio con permisos
    }

    $archivoMensajes = "$directorioMensajes/mensajes_recibidos.txt"; // Se define la ruta del archivo de mensajes
    $contenidoMensaje = "De: $remitente - Mensaje: $mensaje\n"; // Se define el formato del mensaje
    file_put_contents($archivoMensajes, $contenidoMensaje, FILE_APPEND); // Se guarda el mensaje en el archivo

    logAccion("El usuario $remitente envió un mensaje a $destinatario."); // Se registra la acción
    header("Location: index.php?view=mensajes"); // Se redirige a la vista de mensajes
    exit();
}
?>
