<?php


require_once '../pdf/fpdf/fpdf.php';

define("EURO", chr(128));
class PDFService extends FPDF
{
    function Header()
    {
        $marca_agua = 'img/marca_agua.png';
        $logo = 'img/logo_azul.png';
        $this->Image($marca_agua, -35, 220, 120);
        $this->Image($logo, 5, 0, 30, 30);
        $this->SetTextColor(50, 81, 98);
        $this->SetFont("Times", "B", 24);
        $this->Cell(20);
        $this->Cell($this->GetPageWidth() / 2, 10, "INTERSTELLAR ARILINES", 0, 0, "L");
    }

    function FactureTable($data, $row)
    {
        $this->SetFillColor(232, 232, 232);
        $this->SetTextColor(0);
        $this->SetDrawColor(0);
        $this->SetLineWidth(0.3);
        $i = 0;
        foreach ($data as $col) {
            if ($i == count($data) - 1) {
                $this->Cell($row[$i], 5, htmlentities($col), 1, 1, "L", 1);
            } else {
                $this->Cell($row[$i], 5, htmlentities($col), 1, 0, "L", 1);
            }
            $i++;
        }
    }


    function SetCol($col)
    {
        // Establecer la posición de una columna dada
        $x = 10 + $col * 65;
        $this->SetLeftMargin($x);
        $this->SetX($x);
    }

    function PriceTable($data, $row)
    {
        $this->SetFillColor(255);
        $this->SetTextColor(0);
        $this->SetDrawColor(0);
        $this->SetLineWidth(0.3);
        $i = 0;
        foreach ($data as $col) {
            if ($i == count($data) - 1) {
                $this->Cell($row[$i], 5, htmlentities($col), 1, 2, "C", 1);
            } else {
                $this->Cell($row[$i], 5, htmlentities($col), 1, 0, "C", 1);
            }
            $i++;
        }
    }
    function Tabla($w, $h, $texto, $border, $align, $color, $row, $x)
    {

        for ($i = 0; $i < $row; $i++) {
            $this->SetX($x);
            $this->MultiCell($w, $h, $texto[$i], $border, $align, $color);
        }
    }


    function BodyTable($data, $row)
    {
        $this->SetFillColor(255);
        $this->SetTextColor(0);
        $this->SetDrawColor(0);
        $this->SetLineWidth(0.3);
        $i = 0;
        foreach ($data as $col) {
            if ($i == count($data) - 1) {
                $this->Cell($row[$i], 5, htmlentities($col), 1, 1, "L");
            } else {
                $this->Cell($row[$i], 5, htmlentities($col), 1, 0, "L");
            }
            $i++;
        }
    }

    function Footer()
    {
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        // Número de página
        $this->Cell(0, 10, htmlentities('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}
