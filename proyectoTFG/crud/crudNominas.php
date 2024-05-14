<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['admin_Usuario']) && !isset($_SESSION['admin_Password'])) {
    header('Location: login-crud.php');
}
include_once "clases/conexion.php";
include_once "clases/nomina.php";
include_once "clases/empleado.php";

$conexion = new conexion();
$nomina = new nomina();
$empleado = new empleado();

$mensajeCorrecto = "";
$mensajeError = "";

if (isset($_GET['mensajeError'])) {
    $mensajeError = $_GET['mensajeError'];
}
if (isset($_GET['mensajeCorrecto'])) {
    $mensajeCorrecto = $_GET['mensajeCorrecto'];
}

// INSERTAR NOMINA
if (isset($_POST['aux_insertar_nomina'])) {
    $nomina_Empleado_IdFK = $_POST['nomina_Empleado_IdFK'];
    $nomina_Fecha = $_POST['nomina_Fecha'];
    $nomina_Archivo = $_POST['nomina_Archivo'];

    $resInserccion = $nominas->insertar($nomina_Empleado_IdFK, $nomina_Fecha, $nomina_Archivo);
    if ($resInserccion) {
        $mensajeCorrecto = "Nómina insertada correctamente";
        header('Location: crudNominas.php?mensajeCorrecto=' . $mensajeCorrecto);
    } else {
        $mensajeError = "Error al insertar la nómina";
        header('Location: crudNominas.php?mensajeError=' . $mensajeError);
    }
}

// MODIFICAR NOMINA
if (isset($_POST['aux_modificar_nomina'])) {
    $nomina_Id = $_POST['aux_modificar_nomina'];
    $nomina_Empleado_IdFK = $_POST['nomina_Empleado_IdFK'];
    $nomina_Fecha = $_POST['nomina_Fecha'];
    $nomina_Archivo = $_POST['nomina_Archivo'];

    $resModificacion = $nomina->modificar($nomina_Id, $nomina_Empleado_IdFK, $nomina_Fecha, $nomina_Archivo);
    if ($resModificacion) {
        $mensajeCorrecto = "Nómina modificado correctamente";
        header('Location: crudNominas.php?mensajeCorrecto=' . $mensajeCorrecto);
    } else {
        $mensajeError = "Error al modificar la nómina";
        header('Location: crudNominas.php?mensajeError=' . $mensajeError);
    }
}



