<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "../vendor/PHPExcel/autoload.php";

require_once "conexion.php";
require_once "nomina.php";
require_once "empleado.php";
require_once "funciones.php";

$conexion = new conexion();
$empleado = new empleado();
$nomina = new nomina();
$spreadsheet = new Spreadsheet();

$spreadsheet->getProperties()
    ->setCreator("Interstellar Airlines")
    ->setLastModifiedBy("Interstellar Airlines")
    ->setTitle("Nominas Empleados")
    ->setSubject("Nominas Empleados")
    ->setDescription("Nominas Empleados")
    ->setKeywords("Nominas Empleados")
    ->setCategory("Nominas Empleados");
$spreadsheet->setActiveSheetIndex(0);
$worksheet = $spreadsheet->getActiveSheet();
if (isset($_GET['crearExcel'])) {
    $resNomina = $nomina->obtener();
    if ($resNomina == null) {
        header('location: ../crudNominas.php?nominaExcel=false');
    }
    $tuplaNomina = $conexion->BD_GetTupla($resNomina);
    $worksheet->setCellValue("A1", "Empleado");
    $worksheet->setCellValue("B1", "Fecha");
    $worksheet->setCellValue("C1", "Archivo");
    $i = 2;
    while ($tuplaNomina != null) {

        $consultaEmpleado = "WHERE empl_Id = '$tuplaNomina[nomina_Empleado_IdFK]'";
        $resEmpleado = $empleado->obtenerConFiltro($consultaEmpleado, "");
        $tuplaEmpleado = $conexion->BD_GetTupla($resEmpleado);

        $worksheet->setCellValue("A" . $i, $tuplaEmpleado['empl_Nombre']);
        $fecha = formatearFecha($tuplaNomina['nomina_Fecha']);
        $worksheet->setCellValue("B" . $i, $fecha);
        $worksheet->setCellValue("C" . $i, $tuplaNomina['nomina_Archivo']);

        $tuplaNomina = $conexion->BD_GetTupla($resNomina);
        $i++;
    }

    $fecha_Actual = date("Y-m-d H-i-s");
    $nombreArchivo = 'nomina_Empleados-' .  $fecha_Actual . '.xlsx';

    $sheet = $spreadsheet->getActiveSheet();

    foreach ($sheet->getColumnIterator() as $column) {
        $sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
    }
    $writer = new Xlsx($spreadsheet);
    $writer->save('../../archivos/excel/' . $nombreArchivo);
    header("location: ../../archivos/excel/" . $nombreArchivo);
}
