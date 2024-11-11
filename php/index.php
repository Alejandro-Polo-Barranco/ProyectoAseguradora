<?php
// Se incluye el archivo con las funciones necesarias para manejar la lógica de la aplicación
include("modelo/funciones.php");

// Se inicia la sesión para gestionar la autenticación de usuarios
session_start();

// Se maneja la acción de cerrar sesión
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy(); // Se destruye la sesión actual
    header("Location: index.php?view=identificacion"); // Se redirige a la vista de identificación
    exit();
}

// Se establece la vista que se cargará; por defecto es 'identificacion'
$view = isset($_GET['view']) ? $_GET['view'] : 'identificacion';

// Se incluye el menú de navegación
include("vistas/menu.php");

// Se carga la vista correspondiente según la acción solicitada
switch ($view) {
    case 'explorar':
        include("vistas/explorar.php");
        break;
    case 'identificacion':
        include("vistas/identificacion.php");
        break;
    case 'mostrarbloc':
        include("vistas/mostrarbloc.php");
        break;
    case 'mensajes':
        include("vistas/mensajes.php");
        break;
    case 'usuario':
        include("vistas/usuario.php");
        break;
    default:
        echo "<h2>Vista no encontrada.</h2>"; // Se muestra un mensaje de error si la vista no existe
        break;
}

// Se incluye el pie de página al final
include("vistas/footer.php");
?>
