<?php

use Symfony\Component\VarDumper\VarDumper;

require_once 'ConexionBD.php';
require_once 'fpdf/PDFService.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

if (isset($_GET['billete_Id'])) {
    $id = $_GET['billete_Id'];

    $conexion = ConexionBD::connectDB();
    $seleccionBillete = "SELECT * FROM billetes WHERE billete_Id = '$id'";
    $consultaBillete = $conexion->query($seleccionBillete);

    $fechaHoy = date("d/m/Y");
    $precio = 0;
    $precioSinIVA = 0;
    $asiento = "";
    $cliente = null;
    $numeroFactura =  generarNumeroFactura();
    if ($consultaBillete !== null) {
        $resultadoBillete = $consultaBillete->fetchObject();
        if ($resultadoBillete !== null) {
            $asiento = $resultadoBillete->billete_Asiento;
            $asiento = intval(substr($asiento, 1));
            $precio = $resultadoBillete->billete_Precio;
            $precioSinIVA =  $precio * 0.1;

            $seleccionCliente = "SELECT * FROM clientes WHERE cliente_Id = '$resultadoBillete->billete_cliente_IdFK'";
            $consultaCliente = $conexion->query($seleccionCliente);
            if ($consultaCliente !== null) {
                $resultadoCliente = $consultaCliente->fetchObject();

                $pdf = new PDFService();
                $pdf->AliasNbPages();
                // Primera página
                $pdf->AddPage();
                $pdf->SetFont("Courier", "B", 11);
                $y = 20;
                $pdf->SetXY($pdf->GetX(), $y);
                $pdf->Cell($pdf->GetPageWidth() / 3, 15, $fechaHoy, 0, 2, "R");
                $pdf->SetFont("Courier", "B", 8);
                $pdf->Cell($pdf->GetPageWidth() / 3, 0, "Factura por compra de billete de vuelo", 0, 1, "R");
                $pdf->SetFont("Courier", "B", 10);
                $y = 40;
                $pdf->SetXY($pdf->GetX(), $y);
                $pdf->Cell(0, 10, "" . utf8_decode("¡") . "Gracias por confiar en nosotros!", 0, 2, "L");
                $pdf->Cell(0, 6, "Nombre: " . $resultadoCliente->cliente_Nombre . "", 0, 2, "L");
                $pdf->Cell(0, 6, "Apellidos: " . $resultadoCliente->cliente_Apellidos . "", 0, 2, "L");
                $pdf->Cell(0, 6, "NIF: " . $resultadoCliente->cliente_DNI . "", 0, 2, "L");

                $pdf->Ln(20);

                $pdf->Cell(0, 6, "INTERSTELLAR AIRLINES, " . utf8_decode("LÍNEAS AÉREAS DE ESPAÑA") . ", S.A, OPERADORA, SOCIEDAD UNIPERSONAL", 0, 2, "L");
                $pdf->Cell(0, 6, "C/ Real, 1, 28001 Sevilla", 0, 2, "L");
                $pdf->Cell(0, 6, "CIF: A-12345678", 0, 2, "L");
                $pdf->Cell(0, 6, "41710 SEVILLA - " . utf8_decode("ESPAÑA") . "", 0, 2, "L");
                $pdf->Cell(0, 6, "" . utf8_decode("Teléfono") . ": 954 123 456", 0, 2, "L");
                $pdf->Cell(0, 6, "Fax: 954 123 456", 0, 2, "L");

                $pdf->Ln(20);

                $pdf->Cell(0, 6, "DATOS DE LA FACTURA", 0, 2, "L");
                $pdf->Cell(0, 6, "NUMERO DE FACTURA: " . $numeroFactura . "", 0, 2, "L");
                $pdf->Cell(0, 6, "FECHA DE EMISION DE BILLETE: " . $fechaHoy . "", 0, 2, "L");
                $pdf->Cell(0, 6, "FECHA DE EMISION DE FACTURA: " . $fechaHoy . "", 0, 2, "L");
                $data = ["IVA", "Base imponible", "Total IVA", "SUBTOTAL"];
                $rowFacture = [32, 32, 25, 25];
                $pdf->Ln(30);
                $pdf->SetFont("Arial", "B", 8);
                $pdf->SetX(80);
                $pdf->FactureTable($data, $rowFacture);

                $price = ["10%",  $precio - $precioSinIVA,  $precioSinIVA, $precio];
                $rowPrice = [32, 32, 25, 25];
                $pdf->SetX(80);
                $pdf->PriceTable($price, $rowPrice);

                $pdf->SetFillColor(232, 232, 232);
                $pdf->SetTextColor(0);
                $pdf->SetDrawColor(0);
                $pdf->SetLineWidth(0.3);
                $pdf->Cell(0, 15, "", 0, 2);
                $pdf->Cell(25, 5, "TOTAL (" . EURO . ")", 1, 2, "L", 1);
                $pdf->SetFillColor(255);
                $pdf->SetTextColor(0);
                $pdf->SetDrawColor(0);
                $pdf->SetLineWidth(0.3);
                $pdf->Cell(25, 5, $precio, 1, 1, "C", 1);  // Total


                $pdf->Output('D', 'Factura-' . $numeroFactura . '.pdf');
                //    header('Location')
            }
        }
    }
} else {
    header("Location: ../client/index.html");
}
function generarNumeroFactura()
{
    // Genera un número aleatorio de tres dígitos
    $parte1 = rand(100, 999);
    // Genera un número aleatorio de ocho dígitos
    $parte2 = rand(10000000, 99999999);

    // Formatea el número de factura
    $numeroFactura = sprintf('%03d-%08d', $parte1, $parte2);

    return $numeroFactura;
}
