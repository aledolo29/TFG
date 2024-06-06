<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Services\PDFService;

class PDFController extends Controller
{
    function generatePDF()
    {
        $pdf = new PDFService();
        $pdf->AliasNbPages();
        // Primera página
        $pdf->AddPage();
        $pdf->SetFont("Courier", "", 18);
        $pdf->SetTextColor(0);
        $pdf->SetFont("Arial", "", 12);
        $pdf->Cell(0, 10, "07/06/2022", 0, 2, "R");
        $pdf->SetFont("Arial", "B", 10);
        $pdf->SetDrawColor(0);
        $pdf->SetX(140);
        $pdf->SetLineWidth(0.1);
        $pdf->MultiCell(0, 5, utf8_decode("In Situ Management SLU\nB98283203\nUrb. Loma de Sancti Petri 68\nChiclana de la Frontera(11130)\nCádiz-España\n-\nPeriodo:\n15/01/2022 - 15/01/2022"), 1, "L");
        $pdf->SetFont("Arial", "", 10);
        $pdf->MultiCell(0, 5, utf8_decode("Museo del Baile Flamenco SL\nCIF: b91203166\nC/Manuel Rojas Marcos, 3\n41004 - Sevilla - ESPAÑA"), 0, "L");
        $pdf->SetY(130);
        $pdf->Cell(0, 5, "Condiciones de venta: Mencionados sobre el documento", 0, 2, "L");
        $pdf->Cell(0, 5, "Observaciones", 0, 1, "L");

        $data = ["IVA", "Base imponible", "Total IVA", "SUBTOTAL"];
        $rowFacture = [32, 32, 25, 25];
        $pdf->Ln(30);
        $pdf->SetFont("Arial", "B", 8);
        $pdf->SetX(80);
        $pdf->FactureTable($data, $rowFacture);

        $price = ["21%", "540,00", "113,40", "653,40"];
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
        $pdf->Cell(25, 5, "653,40", 1, 1, "C", 1);
        $pdf->Ln(3);
        $pdf->SetFont("Arial", "B", 10);
        $pdf->Cell(70, 5, "Datos para la transferencia:", "TLR", 2, "L");
        $pdf->SetFont("Arial", "", 10);
        $pdf->MultiCell(70, 5, "La forma de pago habitual es mediante\nprepago por transferencia contra proforma\nemitida. Datos para la Transferencia:\nBanco Bilbao Vizcaya Argentaria, IBAN:\nES23 0182 7941 8502 0157 4088 -BIC:\nBBVAESMMXX\n ", "RLB", "L");

        // Segunda página
        $pdf->AddPage();
        $pdf->SetFont("Arial", "", 8);
        $pdf->Ln(2);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0);
        $pdf->SetLineWidth(0.2);
        $pdf->SetFillColor(232, 232, 232);



        // Primera columna
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->MultiCell(17, 5, "Fecha", 1, "C", 1);

        $texto = [("15/01/2022\n\n\n"), ("15/01/2022\n\n\n"), ("15/01/2022\n\n\n")];

        $pdf->Tabla(17, 5, $texto, 1, "C", 0, 3, $x);

        //Segunda Columna
        $pdf->SetY($y);
        $pdf->SetX($x + 17);
        $x = $pdf->GetX();
        $pdf->MultiCell(13, 5, "Localiz.", 1, "C", 1);

        $texto = ["E00068\n\n\n", "P39639\n\n\n", "E98349\n\n\n"];

        $pdf->Tabla(13, 5, $texto, 1, "C", 0, 3, $x);


        // Tercera columna
        $pdf->SetY($y);
        $pdf->SetX($x + 13);
        $x = $pdf->GetX();
        $pdf->MultiCell(30, 5, "Actividad", 1, "C", 1);

        $texto = ["Clase de Baile en Estudio\n\n", utf8_decode("Clase de Percusión\n\n\n"), "Clase de Baile en Estudio\n\n"];

        $pdf->Tabla(30, 5, $texto, 1, "L", 0, 3, $x);

        // Cuarta columna
        $pdf->SetY($y);
        $pdf->SetX($x + 30);
        $x = $pdf->GetX();
        $pdf->MultiCell(10, 5, "Hora", 1, "C", 1);

        $texto = ["14:00\n\n\n", "14:00\n\n\n", "15:00\n\n\n"];

        $pdf->Tabla(10, 5, $texto, 1, "L", 0, 3, $x);

        // Quinta columna
        $pdf->SetY($y);
        $pdf->SetX($x + 10);
        $x = $pdf->GetX();
        $pdf->MultiCell(35, 5, "Nombre", 1, "C", 1);

        $texto = [utf8_decode("Grupo Blanco 2 - (Talleres baile y percusión 23-24 pax por grupo)"), utf8_decode("Grupo Blanco 2 - (Talleres baile y percusión 23-24 pax por grupo)"), utf8_decode("Grupo Blanco 2 - (Talleres baile y percusión 23-24 pax por grupo)")];

