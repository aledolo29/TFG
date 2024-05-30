<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include_once "clases/conexion.php";
include_once "clases/usuario.php";
include_once "clases/seguridad.php";

// Comprobar si el usuario ha iniciado sesión
seguridad();

$conexion = new conexion();
$usuario = new usuario();

$mensajeCorrecto = "";
$mensajeError = "";

if (isset($_GET['mensajeError'])) {
    $mensajeError = $_GET['mensajeError'];
}
if (isset($_GET['mensajeCorrecto'])) {
    $mensajeCorrecto = $_GET['mensajeCorrecto'];
}

// MODIFICAR USUARIO
if (isset($_POST['aux_modificar_usuario'])) {
    $usuario_Id = $_POST['aux_modificar_usuario'];
    $usuario_Login = $_POST['usuario_Login'];
    $usuario_DNI = $_POST['usuario_DNI'];
    $usuario_Password = $_POST['usuario_Password'];

    // Comprobar si ya existe un usuario con el mismo DNI o Login
    $campos = ['usuario_DNI' => $usuario_DNI, 'usuario_Login' => $usuario_Login];
    foreach ($campos as $campo => $valor) {
        $consultaModificacion = "WHERE $campo = '$valor' AND usuario_Id != $usuario_Id";
        $resModificacion = $usuario->obtenerConFiltro($consultaModificacion, "");
        $tuplaComprobarModificacion = $conexion->BD_GetTupla($resModificacion);
        if ($tuplaComprobarModificacion !== null) {
            $campo = strtolower(str_replace("usuario_", "", $campo));
            $mensajeError = "Error al modificar el usuario. Ya existe un usuario con ese $campo";
            header('Location: crudDatosPersonales.php?mensajeError=' . $mensajeError);
            exit();
        }
    }
    $resModificacion = $usuario->modificar($usuario_Id, $usuario_Login, $usuario_Password,  $usuario_DNI, "Si");
    if ($resModificacion) {
        $_SESSION['admin_Usuario'] = $usuario_Login;
        $_SESSION['admin_Password'] = $usuario_Password;
        $mensajeCorrecto = "Usuario $usuario_Logins modificado correctamente";
        header('Location: crudDatosPersonales.php?mensajeCorrecto=' . $mensajeCorrecto);
    } else {
        $mensajeError = "Error al modificar el usuario $usuario_Login";
        header('Location: crudDatosPersonales.php?mensajeError=' . $mensajeError);
    }
}