// ELIMINAR NOMINA
if (isset($_POST['aux_eliminar_nomina'])) {
    $nomina_Id = $_POST['aux_eliminar_nomina'];
    $condicionEliminar = "WHERE nomina_Id = $nomina_Id";
    $resEliminacion = $nomina->eliminar($condicionEliminar);
    if ($resEliminacion) {
        $mensajeCorrecto = "Nómina eliminada correctamente";
        header('Location: crudNominas.php?mensajeCorrecto=' . $mensajeCorrecto);
    } else {
        $mensajeError = "Error al eliminar la nómina";
        header('Location: crudNominas.php?mensajeError=' . $mensajeError);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Crud Nóminas</title>
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
                                <a class="crud__header__link nav-link text-white" aria-current="page" href="crudClientes.php">Clientes</a>
                            </li>
                            <li class="nav-item mx-3">
                                <a class="crud__header__link nav-link text-white" aria-current="page" href="crudEmpleados.php">Empleados</a>
                            </li>
                            <li class="nav-item mx-3">
                                <a class="crud__header__link nav-link text-warning" aria-current="page" href="crudNominas.php">Nóminas</a>
                            </li>
                            <li class="nav-item mx-3">
                                <a class="crud__header__link nav-link text-white" aria-current="page" href="crudUsuariosMaestros.php">Usuarios maestros</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="d-flex">
                    <img src="../assets/media/avatar.png" alt="avatar" class="crud__header__avatar img-fluid me-3" />
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
        <div class="d-flex flex-sm-row flex-column align-items-sm-center mb-5">
            <a class="text-decoration-none text-white mb-2 mb-sm-0" href="#" data-bs-toggle="modal" data-bs-target="#modal_anadir_nomina">
                <div class="crud__tabla__btn btn btn-warning text-white p-3 rounded-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none">
                        <rect opacity="1" x="11.364" y="20.364" width="16" height="2.5" rx="1" transform="rotate(-90 11.364 20.364)" fill="currentColor"></rect>
                        <rect x="4.36396" y="11.364" width="16" height="2.5" rx="1" fill="currentColor"></rect>
                    </svg>
                    <span class="fs-4">Añadir nómina</span>
                </div>
            </a>
            <a class="text-decoration-none text-white mx-sm-3  mb-2 mb-sm-0" href="#" data-bs-toggle="modal" data-bs-target="#modal_anadir_nomina_masiva">
                <div class="crud__tabla__btn btn btn-warning text-white p-3 rounded-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none">
                        <rect opacity="1" x="11.364" y="20.364" width="16" height="2.5" rx="1" transform="rotate(-90 11.364 20.364)" fill="currentColor"></rect>
                        <rect x="4.36396" y="11.364" width="16" height="2.5" rx="1" fill="currentColor"></rect>
                    </svg>
                    <span class="fs-4">Añadir nóminas masiva</span>
                </div>
            </a>
            <a class="text-decoration-none text-white  mb-2 mb-sm-0" href="#" data-bs-toggle="modal" data-bs-target="#modal_anadir_cliente">
                <div class="crud__tabla__btn btn btn-warning text-white p-3 rounded-3">
                    <i class="bi bi-send-fill mx-2"></i> <span class="fs-4">Mandar nóminas</span>
                </div>
            </a>
            <a class="text-decoration-none text-white mx-sm-3 " href="#" data-bs-toggle="modal" data-bs-target="#modal_anadir_cliente">
                <div class="crud__tabla__btn btn btn-warning text-white p-3 rounded-3">
                    <i class="bi bi-file-earmark-excel-fill mx-2"></i>
                    <span class="fs-4">Crear excel</span>
                </div>
            </a>
        </div>
        <table id="TablaNominas" class="table table-striped table-hover align-middle gs-0 gy-4">
            <thead>
                <tr>
                    <th class="text-start">Empleado</th>
                    <th class="text-start">Fecha</th>
                    <th class="text-start">Archivo</th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="TablaNominas_body">
                <!-- FILAS -->
                <?php
                $resNominas = $nomina->obtener();
                if ($resNominas !== null) {
                    $tupla_Nomina = $conexion->BD_GetTupla($resNominas);

                    while ($tupla_Nomina !== null) {

                        $condicionEmpleado = "WHERE empl_Id = " . $tupla_Nomina['nomina_Empleado_IdFK'];
                        $resEmpleado = $empleado->obtenerConFiltro($condicionEmpleado, "");
                        $tupla_Empleado = $conexion->BD_GetTupla($resEmpleado);

                        echo "<tr>";
                        echo "<td>
                    <svg xmlns='http://www.w3.org/2000/svg' width='15' height='15' viewBox='0 0 24 24' fill='none'>
                      <path opacity='0.3' d='M20 15H4C2.9 15 2 14.1 2 13V7C2 6.4 2.4 6 3 6H21C21.6 6 22 6.4 22 7V13C22 14.1 21.1 15 20 15ZM13 12H11C10.5 12 10 12.4 10 13V16C10 16.5 10.4 17 11 17H13C13.6 17 14 16.6 14 16V13C14 12.4 13.6 12 13 12Z' fill='#E838F5'></path>
                      <path d='M14 6V5H10V6H8V5C8 3.9 8.9 3 10 3H14C15.1 3 16 3.9 16 5V6H14ZM20 15H14V16C14 16.6 13.5 17 13 17H11C10.5 17 10 16.6 10 16V15H4C3.6 15 3.3 14.9 3 14.7V18C3 19.1 3.9 20 5 20H19C20.1 20 21 19.1 21 18V14.7C20.7 14.9 20.4 15 20 15Z' fill='#AB00F5'></path>
                    </svg>
                    <span class='text-start'>" . $tupla_Empleado['empl_Nombre'] . "</span>
                  </td>";
                        echo "<td class='text-start'>" . $tupla_Nomina['nomina_Fecha'] . "</td>";
                        echo "<td class='text-start'>" . $tupla_Nomina['nomina_Archivo'] . "</td>";
                        echo "<td class='text-end'>
                    <a href='#'class='text-decoration-none' data-bs-toggle='modal' data-bs-target='#modal_modificar_nomina_" . $tupla_Nomina['nomina_Id'] . "'>
                      <svg xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' width='22px' height='22px' viewBox='0 0 24 24' version='1.1'>
                        <g stroke='none' stroke-width='1' fill='none' fill-rule='evenodd'>
                        <rect x='0' y='0' width='24' height='24'></rect>
                        <path d='M8,17.9148182 L8,5.96685884 C8,5.56391781 8.16211443,5.17792052 8.44982609,4.89581508 L10.965708,2.42895648 C11.5426798,1.86322723 12.4640974,1.85620921 13.0496196,2.41308426 L15.5337377,4.77566479 C15.8314604,5.0588212 16,5.45170806 16,5.86258077 L16,17.9148182 C16,18.7432453 15.3284271,19.4148182 14.5,19.4148182 L9.5,19.4148182 C8.67157288,19.4148182 8,18.7432453 8,17.9148182 Z' fill='#000000' fill-rule='nonzero' transform='translate(12.000000, 10.707409) rotate(-135.000000) translate(-12.000000, -10.707409) '></path>
                        <rect fill='#000000' opacity='0.3' x='5' y='20' width='15' height='2' rx='1'></rect>
                        </g>
                      </svg>
                    </a>
                    <a href='#' class='text-decoration-none' data-bs-toggle='modal' data-bs-target='#modal_eliminar_nomina_" . $tupla_Nomina['nomina_Id'] . "'>
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
                        $tupla_Nomina = $conexion->BD_GetTupla($resNominas);
                    }
                }
                ?>
            </tbody>
        </table>
    </section>

    <!-- MODALS -->
    <!-- MODAL AÑADIR NOMINA -->

    <div class="modal fade" id="modal_anadir_nomina" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog d-flex justify-content-center align-items-center crud__modal">
            <div class="modal-content w-100">
                <form method="post" action="crudClientes.php">
                    <input type="hidden" name="aux_insertar_cliente" />
                    <div class="modal-header p-5">
                        <h1 class="modal-title fs-2" id="exampleModalLabel">
                            Nueva nómina
                        </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-5">

                        <!-- FILA 1 -->
                        <div class="row d-flex justify-content-center align-items-center">
                            <div class="mb-3 col-md-6 d-flex flex-column">
                                <label for="nombre" class="form-label fs-4">Empleado:</label>
                                <select class="crud__input form-select fs-4 p-3 bg-white w-100 border text-light-emphasis rounded-4" id="activo" name="nomina_Empleado_IdFK">
                                    <?php
                                    $resEmpleados = $empleado->obtener();
                                    if ($resEmpleados !== null) {
                                        $tupla_Empleado = $conexion->BD_GetTupla($resEmpleados);
                                        while ($tupla_Empleado !== null) {
                                            echo "<option value='" . $tupla_Empleado['empl_Id'] . "'>" . $tupla_Empleado['empl_Nombre'] . "</option>";
                                            $tupla_Empleado = $conexion->BD_GetTupla($resEmpleados);
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="mb-3 col-md-6 d-flex flex-column">
                                <label for="fecha" class="form-label fs-4">Fecha:</label>
                                <input type="date" class="crud__input fs-4 p-3 text-light-emphasis rounded-4" id="apellidos" name="nomina_Fecha" required />
                            </div>
                        </div>

                        <!-- FILA 2 -->
                        <div class="row">
                            <div class="mb-3 col">
                                <label for="archivo" class="form-label fs-4">Archivo:</label>
                                <input type="file" class="form-control fs-4 p-3 text-light-emphasis rounded-4" id="archivo" name="nomina_Archivo" />
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

    <!-- MODAL AÑADIR NOMINA MASIVA -->
    <div class="modal fade" id="modal_anadir_nomina_masiva" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog d-flex justify-content-center align-items-center crud__modal">
            <div class="modal-content w-100">
                <form method="post" action="crudClientes.php">
                    <input type="hidden" name="aux_insertar_cliente" />
                    <div class="modal-header p-5">
                        <h1 class="modal-title fs-2" id="exampleModalLabel">
                            Nueva nómina masiva
                        </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-5">

                        <!-- FILA 1 -->
                        <div class="row d-flex">
                            <div class="mb-3 col-md-6 d-flex flex-column">
                                <label for="archivo" class="form-label fs-4">Archivo:</label>
                                <input type="file" class="form-control fs-4 p-3 text-light-emphasis rounded-4" id="archivo" name="nomina_Archivo" />
                            </div>
                        </div>

                        <!-- FILA 2 -->
                        <div class="row">
                            <div class="mb-3 col-6">
                                <div class="border-1">

                                    Empleado
                                </div>
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

    <!-- MODALS MODIFICAR Y ELIMINAR NÓMINA -->
    <?php
    $resNominas = $nomina->obtener();
    if ($resNominas !== null) {
        $tupla_Nomina = $conexion->BD_GetTupla($resNominas);
        while ($tupla_Nomina !== null) {

            // MODAL MODIFICAR NÓMINA
            print("<div class='modal fade' id='modal_modificar_nomina_" . $tupla_Nomina['nomina_Id'] . "' tabindex='-1' aria-labelledby='exampleModalLabel' aria-hidden='true'>
  <div class='modal-dialog d-flex justify-content-center align-items-center crud__modal'>
    <div class='modal-content w-100'>
      <form method='post' action='crudClientes.php'>
      <input type='hidden' name='aux_modificar_cliente' value='" . $tupla_Nomina['nomina_Id'] . "'/>
        <div class='modal-header p-5'>
          <h1 class='modal-title fs-2' id='exampleModalLabel'>
            Modificar cliente
          </h1>
          <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
        </div>
        <div class='modal-body p-5'>
 
        <!-- FILA 1 -->
        <div class='row d-flex justify-content-center align-items-center'>
          <div class='mb-3 col-md-4 d-flex flex-column'>
            <label for='nombre' class='form-label fs-4'>Nombre:</label>
            <input type='text' class='crud__input fs-4 p-3 text-light-emphasis rounded-4' id='nombre' name='nomina_Nombre' value='" . $tupla_Nomina['nomina_Nombre'] . "' required />
          </div>
          <div class='mb-3 col-md-4 d-flex flex-column'>
            <label for='apellidos' class='form-label fs-4'>Apellidos:</label>
            <input type='text' class='crud__input fs-4 p-3 text-light-emphasis rounded-4' id='apellidos' name='nomina_Apellidos'  value='" . $tupla_Nomina['nomina_Apellidos'] . "' required />
          </div>
          <div class='mb-3 col-md-4 d-flex flex-column'>
            <label for='telefono' class='form-label fs-4'>Telfono:</label>
            <input type='text' class='crud__input fs-4 p-3 text-light-emphasis rounded-4' id='telefono' name='nomina_Telefono'  value='" . $tupla_Nomina['nomina_Telefono'] . "' required />
          </div>
        </div>

        <!-- FILA 2 -->
        <div class='row'>
          <div class='mb-3 col-md-6 d-flex flex-column'>
            <label for='usuario' class='form-label fs-4'>Usuario:</label>
            <input type='text' class='crud__input fs-4 p-3 text-light-emphasis rounded-4' id='dni' name='nomina_Usuario' value='" . $tupla_Nomina['nomina_Usuario'] . "' required />
          </div>
          <div class='mb-3 col-md-6 d-flex flex-column'>
            <label for='password' class='form-label fs-4'>Contraseña:</label>
            <div class='input-group'>
            <input type='password' style='width: 89%;' class='password_texto crud__input fs-4 p-3 text-light-emphasis rounded-start-4' id='password' name='nomina_Password'  value='" . $tupla_Nomina['nomina_Password'] . "' required />
            <div class='input-group-text border-0 rounded-end-4 password__ojo'>
              <a href>
                <i class='bi bi-eye mostrar_password'></i>
              </a>
            </div>
          </div>
          </div>
        </div>

        <!-- FILA 3 -->
        <div class='row'>
          <div class='mb-3 col-md-6 d-flex flex-column'>
            <label for='dni' class='form-label fs-4'>DNI:</label>
            <input type='text' class='crud__input fs-4 p-3 text-light-emphasis rounded-4' id='dni' name='nomina_DNI' value='" . $tupla_Nomina['nomina_DNI'] . "' required />
          </div>
          <div class='mb-3 col-md-6 d-flex flex-column'>
            <label for='correo' class='form-label fs-4'>Correo:</label>
            <input type='email' class='crud__input fs-4 p-3 text-light-emphasis rounded-4' id='correo' name='nomina_Correo'  value='" . $tupla_Nomina['nomina_Correo'] . "' required />
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
            print("<div class='modal fade' id='modal_eliminar_nomina_" . $tupla_Nomina['nomina_Id'] . "' tabindex='-1' aria-labelledby='modal_eliminar_cliente' aria-hidden='true'>
    <div class='modal-dialog'>
      <div class='modal-content'>
        <div class='modal-body fs-3 p-4 fw-bold'>
          ¿Desea eliminar el cliente " . $tupla_Nomina['nomina_Nombre'] . " " . $tupla_Nomina['nomina_Apellidos'] . "?
        </div>
        <div class='modal-footer'>
        <form method='post' action='crudClientes.php'>
        <input type='hidden' name='aux_eliminar_cliente' value='" . $tupla_Nomina['nomina_Id'] . "' />
        <input type='hidden' name='nombre_nomina_eliminado' value='" . $tupla_Nomina['nomina_Nombre'] . "' />
          <button type='button' class='btn btn-danger fs-4 py-2 px-3' data-bs-dismiss='modal'>No</button>
          <button type='submit' class='btn btn-success fs-4 py-2 px-3'>Sí</button>
        </form>
        </div>
      </div>
    </div>
  </div>");
            $tupla_Nomina = $conexion->BD_GetTupla($resNominas);
        }
    }
    ?>


    <script src="../../node_modules/bootstrap/dist/js/bootstrap.js"></script>
    <script src="js/jquery-3.7.1.min.js"></script>
    <script src="js/plugins/datatables/datatables.bundle.js"></script>
    <script src="js/datatableCrud.js"></script>
    <script src="js/funciones.js"></script>
</body>

</html>