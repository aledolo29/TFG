<?php
if (session_status() == PHP_SESSION_NONE) session_start();

if (isset($_SESSION['admin_Usuario']) && isset($_SESSION['admin_Password'])) {
  header('Location: crudEmpleados.php');
}
if (isset($_GET['mensaje'])) {
  $mensaje = $_GET['mensaje'];
} else {
  $mensaje = "";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
  <link rel="stylesheet" href="../../build/css/loginCrudStyles.css" />
  <title>Log In</title>
</head>

<body>
  <div class="loginCrud container m-auto d-flex justify-content-center align-items-center">
    <div class="loginCrud__container d-flex flex-column justify-content-center align-items-center">
      <img src="../assets/media/logo_azul.png" alt="logo" class="img-fluid" />
      <h1 class="loginCrud__titulo text-primary text-center">
        INTERSTELLAR AIRLINES
      </h1>
      <div class="loginCrud__container_form container bg-white p-5 rounded-3 mt-3">
        <h3 class="text-center mb-5">Acceso Intranet</h3>
        <form action="clases/comprobarLoginCrud.php" method="post">
          <div>
            <label for="usuario" class="form-label fw-bold">Usuario</label>
            <input type="text" class="input form-control bg-light fs-4 rounded-3 border-0" name="admin_Usuario" autocomplete="off" required />
          </div>
          <div class="mt-3">
            <label for="password" class="form-label mt-3 fw-bold">Contraseña</label>
            <input type="password" class="input form-control bg-light fs-4 rounded-3 border-0" name="admin_Password" required />
          </div>
          <?php
          if ($mensaje != "") {
          ?>
            <div class="alert alert-danger mt-3" role="alert">
              <?= $mensaje ?>
            </div>
          <?php
          }
          ?>
          <div>
            <button type="submit" class="loginCrud__btn fw-bold mt-5 fs-5 rounded-3 py-3 w-100">
              ENTRAR
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <script src="js/jquery-3.7.1.min.js"></script>
</body>

</html>