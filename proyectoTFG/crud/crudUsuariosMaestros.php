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

// INSERTAR USUARIO
if (isset($_POST['aux_insertar_usuario'])) {
    $usuario_Login = $_POST['usuario_Login'];
    $usuario_Password = $_POST['usuario_Password'];
    $usuario_DNI = $_POST['usuario_DNI'];
    $usuario_Activo = $_POST['usuario_Activo'];

    // Comprobar si ya existe un usuario con el mismo DNI o Login
    $campos = ['usuario_DNI' => $usuario_DNI, 'usuario_Login' => $usuario_Login];
    foreach ($campos as $campo => $valor) {
        $consulta = "WHERE $campo = '$valor'";
        $res = $usuario->obtenerConFiltro($consulta, "");
        $tuplaComprobarInserccion = $conexion->BD_GetTupla($res);
        if ($tuplaComprobarInserccion !== null) {
            $campo = strtolower(str_replace("usuario_", "", $campo));
            $mensajeError = "Error al insertar el usuario. Ya existe un usuario con ese $campo";
            header('Location: crudUsuariosMaestros.php?mensajeError=' . $mensajeError);
            exit();
        }
    }

    $resInserccion = $usuario->insertar($usuario_Login, $usuario_Password, $usuario_DNI, $usuario_Activo);
    if ($resInserccion) {
        $mensajeCorrecto = "Usuario $usuario_Login insertado correctamente";
        header('Location: crudUsuariosMaestros.php?mensajeCorrecto=' . $mensajeCorrecto);
    } else {
        $mensajeError = "Error al insertar el usuario $usuario_Login";
        header('Location: crudUsuariosMaestros.php?mensajeError=' . $mensajeError);
    }
}

// MODIFICAR USUARIO
if (isset($_POST['aux_modificar_usuario'])) {
    $usuario_Login_Actual = $_POST['usuario_Login_Actual'];
    $usuario_Id = $_POST['aux_modificar_usuario'];
    $usuario_Login = $_POST['usuario_Login'];
    $usuario_Password = $_POST['usuario_Password'];
    $usuario_DNI = $_POST['usuario_DNI'];
    $usuario_Activo = $_POST['usuario_Activo'];

    // Comprobar si ya existe un usuario con el Usuario o DNI
    $campos = ['usuario_DNI' => $usuario_DNI, 'usuario_Login' => $usuario_Login];
    foreach ($campos as $campo => $valor) {
        $consultaModificacion = "WHERE $campo = '$valor' AND usuario_Id != $usuario_Id";
        $resModificacion = $usuario->obtenerConFiltro($consultaModificacion, "");
        $tuplaComprobarModificacion = $conexion->BD_GetTupla($resModificacion);
        if ($tuplaComprobarModificacion !== null) {
            $campo = strtolower(str_replace("usuario_", "", $campo));
            $mensajeError = "Error al modificar el usuario $usuario_Login_Actual. Ya existe un usuario con ese $campo";
            header('Location: crudUsuariosMaestros.php?mensajeError=' . $mensajeError);
            exit();
        }
    }
    $resModificacion = $usuario->modificar($usuario_Id, $usuario_Login, $usuario_Password, $usuario_DNI, $usuario_Activo);
    if ($resModificacion) {
        $mensajeCorrecto = "Usuario $usuario_Login modificado correctamente";
        header('Location: crudUsuariosMaestros.php?mensajeCorrecto=' . $mensajeCorrecto);
    } else {
        $mensajeError = "Error al modificar el usuario $usuario_Login_Actual";
        header('Location: crudUsuariosMaestros.php?mensajeError=' . $mensajeError);
    }
}