        $pdf->Tabla(35, 5, $texto, 1, "L", 0, 3, $x);
        // Sexta columna
        $pdf->SetY($y);
        $pdf->SetX($x + 35);
        $x = $pdf->GetX();
        $pdf->MultiCell(25, 5, "Responsable", 1, "C", 1);

        $texto = ["\n\n\n", "\n\n\n", "\n\n\n"];

        $pdf->Tabla(25, 5, $texto, 1, "L", 0, 3, $x);
        // Septima columna
        $pdf->SetY($y);
        $pdf->SetX($x + 25);
        $x = $pdf->GetX();
        $pdf->MultiCell(32, 5, "Entradas", 1, "C", 1);

        $texto = ["20x Adulto\n\n\n", "20x Adulto\n\n\n", "20x Adulto\n\n\n"];

        $pdf->Tabla(32, 5, $texto, 1, "L", 0, 3, $x);
        //Octava Columna
        $pdf->SetY($y);
        $pdf->SetX($x + 32);
        $x = $pdf->GetX();
        $pdf->MultiCell(15, 5, "Met.Pago", 1, "C", 1);

        $texto = ["Pago Factura\n\n", "Pago Factura\n\n", "Pago Factura\n\n"];

        $pdf->Tabla(15, 5, $texto, 1, "L", 0, 3, $x);
        // Novena columna
        $pdf->SetY($y);
        $pdf->SetX($x + 15);
        $x = $pdf->GetX();
        $pdf->MultiCell(12, 5, "Total", 1, "C", 1);

        $texto = ["217.8" . EURO . "\n\n\n", "217.8" . EURO . "\n\n\n", "217.8" . EURO . "\n\n\n"];

        $pdf->Tabla(12, 5, $texto, 1, "L", 0, 3, $x);

        // Tercera página
        $pdf->SetFillColor(255);

        $pdf->AddPage();
        $pdf->SetFont("Arial", "B", 10);
        $pdf->Ln(2);
        $pdf->Cell(0, 5, "Consulta, descarga, y gestiona tus facturas desde tu perfil de colaborador en:", 0, 2, "C");
        $pdf->SetTextColor(255, 0, 0);
        $pdf->Cell(0, 5, "https://tickets.museodelbaileflamenco.com/", 0, 2, "C");
        $pdf->Ln(10);
        $pdf->SetTextColor(0);
        $pdf->SetX(35);
        $pdf->Cell(0, 5, utf8_decode("Política de cancelación y modificación"), 0, 1, "L");
        $pdf->Ln(5);
        $pdf->SetFont("Arial", "", 10);
        $pdf->SetX(28);
        $pdf->MultiCell(168, 5, utf8_decode("La modificación de la reserva no conllevará ningún coste, siempre que se realice con al menos un día de antelación. En caso de cancelación, si se realiza antes de 48 horas, no conllevará coste alguno; dentro de las 48 horas antes de la fecha, conlleva un coste del 50%; y en caso de que se cancele 24 horas antes de la hora de la reserva, se aplicará el 100% del coste."), 0, 1, "L");

        $pdf->Ln(15);
        $pdf->SetX(35);
        $pdf->SetFont("Arial", "B", 10);
        $pdf->Cell(0, 5, utf8_decode("Acceso a la Intranet B2B"), 0, 1, "L");
        $pdf->Ln(5);
        $pdf->SetFont("Arial", "", 10);
        $pdf->SetX(28);
        $pdf->MultiCell(168, 5, utf8_decode("Recuerda que puede visualizar y descargar todas las facturas y compromisos con el Museo del Baile Flamenco, así como obtener las confirmaciones de pagos y hacer reservas a tiempo real, desde tu perfil de colaborador en la siguiente dirección web de la intranet del Museo: https://demo.museodelbaileflamenco.com/b2b-esp/, accediendo con tu usuario y contraseña. Si tienes problemas de acceso con tu usuario y contraseña, puede contactar con nuestro equipo de comunicación en: 0031 954340311, o a través de admin\"admin@museoflamenco.com\"."), 0, 1, "L");

        $pdf->Ln(15);
        $pdf->SetX(35);
        $pdf->SetFont("Arial", "B", 10);
        $pdf->Cell(0, 5, utf8_decode("Reserva"), 0, 1, "L");
        $pdf->Ln(5);
        $pdf->SetFont("Arial", "", 10);
        $pdf->SetX(28);
        $pdf->MultiCell(168, 5, utf8_decode("Para realizar una reserva es necesario reserva siempre con antelación a la actividad, indicando los servicios solicitados, número de personas, referencia de la reserva, etc. bien por el portal B2B de colaborador, o a través de los teléfonos: Tel: (0034) 954 34 03 11 + (0034) 954 00 67 87, o, a través de los correos electrónicos: info@museoflamenco.com y reservas@museoflamenco.com."), 0, 1, "L");
        $pdf->Output();
    }
}
