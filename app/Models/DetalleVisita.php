<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleVisita extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_data',
        'id_servicio',
        'descuento',
        'subtotal',
    ];

    public function data(){
        return $this->belongsTo(Data::class,'id_data');
    }

    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'id_servicio', 'id'); 
    }
}
