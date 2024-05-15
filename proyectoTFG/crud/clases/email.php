<?php
include_once "conexion.php";
include_once "empleado.php";
include_once "nomina.php";

$conexion = new conexion();
$empleado = new empleado();
$nomina = new nomina();
// Mostrar errores PHP (Desactivar en producción)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Incluir la libreria PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require "../vendor/PHPMailer/src/Exception.php";
require "../vendor/PHPMailer/src/PHPMailer.php";
require "../vendor/PHPMailer/src/SMTP.php";

// Inicio
$mail = new PHPMailer(true);

try {
    // Configuracion SMTP
    // $mail->SMTPDebug = SMTP::DEBUG_SERVER;  // Mostrar salida (Desactivar en producción)
    $mail->isSMTP();   // Activar envio SMTP
    $mail->Host  = 'smtp.gmail.com';                     // Servidor SMTP
    $mail->SMTPAuth  = true;  // Identificacion SMTP
    $mail->Username  = 'proyectotfgdaw@gmail.com';                  // Usuario SMTP
    $mail->Password  = 'lozcugypnpjurnrd';   // Contraseña SMTP
    $mail->SMTPSecure = 'ssl';
    $mail->Port  = 465;
    $mail->setFrom('proyectotfgdaw@gmail.com', 'Interstellar Airlines');  // Remitente del correo

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['allNominas'])) {
            $resDestinatario = $empleado->obtener();
            $tuplaDestinatario = $conexion->BD_GetTupla($resDestinatario);

            while ($tuplaDestinatario !== NULL) {
                $archivosAdjuntos = [];
                $consultaNominas = "WHERE nomina_Empleado_IdFK = '$tuplaDestinatario[empl_Id]'";
                $resNominas = $nomina->obtenerConFiltro($consultaNominas, "");
                $tuplaNominas = $conexion->BD_GetTupla($resNominas);
                while ($tuplaNominas !== NULL) {
                    $carpeta = "../../archivos/nominas/";

                    if ($tuplaDestinatario['empl_Id'] == $tuplaNominas['nomina_Empleado_IdFK']) {
                        $archivosAdjuntos[] = $carpeta . $tuplaNominas['nomina_Archivo'];
                    }
                    $tuplaNominas = $conexion->BD_GetTupla($resNominas);
                }

                // Destinatarios
                $mail->addAddress("proyectotfgdaw@gmail.com", $tuplaDestinatario["empl_Nombre"]);  // Email y nombre del destinatario

                // Contenido del correo
                $mail->isHTML(true);
                $mail->Subject = 'Nominas de ' . $tuplaDestinatario['empl_Nombre'] . '.';
                $mail->Body  = 'Nominas del empleado con email ' . $tuplaDestinatario['empl_Correo'] . '.';
                $mail->AltBody = 'Nominas';
                $mail->clearAttachments();
                foreach ($archivosAdjuntos as $archivo) {
                    if (file_exists($archivo)) {
                        $mail->addAttachment($archivo);
                    }
                }

                $mail->send();
                $mensaje = 'Se ha enviado todas las nóminas correctamente.';
                $tuplaDestinatario = $conexion->BD_GetTupla($resDestinatario);
            }
            header("location: ../crudNominas.php?email=$mensaje&sendTodas=true");
            exit();
        } else {

            $id_destinatario = $_POST['destinatario'];

            foreach ($id_destinatario as $destinatario) {
                $condicion = "WHERE nomina_Empleado_IdFK = '$destinatario'";
                $resNominas = $nomina->obtenerConFiltro($condicion, "");
                $tuplaNominas = $conexion->BD_GetTupla($resNominas);
                $mail->clearAttachments();
                if ($tuplaNominas == NULL) {
                    header("location: ../crudNominas.php?noEmail=true");
                    exit();
                }
                while ($tuplaNominas !== NULL) {

                    $condicionDestinatario = "WHERE empl_Id = '$tuplaNominas[nomina_Empleado_IdFK]'";
                    $resDestinatario = $empleado->obtenerConFiltro($condicionDestinatario, "");
                    $tuplaDestinatario = $conexion->BD_GetTupla($resDestinatario);

                    $carpeta = "../../archivos/nominas/";
                    $archivosAdjuntos = $carpeta . $tuplaNominas['nomina_Archivo'];

                    if (file_exists($archivosAdjuntos)) {
                        $mail->addAttachment($archivosAdjuntos);
                    }
                    $tuplaNominas = $conexion->BD_GetTupla($resNominas);
                }
                // Destinatario
                $mail->addAddress("proyectotfgdaw@gmail.com", $tuplaDestinatario["empl_Nombre"]);  // Email y nombre del destinatario

                // Contenido del correo
                $mail->isHTML(true);
                $mail->Subject = 'Nominas de ' . $tuplaDestinatario['empl_Nombre'] . '.';
                $mail->Body  = 'Nominas del empleado con email ' . $tuplaDestinatario['empl_Correo'] . '.';
                $mail->AltBody = 'Nominas';
                $mail->send();
                $mensaje = 'El email se ha enviado correctamente.';
            }
            header("location: ../crudNominas.php?email=$mensaje&send=true");
            exit();
        }
    }
} catch (Exception $e) {
    $mensaje = "El mensaje no se ha enviado. Mailer Error: {$mail->ErrorInfo}";
    header("location: ../crudNominas?email=$mensaje&send=false");
    exit();
}
