<?PHP
// ini_set("session.cookie_lifetime","7200");
// ini_set("session.gc_maxlifetime","7200");
if (session_status() == PHP_SESSION_NONE) {
    session_start();
    $_SESSION['admin_Usuario'] = "";
    $_SESSION['admin_Password'] = "";
    unset($_SESSION['admin_Usuario']);
    unset($_SESSION['admin_Password']);
}

?>
<script>
    document.location.href = "../login-crud.php";
</script>