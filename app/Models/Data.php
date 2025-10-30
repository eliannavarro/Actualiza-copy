<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Data extends Model
{
    use HasFactory;
    protected $fillable = [
        'orden',
        'nombres',
        'cedula',
        'direccion',
        'barrio',
        'telefono',
        'correo',
        'ciclo',

        'total',
        
        'medidor',
        'lectura',
        'aforo',
        'resultado',
        'observacion_inspeccion',
        'url_foto',
        'firmaUsuario',
        'firmaTecnico',
        'id_user',
        'puntoHidraulico',
        'numeroPersonas',
        'categoria',
    ];

    protected static function boot()
    {
        parent::boot();
    
        static::creating(function ($orden) {
            // Obtener el último número de orden
            $lastOrden = self::orderBy('orden', 'desc')->first();
    
            if ($lastOrden && preg_match('/^OR-(\d+)$/', $lastOrden->orden, $matches)) {
                $lastNumber = (int) $matches[1];
            } else {
                $lastNumber = 0;
            }
    
            // Incrementar el número
            $newNumber = $lastNumber + 1;
    
            // Formatear con ceros a la izquierda
            $formattedNumber = str_pad($newNumber, 5, '0', STR_PAD_LEFT);
    
            // Asignar el nuevo código de orden
            $orden->orden = 'OR-' . $formattedNumber;
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function detalleVisita(){
        return $this->hasMany(DetalleVisita::class, 'id_data');
    }
}


