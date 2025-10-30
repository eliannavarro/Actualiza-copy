<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    use HasFactory;

    protected $table = 'servicios';

    protected $fillable = [
        'nombre',
        'precio',
        'descripcion',
    ];

    public function detalleVisitas()
    {
        return $this->hasMany(DetalleVisita::class, 'id_servicio', 'id');
    }
}