// ELIMINAR USUARIO
if (isset($_POST['aux_eliminar_usuario'])) {
    $usuario_Id = $_POST['aux_eliminar_usuario'];
    $usuario_Login_Eliminado = $_POST['nombre_usuario_eliminado'];
    $condicionEliminar = "WHERE usuario_Id = $usuario_Id";
    $resEliminacion = $cliente->eliminar($condicionEliminar);
    if ($resEliminacion) {
        $mensajeCorrecto = "Usuario $usuario_Login_Eliminado eliminado correctamente";
        header('Location: crudUsuariosMaestros.php?mensajeCorrecto=' . $mensajeCorrecto);
    } else {
        $mensajeError = "Error al eliminar el usuario $usuario_Login_Eliminado";
        header('Location: crudUsuariosMaestros.php?mensajeError=' . $mensajeError);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Crud Usuarios</title>
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
                                <a class="crud__header__link nav-link" aria-current="page" href="crudVuelos.php">Vuelos Registrados</a>
                            </li>
                            <li class="nav-item mx-3">
                                <a class="crud__header__link nav-link text-warning" aria-current="page" href="crudUsuariosMaestros.php">Usuarios maestros</a>
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
                            <li><a class="dropdown-item fs-4 mb-2" href="crudDatosPersonales.php"><i class="bi bi-pencil-square fs-2 me-3"></i>Datos personales</a></li>
                            <li><a class="dropdown-item fs-4" href="clases/desconectar.php"><i class="bi bi-box-arrow-left fs-2 me-3"></i>Cerrar Sesión</a></li>
                        </ul>
                    </div>
                    <button class="navbar-toggler border-0 text-white" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-expanded="false" aria-label="Toggle navigation">
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
    <!-- TABLA -->
    <section class="crud__tabla container-fluid bg-white mt-5 rounded-4 p-5">
        <div class="mb-5 d-flex flex-column flex-sm-row justify-content-between align-items-center">
            <div>
                <a class="text-decoration-none text-white" href="#" data-bs-toggle="modal" data-bs-target="#modal_anadir_cliente">
                    <div class="crud__tabla__btn btn btn-warning text-white p-3 rounded-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none">
                            <rect opacity="1" x="11.364" y="20.364" width="16" height="2.5" rx="1" transform="rotate(-90 11.364 20.364)" fill="currentColor"></rect>
                            <rect x="4.36396" y="11.364" width="16" height="2.5" rx="1" fill="currentColor"></rect>
                        </svg>
                        <span class="fs-4">Añadir usuario</span>
                    </div>
                </a>
            </div>
            <div>
                <div class="input-group search rounded-3 my-3 my-sm-0">
                    <span class="search__icon input-group-text border-0 py-2 px-3 rounded-start-3"><i class="bi bi-search fs-4"></i></span>
                    <input type="text" class="search__input border-0 fs-4 p-2 rounded-end-3" id="search__input" placeholder="Buscar usuario">
                </div>
            </div>
        </div>
        <table id="TablaUsuarios" class="table table-striped table-hover align-middle gs-0 gy-4">
            <thead>
                <tr>
                    <th class="text-start" style="width: 600px;">Login</th>
                    <th class="text-start" style="width: 400px;">DNI</th>
                    <th class="text-start">Activo</th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="TablaUsuarios_body">
                <!-- FILAS -->
                <?php
                $condicionUsuario = "WHERE usuario_Activo = 'Si'"; // "WHERE usuario_Activo = 'Si'
                $resUsuarios = $usuario->obtenerConFiltro($condicionUsuario, "");
                if ($resUsuarios  !== null) {
                    $tupla_Usuario = $conexion->BD_GetTupla($resUsuarios);

                    while ($tupla_Usuario !== null) {
                        echo "<tr>";
                        echo "<td>
                    <svg xmlns='http://www.w3.org/2000/svg' width='15' height='15' viewBox='0 0 24 24' fill='none'>
                      <path opacity='0.3' d='M20 15H4C2.9 15 2 14.1 2 13V7C2 6.4 2.4 6 3 6H21C21.6 6 22 6.4 22 7V13C22 14.1 21.1 15 20 15ZM13 12H11C10.5 12 10 12.4 10 13V16C10 16.5 10.4 17 11 17H13C13.6 17 14 16.6 14 16V13C14 12.4 13.6 12 13 12Z' fill='#E838F5'></path>
                      <path d='M14 6V5H10V6H8V5C8 3.9 8.9 3 10 3H14C15.1 3 16 3.9 16 5V6H14ZM20 15H14V16C14 16.6 13.5 17 13 17H11C10.5 17 10 16.6 10 16V15H4C3.6 15 3.3 14.9 3 14.7V18C3 19.1 3.9 20 5 20H19C20.1 20 21 19.1 21 18V14.7C20.7 14.9 20.4 15 20 15Z' fill='#AB00F5'></path>
                    </svg>
                    <span class='text-start'>" . $tupla_Usuario['usuario_Login'] . "</span>
                  </td>";
                        echo "<td class='text-start'>" . $tupla_Usuario['usuario_DNI'] . "</td>";
                        echo "<td class='text-start'>" . $tupla_Usuario['usuario_Activo'] . "</td>";
                        echo "<td class='text-end'>
                    <a href='#'class='text-decoration-none' data-bs-toggle='modal' data-bs-target='#modal_modificar_usuario_" . $tupla_Usuario['usuario_Id'] . "'>
                      <svg xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' width='22px' height='22px' viewBox='0 0 24 24' version='1.1'>
                        <g stroke='none' stroke-width='1' fill='none' fill-rule='evenodd'>
                        <rect x='0' y='0' width='24' height='24'></rect>
                        <path d='M8,17.9148182 L8,5.96685884 C8,5.56391781 8.16211443,5.17792052 8.44982609,4.89581508 L10.965708,2.42895648 C11.5426798,1.86322723 12.4640974,1.85620921 13.0496196,2.41308426 L15.5337377,4.77566479 C15.8314604,5.0588212 16,5.45170806 16,5.86258077 L16,17.9148182 C16,18.7432453 15.3284271,19.4148182 14.5,19.4148182 L9.5,19.4148182 C8.67157288,19.4148182 8,18.7432453 8,17.9148182 Z' fill='#000000' fill-rule='nonzero' transform='translate(12.000000, 10.707409) rotate(-135.000000) translate(-12.000000, -10.707409) '></path>
                        <rect fill='#000000' opacity='0.3' x='5' y='20' width='15' height='2' rx='1'></rect>
                        </g>
                      </svg>
                    </a>
                    <a href='#' class='text-decoration-none' data-bs-toggle='modal' data-bs-target='#modal_eliminar_usuario_" . $tupla_Usuario['usuario_Id'] . "'>
                        <svg xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlin' width='22px' height='22px' viewBox='0 0 24 24' version='1.1'>
                        <g stroke='none' stroke-width='1' fill='none' fill-rule='evenodd'>
                        <rect x='0' y='0' width='24' height='24'></rect>
                        <path d='M6,8 L6,20.5 C6,21.3284271 6.67157288,22 7.5,22 L16.5,22 C17.3284271,22 18,21.3284271 18,20.5 L18,8 L6,8 Z' fill='#000000' fill-rule='nonzero'></path>
                        <path d='M14,4.5 L14,4 C14,3.44771525 13.5522847,3 13,3 L11,3 C10.4477153,3 10,3.44771525 10,4 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z' fill='#000000' opacity='0.3'></path>
                        </g>
                      </svg>
                    </a>
                  </td>";
                        echo "</tr>";
                        $tupla_Usuario = $conexion->BD_GetTupla($resUsuarios);
                    }
                }
                ?>
            </tbody>
        </table>
    </section>

    <!-- MODALS -->
    <!-- MODAL AÑADIR USUARIO -->

    <div class="modal fade" id="modal_anadir_cliente" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog d-flex justify-content-center align-items-center crud__modal">
            <div class="modal-content w-100">
                <form method="post" action="crudUsuariosMaestros.php">
                    <input type="hidden" name="aux_insertar_usuario" />
                    <div class="modal-header p-5">
                        <h1 class="modal-title fs-2" id="exampleModalLabel">
                            Nuevo usuario
                        </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-5">

                        <!-- FILA 1 -->
                        <div class="row d-flex justify-content-center align-items-center">
                            <div class="mb-3 col-md-6 d-flex flex-column">
                                <label for="login" class="form-label fs-4">Login:</label>
                                <input type="text" class="crud__input fs-4 p-3 text-light-emphasis rounded-4" id="login" name="usuario_Login" placeholder="user" required />
                            </div>
                            <div class="mb-3 col-md-6 d-flex flex-column">
                                <label for="password" class="form-label fs-4">Contraseña:</label>
                                <div class="input-group">
                                    <input type="password" style="width: 85%;" class="password_texto crud__input fs-4 p-3 text-light-emphasis rounded-start-4" id="password" name="usuario_Password" required />
                                    <div style="width: 15%;" class="d-flex justify-content-center align-items-center input-group-text border-0 rounded-end-4 password__ojo">
                                        <a href>
                                            <i class="bi bi-eye mostrar_password"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- FILA 2 -->
                        <div class="row">
                            <div class="mb-3 col-md-6 d-flex flex-column">
                                <label for="dni" class="form-label fs-4">DNI:</label>
                                <input type="text" class="crud__input fs-4 p-3 text-light-emphasis rounded-4" id="dni" name="usuario_DNI" placeholder="00000000A" required />
                            </div>
                            <div class="mb-3 col-md-6 d-flex flex-column">
                                <label for="activo" class="form-label fs-4">Activo:</label>
                                <select class="crud__input form-select fs-4 p-3 bg-white w-100 border text-light-emphasis rounded-4" id="activo" name="usuario_Activo">
                                    <option value="Si" selected>Sí</option>
                                    <option value="No">No</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4">
                        <button type="button" class="btn btn-dark fs-4 px-4 py-2 rounded-3" data-bs-dismiss="modal">
                            Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary fs-4 px-4 py-2 text-white rounded-3">
                            Añadir
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODALS MODIFICAR Y ELIMINAR USUARIO -->
    <?php
    $resUsuarios = $usuario->obtener();
    if ($resUsuarios !== null) {
        $tupla_Usuario = $conexion->BD_GetTupla($resUsuarios);
        while ($tupla_Usuario !== null) {

            // MODAL MODIFICAR USUARIO
            print("<div class='modal fade' id='modal_modificar_usuario_" . $tupla_Usuario['usuario_Id'] . "' tabindex='-1' aria-labelledby='exampleModalLabel' aria-hidden='true'>
  <div class='modal-dialog d-flex justify-content-center align-items-center crud__modal'>
    <div class='modal-content w-100'>
      <form method='post' action='crudUsuariosMaestros.php'>
      <input type='hidden' name='aux_modificar_usuario' value='" . $tupla_Usuario['usuario_Id'] . "' />
      <input type='hidden' name='usuario_Login_Actual' value='" . $tupla_Usuario['usuario_Login'] . "' />
        <div class='modal-header p-5'>
          <h1 class='modal-title fs-2' id='exampleModalLabel'>
            Modificar cliente
          </h1>
          <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
        </div>
        <div class='modal-body p-5'>
 
        <!-- FILA 1 -->
        <div class='row d-flex justify-content-center align-items-center'>
            <div class='mb-3 col-md-6 d-flex flex-column'>
                <label for='login' class='form-label fs-4'>Login:</label>
                <input type='text' class='crud__input fs-4 p-3 text-light-emphasis rounded-4' id='login' name='usuario_Login' value='" . $tupla_Usuario['usuario_Login'] . "' required />
            </div>
            <div class='mb-3 col-md-6 d-flex flex-column'>
                <label for='password' class='form-label fs-4'>Contraseña:</label>
                <div class='input-group'>
                <input type='password' style='width: 85%;' class='password_texto crud__input fs-4 p-3 text-light-emphasis rounded-start-4' id='password' name='usuario_Password' value='" . $tupla_Usuario['usuario_Password'] . "' required />
                <div style='width: 15%;' class='d-flex justify-content-center align-items-center input-group-text border-0 rounded-end-4 password__ojo'>
                    <a href>
                        <i class='bi bi-eye mostrar_password'></i>
                    </a>
                </div>
            </div>            </div>
        </div>

        <!-- FILA 2 -->
        <div class='row'>
            <div class='mb-3 col-md-6 d-flex flex-column'>
                <label for='dni' class='form-label fs-4'>DNI:</label>
                <input type='text' class='crud__input fs-4 p-3 text-light-emphasis rounded-4' id='dni' name='usuario_DNI' value='" . $tupla_Usuario['usuario_DNI'] . "' required />
            </div>
            <div class='mb-3 col-md-6 d-flex flex-column'>
                <label for='activo' class='form-label fs-4'>Activo:</label>
                <select class='crud__input form-select fs-4 p-3 bg-white w-100 border text-light-emphasis rounded-4' id='activo' name='usuario_Activo'>
                    <option value='Si' selected>Sí</option>
                    <option value='No'>No</option>
                </select>
            </div>
        </div>
        </div>
        <div class='modal-footer border-0 p-4'>
          <button type='button' class='btn btn-dark fs-4 px-4 py-2 rounded-3' data-bs-dismiss='modal'>
            Cancelar
          </button>
          <button type='submit' class='btn btn-primary fs-4 px-4 py-2 text-white rounded-3'>
            Añadir
          </button>
        </div>
      </form>
    </div>
  </div>
</div>");

            // MODAL ELIMINAR CLIENTE
            print("<div class='modal fade' id='modal_eliminar_usuario_" . $tupla_Usuario['usuario_Id'] . "' tabindex='-1' aria-labelledby='modal_eliminar_cliente' aria-hidden='true'>
    <div class='modal-dialog'>
      <div class='modal-content'>
        <div class='modal-body fs-3 p-4'>
          ¿Desea eliminar el usuario <strong>" . $tupla_Usuario['usuario_Login'] . "</strong>?
        </div>
 -       <div class='modal-footer'>
        <form method='post' action='crudUsuariosMaestros.php'>
        <input type='hidden' name='aux_eliminar_usuario' value='" . $tupla_Usuario['usuario_Id'] . "' />
        <input type='hidden' name='nombre_usuario_eliminado' value='" . $tupla_Usuario['usuario_Login'] . "' />
          <button type='button' class='btn btn-danger fs-4 py-2 px-3' data-bs-dismiss='modal'>No</button>
          <button type='submit' class='btn btn-success fs-4 py-2 px-3'>Sí</button>
        </form>
        </div>
      </div>
    </div>
  </div>");
            $tupla_Usuario = $conexion->BD_GetTupla($resUsuarios);
        }
    }
    ?>

    <script src="https://unpkg.com/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="../../node_modules/bootstrap/dist/js/bootstrap.js"></script>
    <script src="js/jquery-3.7.1.min.js"></script>
    <script src="js/plugins/datatables/datatables.bundle.js"></script>
    <script src="js/datatableCrud.js"></script>
    <script src="js/funciones.js"></script>
    <script>
        $("#search__input").on("keyup", function() {
            let tabla = $("#TablaUsuarios").DataTable();
            tabla.search(this.value).draw();
        });
    </script>
</body>

</html>