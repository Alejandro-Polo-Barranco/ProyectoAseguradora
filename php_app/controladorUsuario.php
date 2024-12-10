<?php
// Se incluye el archivo con las funciones necesarias para manejar la lógica de la aplicación
include("modelo/funciones.php");

// Se inicia la sesión para gestionar la autenticación de usuarios
session_start();

// Se verifica si la petición es de tipo POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Se maneja el inicio de sesión
    if (isset($_POST['identificar'])) {
        $nombre = $_POST['nombre']; // Se obtiene el nombre del usuario
        $password = $_POST['password']; // Se obtiene la contraseña

        // Se verifica las credenciales del usuario
        if (identificarUsuario($nombre, $password)) {
            incrementarVisitas($nombre); // Se aumenta el contador de visitas
            $_SESSION['usuario'] = $nombre; // Se guarda el usuario en la sesión
            header("Location: index.php?view=mostrarbloc"); // Se redirige al muro
            exit();
        } else {
            $_SESSION['error'] = "Nombre de usuario o contraseña incorrectos."; // Se establece un mensaje de error
            header("Location: index.php?view=identificacion"); // Se redirige a identificación
            exit();
        }
    // Se maneja el registro de un nuevo usuario
    } elseif (isset($_POST['registrar'])) {
        $nombre = $_POST['nombre']; // Se obtiene el nombre del usuario
        $password = $_POST['password']; // Se obtiene la contraseña

        if (registrarUsuario($nombre, $password)) {
            $_SESSION['usuario'] = $nombre; // Se guarda el usuario en la sesión
            header("Location: index.php?view=mostrarbloc"); // Se redirige al muro
            exit();
        } else {
            $_SESSION['error'] = "El usuario ya existe o ocurrió un problema al registrarse."; // Se establece un mensaje de error
            header("Location: index.php?view=identificacion"); // Se redirige a identificación
            exit();
        }
    }
}
?>
