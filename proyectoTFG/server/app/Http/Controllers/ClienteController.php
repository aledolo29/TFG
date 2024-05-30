<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use PHPMailer\PHPMailer\PHPMailer;

class ClienteController extends Controller
{
    public function guardarCliente(Request $request)
    {
        $comprobarCliente = Cliente::where('cliente_Usuario', $request->usuario)->orWhere('cliente_Correo', $request->correo)->first();
        $arrayValores = ["cliente_Usuario" => $request->usuario, "cliente_Correo" => $request->correo];
        if ($comprobarCliente != null) {
            foreach ($arrayValores as $key => $value) {
                if ($comprobarCliente->$key == $value) {
                    $campoRepetido =  explode('_', $key);
                    return response()->json([
                        'error' => 'El ' . strtolower($campoRepetido[1]) . ' ya esta registrado.'
                    ]);
                }
            }
        } else {
            $cliente = new Cliente();
            $cliente->cliente_Nombre = $request->nombre;
            $cliente->cliente_Apellidos = $request->apellidos;
            $cliente->cliente_Usuario = $request->usuario;
            $cliente->cliente_Password = $request->password;
            $cliente->cliente_DNI = $request->dni;
            $cliente->cliente_Correo = $request->correo;
            $cliente->cliente_Telefono = $request->telefono;
            $cliente->save();
            return response()->json([
                'correcto' => 'Usuario ' . $request->usuario . ' registrado correctamente. Espere para ser redirigido.', 'cliente' => $cliente
            ]);
        }
    }

    public function comprobarLogin(Request $request)
    {
        $comprobarCliente = Cliente::where('cliente_Correo', $request->user)->orWhere('cliente_Usuario', $request->user)->first();
        if ($comprobarCliente != null) {
            if ($comprobarCliente->cliente_Password == $request->password) {
                return response()->json([
                    'correcto' => 'Usuario ' . $request->usuario . ' logueado correctamente. Espere para ser redirigido.', 'nombre' =>  $comprobarCliente->cliente_Nombre, 'idCliente' => $comprobarCliente->cliente_Id
                ]);
            } else {
                return response()->json([
                    'error' => 'Contraseña incorrecta.'
                ]);
            }
        } else {
            return response()->json([
                'error' => 'Usuario no registrado.'
            ]);
        }
    }

    public function obtenerCliente(Request $request)
    {
        $cliente = Cliente::where('cliente_Id', $request->idCliente)->first();
        return response()->json([
            'cliente' => $cliente
        ]);
    }

    public function recuperarContrasena(Request $request)
    {
        $comprobarCliente = Cliente::where('cliente_Correo', $request->email)->first();
        if ($comprobarCliente != null) {


            $mail = new PHPMailer(true);


            // Envía un email de recuperación de contraseña al cliente

            // Inicio
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

            $mail->addAddress('proyectotfgdaw@gmail.com', $comprobarCliente->cliente_Correo);  // Email y nombre del destinatario
            $mail->isHTML(true);
            $mail->Subject = 'Recordatorio de ' . utf8_decode("contraseña") . ' del usuario ' . $comprobarCliente->cliente_Usuario;
            $mail->Body = '
            <!DOCTYPE html>
            <html lang="es">
              <head>
                <meta charset="UTF-8" />
                <meta name="viewport" content="width=device-width, initial-scale=1.0" />
                <title>Confirmación de Compra de Billetes</title>
                <link
                  href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
                  rel="stylesheet"
                  integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
                  crossorigin="anonymous"
                />
                <style>
                  body {
                    color: #666;
                    font-size: 0.8rem;
                  }
                  .container {
                      width: 100%;
                      display: flex;
                      justify-content: center;
                      align-items: center;
                    }
                .mensaje{
                    border: 1px solid #325162;
                    border-radius: 15px;
                    padding: 20px;
                    width: 90%;
                    margin: auto;
                }
                    
                  h3 {
                    font-weight: bold;
                    color: #325162;
                    font-size: 1rem;
                  }
                  .footer {
                    text-align: center;
                    color: #8a8a8a;
                  }

                  @media (min-width: 576px) {
                    .mensaje {
                      width: 40%;
                    }
                  }
                </style>
              </head>
              <body>
                <div class="container">
                <div class="mensaje">
                <div class="header">
                <h3>Recordatorio de ' . utf8_decode("Contraseña") . '</h3>
                </div>
                <div class="content">
                <p>Hola ' . $comprobarCliente->cliente_Nombre . ',</p>
                <div class="recordatorio">
                      <p>
                      Recibes este correo ' . utf8_decode("electrónico") . ' porque solicitaste un recordatorio
                      de tu ' . utf8_decode("contraseña") . '.
                      </p>
                      <p>Aqui ' . utf8_decode("está") . ' la ' . utf8_decode("información") . ' de tu cuenta:</p>
                      <ul>
                      <li>
                      <strong>Usuario: </strong>' . $comprobarCliente->cliente_Usuario .
                '
                      </li>
                      <li>
                      <strong> ' . utf8_decode("Contraseña") . ': </strong>' .
                $comprobarCliente->cliente_Password . '
                      </li>
                      </ul>
                      <p>
                      Si no solicitaste este recordatorio, por favor ignora este mensaje
                      </p>
                      <p class="footer">Gracias,<br />Equipo de Soporte</p>
                      </div>
                      </div>
                      </div>
                </div>
                      <script
                      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
                      integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
                      crossorigin="anonymous"
                      ></script>
                      </body>
                      </html>
                      ';
            $mail->send();


            return response()->json([
                'correcto' => true
            ]);
        } else {
            return response()->json([
                'error' => true
            ]);
        }
    }
}
