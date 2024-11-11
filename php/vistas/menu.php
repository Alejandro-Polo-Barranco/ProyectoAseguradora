<div class="container"> <!-- Se crea un contenedor para el menú de navegación. -->
        <ul> <!-- Se inicia una lista desordenada para los elementos del menú. -->
            <?php if (isset($_SESSION['usuario'])): ?> <!-- Se verifica si hay un usuario autenticado en la sesión. -->
                <li><button onclick="location.href='index.php?view=mostrarbloc'">Mi Muro</button></li> <!-- Botón para acceder al muro del usuario. -->
                <li><button onclick="location.href='index.php?view=mensajes'">Mis Mensajes</button></li> <!-- Botón para acceder a los mensajes del usuario. -->
                <li><button onclick="location.href='index.php?action=logout'">Cerrar Sesión</button></li> <!-- Botón para cerrar sesión. -->
                <hr> 
                <?php
                    // Se listan los usuarios en el sistema, excluyendo 'usuarios.ini'.
                    $usuarios = scandir('usuarios'); // Se obtiene la lista de directorios en la carpeta 'usuarios'.
                    foreach ($usuarios as $usuario) { // Con un foreach itera sobre cada usuario encontrado.
                        if ($usuario !== '.' && $usuario !== '..' && $usuario !== $_SESSION['usuario'] && $usuario !== 'usuarios.ini') {
                            echo "<li><a href='index.php?view=usuario&user=$usuario'>$usuario</a></li>";
                        }
                    }
                ?>
            <?php else: ?> <!-- Si no hay usuario autenticado, se muestra la opción de identificación. -->
                <li><a href="index.php?view=identificacion">Identificación</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</div>
