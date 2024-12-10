<!-- vista para el logeo o el registro de usuarios-->
<h2>Identificación de Usuario</h2>
<?php if (isset($_SESSION['error'])): ?>
    <p style="color: red;"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
<?php endif; ?>

<form method="post" action="controladorUsuario.php">
    <input type="text" name="nombre" placeholder="Nombre de usuario" required>
    <input type="password" name="password" placeholder="Contraseña" required>
    <button type="submit" name="identificar">Iniciar Sesión</button>
    <button type="submit" name="registrar">Registrarse</button>
</form>
