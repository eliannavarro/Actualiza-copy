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
                'cuentaContrato' => $row[2] ?? null,
                'direccion' => $row[3] ?? null,
                'causanl_obs' => $row[4] ?? null,
                'obs_adic' => $row[5] ?? null,
                'nombre_auditor' => $row[6] ?? null,
                'ciclo' => $row[12] ?? null,


                'lector' => null,
                'atendio_usuario' => null,
                'auditor' => null,
                'observacion_inspeccion' => null,
                'url_foto' => null,
                'id_user' => null,
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
