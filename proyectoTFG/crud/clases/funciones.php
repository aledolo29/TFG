<?php
function formatearFecha($fecha)
{
    $fechaArray = explode("-", $fecha);
    $anio = $fechaArray[0];
    $dia = $fechaArray[2];
    $fechaFormateada = $dia . "/" . $fechaArray[1] . "/" . $anio;
    return $fechaFormateada;
}
