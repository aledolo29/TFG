<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vuelo extends Model
{
    use HasFactory;
    protected $table = "vuelos";
    protected $primaryKey = 'vuelo_Id';

    protected $fillable = [
        'vuelo_Fecha_Hora_Salida',
        'vuelo_Fecha_Hora_Llegada',
        'vuelo_AeropuertoSalida',
        'vuelo_AeropuertoLlegada',
    ];
}
