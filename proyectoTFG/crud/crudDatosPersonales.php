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


// MODIFICAR CLIENTE
if (isset($_POST['aux_modificar_cliente'])) {
    $cliente_Id = $_POST['aux_modificar_cliente'];
    $cliente_Nombre = $_POST['cliente_Nombre'];
    $cliente_Apellidos = $_POST['cliente_Apellidos'];
    $cliente_Usuario = $_POST['cliente_Usuario'];
    $cliente_Password = $_POST['cliente_Password'];
    $cliente_Telefono = $_POST['cliente_Telefono'];
    $cliente_DNI = $_POST['cliente_DNI'];
    $cliente_Correo = $_POST['cliente_Correo'];

    // Comprobar si ya existe un cliente con el mismo DNI o Correo
    $campos = ['cliente_DNI' => $cliente_DNI, 'cliente_Correo' => $cliente_Correo, 'cliente_Usuario' => $cliente_Usuario];
    foreach ($campos as $campo => $valor) {
        $consultaModificacion = "WHERE $campo = '$valor' AND cliente_Id != $cliente_Id";
        $resModificacion = $cliente->obtenerConFiltro($consultaModificacion, "");
        $tuplaComprobarModificacion = $conexion->BD_GetTupla($resModificacion);
        if ($tuplaComprobarModificacion !== null) {
            $campo = strtolower(str_replace("cliente_", "", $campo));
            $mensajeError = "Error al modificar al cliente $cliente_Nombre $cliente_Apellidos. Ya existe un cliente con ese $campo";
            header('Location: crudClientes.php?mensajeError=' . $mensajeError);
            exit();
        }
    }
    $resModificacion = $cliente->modificar($cliente_Id, $cliente_Nombre, $cliente_Apellidos, $cliente_Usuario, $cliente_Password, $cliente_Telefono, $cliente_DNI, $cliente_Correo);
    if ($resModificacion) {
        $mensajeCorrecto = "Cliente/a $cliente_Nombre $cliente_Apellidos modificado correctamente";
        header('Location: crudClientes.php?mensajeCorrecto=' . $mensajeCorrecto);
    } else {
        $mensajeError = "Error al modificar el cliente/a $cliente_Nombre $cliente_Apellidos";
        header('Location: crudClientes.php?mensajeError=' . $mensajeError);
    }
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
                    <img src="../assets/media/logo.png" alt="logo" class="crud__header__logo img-fluid" />
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
                            <img src="../assets/media/avatar.png" alt="avatar" class="crud__header__avatar img-fluid me-3" />
                        </a>
                        <ul class="dropdown-menu p-4 rounded-4">
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
    <div class="container crud__datosPersonales mt-5 ms-5 float-start p-5 rounded-3">
        <h3>Modificar datos personales</h3>
        <div class="row">
            <div class="col-md-6 d-flex flex-column mt-3">
                <label for="login" class="form-label">Login:</label>
                <input type="text" name="usuario_Login" id="login" class="crud__input__datosPersonales fs-4 p-2 rounded-4">
            </div>
            <div class="col-md-6 d-flex flex-column mt-3">
                <label for="dni" class="form-label">DNI:</label>
                <input type="text" name="usuario_DNI" id="dni" class="crud__input__datosPersonales fs-4 p-2 rounded-4">
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 d-flex flex-column mt-3">
                <label for="password" class="form-label">Contraseña:</label>
                <input type="password" name="usuario_Password" id="password" class="crud__input__datosPersonales fs-4 p-2 rounded-4">
            </div>
            <div class="crud__btn__datosPersonales col-md-6 d-flex flex-column">
                <button class="btn btn-primary fs-4 p-2 fw-bold">Guardar cambios</button>
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