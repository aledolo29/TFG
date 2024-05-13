<?PHP
// ini_set("session.cookie_lifetime","7200");
// ini_set("session.gc_maxlifetime","7200");
if (session_status() == PHP_SESSION_NONE) {
    session_start();
    $_SESSION['empl_Usuario'] = "";
    $_SESSION['empl_Password'] = "";
    unset($_SESSION['empl_Usuario']);
    unset($_SESSION['empl_Password']);
}

?>
<script>
    document.location.href = "../login-crud.html";
</script>