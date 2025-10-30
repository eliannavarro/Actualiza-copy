<?php
namespace App\Exports;

use App\Models\Data;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Storage;

class DataExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithEvents
{
    protected $ciclo;

    public function __construct($ciclo = null)
    {
        $this->ciclo = $ciclo;
    }

    public function query()
    {
        // Aplicar filtro de ciclo si se proporciona y asegurar que estado sea 1
        return Data::query()
            ->where('estado', 1)
            ->when($this->ciclo, function ($query) {
                return $query->where('ciclo', $this->ciclo);
            });
    }

    public function headings(): array
    {
        return [
            'Orden',
            'Ciclo',
            'Nombres',
            'Cedula',
            'Resultado',
            'Categoría',
            'Dirección',
            'Municipio',
            'Barrio',
            'Medidor',
            'Lectura',
            'Observación Inspección',
            'Inspector',
            'Fecha',
        ];
    }

    public function map($data): array
    {
        return [
            $data->orden,
            $data->ciclo,
            $data->nombres,
            $data->cedula,
            $data->resultado,
            $data->categoria,
            $data->direccion,
            $data->municipio,
            $data->barrio,
            $data->medidor,
            $data->lectura,
            $data->observacion_inspeccion,
            optional($data->user)->name,
            $data->created_at ? $data->created_at->format('Y-m-d') : 'Unknow'
            // $data->url_foto,
            // $data->firmaUsuario, 
            // $data->firmaTecnico, 
        ];
    }



    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $ultimaFila = $sheet->getHighestRow(); // Última fila con datos

                // $columnUsuario = 'M'; // Columna donde se colocará la firma del usuario
                // $columnTecnico = 'N'; // Columna donde se colocará la firma del técnico 

                // // Primer ciclo: Para la firma del usuario
                // for ($row = 2; $row <= $ultimaFila; $row++) {
                //     $cellValue = $sheet->getCell($columnUsuario . $row)->getValue();

                //     if ($cellValue) {
                //         $filePath = Storage::disk('public')->path($cellValue); // Ruta completa

                //         // Verificar si el archivo existe
                //         if (file_exists($filePath)) {
                //             $drawing = new Drawing();
                //             $drawing->setName('FirmaUsuario');
                //             $drawing->setDescription('UsuarioTecnico');
                //             $drawing->setPath($filePath); // Establecer la ruta de la imagen
                //             $drawing->setHeight(40); // Ajusta el tamaño de la imagen
                //             $drawing->setCoordinates($columnUsuario . $row); // Coordenadas de la celda
                //             $drawing->setWorksheet($sheet); // Asignar la hoja

                //             // Redimensionar la celda para coincidir con la imagen
                //             $sheet->getColumnDimension($columnUsuario)->setWidth(15);
                //             $sheet->getRowDimension($row)->setRowHeight(35);

                //             // Limpiar el contenido de la celda
                //             $sheet->getCell($columnUsuario . $row)->setValue(null);
                //         } else {
                //             // Si no existe, escribir un mensaje de error
                //             $sheet->getCell($columnUsuario . $row)->setValue('Imagen no encontrada');
                //         }
                //     }
                // }

                // // Segundo ciclo: Para la firma del técnico
                // for ($row = 2; $row <= $ultimaFila; $row++) {
                //     $cellValue = $sheet->getCell($columnTecnico . $row)->getValue();

                //     if ($cellValue) {
                //         $filePath = Storage::disk('public')->path($cellValue); // Ruta completa

                //         // Verificar si el archivo existe
                //         if (file_exists($filePath)) {
                //             $drawing = new Drawing();
                //             $drawing->setName('FirmaTecnico');
                //             $drawing->setDescription('FirmaTecnico');
                //             $drawing->setPath($filePath); // Establecer la ruta de la imagen
                //             $drawing->setHeight(40); // Ajusta el tamaño de la imagen
                //             $drawing->setCoordinates($columnTecnico . $row); // Coordenadas de la celda
                //             $drawing->setWorksheet($sheet); // Asignar la hoja

                //             // Redimensionar la celda para coincidir con la imagen
                //             $sheet->getColumnDimension($columnTecnico)->setWidth(15);
                //             $sheet->getRowDimension($row)->setRowHeight(35);

                //             // Limpiar el contenido de la celda
                //             $sheet->getCell($columnTecnico . $row)->setValue(null);
                //         } else {
                //             // Si no existe, escribir un mensaje de error
                //             $sheet->getCell($columnTecnico . $row)->setValue('Imagen no encontrada');
                //         }
                //     }
                // }
                            

                // Aplicar estilo a los encabezados
                $sheet->getStyle('A1:S1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                ]);

                // Aplicar formato numérico a la columna 'recorrido'
                $sheet->getStyle('N2:N' . $ultimaFila)->getNumberFormat()->setFormatCode('0');

                // Alinear todos los datos a la izquierda
                $sheet->getStyle('A1:S' . $ultimaFila)->applyFromArray([
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                    ],
                ]);
            },
        ];
    }
}