// OBTENER DATOS DEL USUARIO
$condincionUsuario = "WHERE usuario_Login = '" . $_SESSION['admin_Usuario'] . "' AND usuario_Password = '" . $_SESSION['admin_Password'] . "'";
$resUsuario = $usuario->obtenerConFiltro($condincionUsuario, "");
$tuplaUsuario = $conexion->BD_GetTupla($resUsuario);
if ($tuplaUsuario === null) {
    $mensajeError = "Error al obtener los datos del usuario";
    header('Location: crudEmpleados.php?mensajeError=' . $mensajeError);
    exit();
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Crud Datos Personales</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" href="js/plugins/datatables/datatables.bundle.css" />
    <link rel="stylesheet" href="../../build/css/crudStyles.css" type="text/css" />
</head>

<body class="crud">
    <!-- HEADER -->
    <header>
        <nav class="crud__header navbar navbar-expand-md bg-primary p-0">
            <div class="container-fluid">
                <a class="navbar-brand" href="crudEmpleados.php">
                <img src="../../build/assets/media/logo.png" alt="logo" class="crud__header__logo img-fluid" />
                </a>
                <div class="offcanvas offcanvas-end bg-primary" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
                    <div class="offcanvas-header">
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <ul class="navbar-nav w-auto d-flex justify-content-between">
                            <li class="nav-item mx-3">
                                <a class="crud__header__link nav-link" aria-current="page" href="crudClientes.php">Clientes</a>
                            </li>
                            <li class="nav-item mx-3">
                                <a class="crud__header__link nav-link" aria-current="page" href="crudEmpleados.php">Empleados</a>
                            </li>
                            <li class="nav-item mx-3">
                                <a class="crud__header__link nav-link" aria-current="page" href="crudNominas.php">Nóminas</a>
                            </li>
                            <li class="nav-item mx-3">
                                <a class="crud__header__link nav-link" aria-current="page" href="crudUsuariosMaestros.php">Usuarios maestros</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="d-flex">
                    <div class="dropdown">
                        <a href="" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="../../build/assets/media/avatar.png" alt="avatar" class="crud__header__avatar img-fluid me-3" />
                        </a>
                        <ul class="dropdown-menu dropdown_avatar p-4 rounded-4">
                            <li><a class="dropdown-item fs-4 mb-2" href="#"><i class="bi bi-pencil-square fs-2 me-3"></i>Datos personales</a></li>
                            <li><a class="dropdown-item fs-4" href="clases/desconectar.php"><i class="bi bi-box-arrow-left fs-2 me-3"></i>Cerrar Sesión</a></li>
                        </ul>
                    </div> <button class="navbar-toggler border-0 text-white" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-expanded="false" aria-label="Toggle navigation">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path d="M13 11H3C2.4 11 2 10.6 2 10V9C2 8.4 2.4 8 3 8H13C13.6 8 14 8.4 14 9V10C14 10.6 13.6 11 13 11ZM22 5V4C22 3.4 21.6 3 21 3H3C2.4 3 2 3.4 2 4V5C2 5.6 2.4 6 3 6H21C21.6 6 22 5.6 22 5Z" fill="currentColor"></path>
                            <path opacity="0.3" d="M21 16H3C2.4 16 2 15.6 2 15V14C2 13.4 2.4 13 3 13H21C21.6 13 22 13.4 22 14V15C22 15.6 21.6 16 21 16ZM14 20V19C14 18.4 13.6 18 13 18H3C2.4 18 2 18.4 2 19V20C2 20.6 2.4 21 3 21H13C13.6 21 14 20.6 14 20Z" fill="currentColor"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </nav>
    </header>

    <!-- Mensaje correcto/error -->
    <?php
    if ($mensajeCorrecto !== "") {
    ?>
        <div id="mensajeCorrecto" class="alert alert-success mt-3 py-4">
            <?= $mensajeCorrecto ?>
        </div>
    <?php
    }
    if ($mensajeError !== "") {
    ?>
        <div id="mensajeError" class="alert alert-danger mt-3 py-4">
            <?= $mensajeError ?>
        </div>
    <?php
    }
    ?>

    <!-- CONTENIDO -->
    <div class="d-flex d-md-block justify-content-center align-items-center">
        <div class="container crud__datosPersonales m-auto mt-5 mx-5 p-5 rounded-3">
            <div>
                <h3>Modificar datos personales</h3>
                <form action="crudDatosPersonales.php" method="post">
                    <input type="hidden" name="aux_modificar_usuario" value="<?= $tuplaUsuario['usuario_Id'] ?>">
                    <div class="row">
                        <div class="col-md-6 d-flex flex-column mt-3">
                            <label for="login" class="form-label">Login:</label>
                            <input type="text" name="usuario_Login" id="login" class="crud__input__datosPersonales fs-4 p-3 rounded-4" value="<?= $tuplaUsuario['usuario_Login'] ?>" required>
                        </div>
                        <div class="col-md-6 d-flex flex-column mt-3">
                            <label for="dni" class="form-label">DNI:</label>
                            <input type="text" name="usuario_DNI" id="dni" class="crud__input__datosPersonales fs-4 p-3 rounded-4" value="<?= $tuplaUsuario['usuario_DNI'] ?>" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 d-flex flex-column mt-3">
                            <label for="password" class="form-label">Contraseña:</label>
                            <div class="input-group">
                                <input style="width: 85%;" type="password" class="password_texto crud__input__datosPersonales fs-4 p-3 rounded-start-4" id="password" name="usuario_Password" value="<?= $tuplaUsuario['usuario_Password'] ?>" required />
                                <div style="width: 15%;" class="input-group-text border-0 rounded-end-4 password__ojo__datosPersonales d-flex justify-content-center align-items-center">
                                    <a href>
                                        <i class="bi bi-eye mostrar_password text-center"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="crud__btn__datosPersonales col-md-6 d-flex flex-column">
                            <button class="btn btn-primary fs-4 p-3 fw-bold">Guardar cambios</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script src="https://unpkg.com/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="../../node_modules/bootstrap/dist/js/bootstrap.js"></script>
    <script src="js/jquery-3.7.1.min.js"></script>
    <script src="js/plugins/datatables/datatables.bundle.js"></script>
    <script src="js/datatableCrud.js"></script>
    <script src="js/funciones.js"></script>
</body>

</html>