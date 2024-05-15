<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
function seguridad()
{
    if (!isset($_SESSION['admin_Usuario']) && !isset($_SESSION['admin_Password'])) {
        header('Location: login-crud.php');
    }
}
