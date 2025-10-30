<?php

namespace App\Imports;

use App\Models\Data;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;

class DataImport implements ToCollection
{
    /**
     * @param Collection $rows
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            if ($index === 0) continue;
            // Mapear los datos de Excel a las columnas de la base de datos
            $data = [
                'orden' => $row[0] ?? null,
                'nombres' => $row[1] ?? null,
                'cedula' => $row[2] ?? null,
                'direccion' => $row[3] ?? null,
                'barrio' => $row[4] ?? null,
                'telefono' => $row[5] ?? null,
                'correo' => $row[6] ?? null,


                'medidor' => null,
                'lectura' => null,
                'aforo' => null,
                'resultado' => null,
                'observacion_inspeccion' => null,
                'url_foto' => null,
                'firmaUsuario' => null,
                'firmaTecnico' => null,
                'ciclo' => $row[12] ?? null,
                'id_user' => null,
                'punto_hidraulico' => null,
                'numeroPersonas' => null,
                'categoria' => null,
                'estado' => null,
            ];

            // Verificar si el registro ya existe en la base de datos por 'contrato'
            $existingRecord = Data::where('orden', $data['orden'])->first();

            if (!$existingRecord) {
                // Insertar nuevo registro si no existe
                Data::create($data);
            }

            // Si el registro ya existe, no se actualiza ni se modifica
        }
    }
}
