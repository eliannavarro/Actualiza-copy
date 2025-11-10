<?php

namespace App\Http\Controllers;

use App\Exports\DataExport;
use App\Exports\DataExportComplete;
use App\Imports\DataImport;
use App\Imports\DataUpdateImport;
use App\Models\Data;
use App\Models\DetalleVisita;
use App\Models\Servicio;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Yaza\LaravelGoogleDriveStorage\Gdrive;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;

class DataController extends Controller
{

    public function destroy($id)
    {
        $visita = Data::findOrFail($id);

        // Luego eliminar la visita
        $visita->delete();

        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Visita eliminada correctamente']);
        }

        return redirect()->route('asignar.index')->with('success', 'Visita eliminada correctamente');
    }


    // =============================    SIDEBAR     =============================
    public function sidebarSearch(Request $request)
    {
        $query = $request->input('buscador-sidebar');

        // Lista de secciones disponibles
        $secciones = [
            'inicio' => route('home'),
            'usuarios' => route('users.index'),
            'asignar' => route('asignar.index'),
            'desasignar' => route('desasignar.index'),
            'completados' => route('completados.index'),
            'cargar excel' => route('import.import'),
            'generar excel' => route('export'),
        ];

        // Busca una coincidencia en las secciones
        $resultado = array_filter($secciones, function ($seccion) use ($query) {
            return stripos($seccion, $query) !== false; // Buscar la coincidencia
        }, ARRAY_FILTER_USE_KEY);

        // Si encuentra una sección, redirige
        if (!empty($resultado)) {
            return redirect(array_values($resultado)[0]);
        }

        // Si no encuentra una sección, redirige al inicio
        return back()->with('error-sidebar', 'Sección no encontrada.');
    }

    // =============================      ASIGNACION      =============================

    public function asignarIndex(Request $request)
    {
        // Obtener los parámetros de ordenamiento
        $sortBy = $request->get('sortBy', 'direction');
        $direction = $request->get('direction', 'asc');

        // Validar que la columna y la dirección sean válidas
        $validColumns = ['nombres', 'cuentaContrato', 'direccion', 'causanl_obs', 'obs_adic', 'ciclo', 'nombre_auditor','medidor'];
        if (!in_array($sortBy, $validColumns)) {
            $sortBy = 'id';
        }

        if (!in_array($direction, ['asc', 'desc'])) {
            $direction = 'asc';
        }

        // Obtener los datos ordenados solo si no están relacionados con un usuario
        $data = Data::whereNull('id_user')
            ->orWhere('id_user', '')
            ->orderBy($sortBy, $direction)
            ->paginate(100);

        $operarios = User::where('rol', 'user')
            ->orderBy('name', 'asc')
            ->get();

        $totalResultados = Data::where('id_user', null)->count();

        return view('Data.Asignacion.asignacion', [
            'data' => $data,
            'operarios' => $operarios,
            'totalResultados' => $totalResultados,
            'sortBy' => $sortBy,
            'direction' => $direction,
        ]);
    }

    public function asignarFiltrar(Request $request)
    {
        $sortBy = $request->get('sortBy', 'id'); // Columna de orden por defecto
        $direction = $request->get('direction', 'asc'); // Dirección de orden por defecto

        // Validar la columna y la dirección de ordenamiento
        $validColumns = ['nombres', 'cuentaContrato', 'direccion', 'causanl_obs', 'obs_adic', 'ciclo', 'nombre_auditor', 'medidor'];
        if (!in_array($sortBy, $validColumns)) {
            $sortBy = 'id';
        }
        if (!in_array($direction, ['asc', 'desc'])) {
            $direction = 'asc';
        }

        // Genera la consulta donde 
        // -> id_user sea null para no incluir los ya asignados
        // -> estado sea null para no incluir los ya completados
        $query = Data::whereNull('id_user')
            ->whereNull('estado');

        if ($request->filled('buscador-nombre')) {
            $query->where('nombres', 'like', '%' . $request->input('buscador-nombre') . '%');
        }

        if ($request->filled('buscador-direccion')) {
            $query->where('direccion', 'like', '%' . $request->input('buscador-direccion') . '%');
        }

        if ($request->filled('buscador-cuentaContrato')) {
            $query->where('barrio', 'like', '%' . $request->input('buscador-cuentaContrato') . '%');
        }

        if ($request->filled('buscador-causanl_obs')) {
            $query->where('telefono', 'like', '%' . $request->input('buscador-causanl_obs') . '%');
        }

        if ($request->filled('buscador-obs_adic')) {
            $query->where('correo', 'like', '%' . $request->input('buscador-obs_adic') . '%');
        }
        if ($request->filled('buscador-ciclo')) {
            $query->where('ciclo', 'like', '%' . $request->input('buscador-ciclo') . '%');
        }
        if ($request->filled('buscador-nombre_auditor')) {
            $query->where('nombre_auditor', 'like', '%' . $request->input('buscador-nombre_auditor') . '%');
        }
        if ($request->filled('buscador-medidor')) {
            $query->where('medidor', 'like', '%' . $request->input('buscador-medidor') . '%');
        }

        // Aplicar el ordenamiento
        $data = $query
            ->orderBy($sortBy, $direction)
            ->paginate(100)
            ->appends($request->except('page')); // Conservar parámetros en la paginación

        $operarios = User::where('rol', 'user')->get();
        $totalResultados = $query->count();

        return view('Data.Asignacion.asignacion', compact('data', 'operarios', 'sortBy', 'direction', 'totalResultados'));
    }

    public function asignarOperario(Request $request)
    {
        $request->validate([
            'Programacion' => 'required|array',
            'operario' => 'required|exists:users,id'
        ]);

        $programaciones = $request->input('Programacion');
        $userId = $request->input('operario');

        Data::whereIn('id', $programaciones)->update(['id_user' => $userId]);

        return redirect()->route('asignar.index')->with('success', 'Operario asignado exitosamente');
    }

    // =============================      DESASIGNACION      =============================
    public function desasignarIndex(Request $request)
    {
        // Obtener los parámetros de ordenamiento
        $sortBy = $request->get('sortBy', 'id'); // Columna por defecto
        $direction = $request->get('direction', 'asc'); // Dirección por defecto

        // Validar que la columna y la dirección sean válidas
        $validColumns = ['operario', 'nombres', 'cuentaContrato', 'direccion', 'causanl_obs', 'obs_adic', 'ciclo', 'nombre_auditor','medidor'];
        if (!in_array($sortBy, $validColumns)) {
            $sortBy = 'id';
        }

        if (!in_array($direction, ['asc', 'desc'])) {
            $direction = 'asc';
        }

        // Obtener los datos ordenados
        $data = Data::whereNotNull('id_user')
            ->whereNull('estado')
            ->orderBy($sortBy, $direction)
            ->paginate(100);

        $operarios = User::where('rol', 'user')
            ->orderBy('name', 'asc')
            ->get();

        $totalResultados = Data::whereNotNull('id_user')->count();

        return view('Data.Asignacion.desasignacion', [
            'data' => $data,
            'operarios' => $operarios,
            'totalResultados' => $totalResultados,
            'sortBy' => $sortBy,
            'direction' => $direction,
        ]);
    }

    public function desasignarFiltrar(Request $request)
    {
        $sortBy = $request->get('sortBy', 'id'); // Columna de orden por defecto
        $direction = $request->get('direction', 'asc'); // Dirección de orden por defecto

        // Validar la columna y la dirección de ordenamiento
        $validColumns = ['operario', 'nombres', 'cuentaContrato', 'direccion', 'causanl_obs', 'obs_adic', 'ciclo', 'nombre_auditor', 'medidor'];
        if (!in_array($sortBy, $validColumns)) {
            $sortBy = 'id';
        }
        if (!in_array($direction, ['asc', 'desc'])) {
            $direction = 'asc';
        }

        // Construir la consulta con filtros
        $query = Data::whereNotNull('id_user')
            ->whereNull('estado');

        if ($request->filled('buscador-operario')) {
            // buscamos el id del usuario por el nombre
            $userId = User::where('name', 'like', '%' . $request->input('buscador-operario') . '%')->pluck('id');
            // filtramos por el id del usuario
            $query->whereIn('id_user', $userId);
        }
        if ($request->filled('buscador-nombre')) {
            $query->where('nombres', 'like', '%' . $request->input('buscador-nombre') . '%');
        }

        if ($request->filled('buscador-direccion')) {
            $query->where('direccion', 'like', '%' . $request->input('buscador-direccion') . '%');
        }

        if ($request->filled('buscador-cuentaContrato')) {
            $query->where('cuentaContrato', 'like', '%' . $request->input('buscador-cuentaContrato') . '%');
        }

        if ($request->filled('buscador-causanl_obs')) {
            $query->where('causanl_obs', 'like', '%' . $request->input('buscador-causanl_obs') . '%');
        }

        if ($request->filled('buscador-obs_adic')) {
            $query->where('obs_adic', 'like', '%' . $request->input('buscador-obs_adic') . '%');
        }
        if ($request->filled('buscador-ciclo')) {
            $query->where('ciclo', 'like', '%' . $request->input('buscador-ciclo') . '%');
        }

        if ($request->filled('buscador-nombre_auditor')) {
            $query->where('nombre_auditor', 'like', '%' . $request->input('buscador-nombre_auditor') . '%');
        }

        if ($request->filled('buscador-medidor')) {
            $query->where('medidor', 'like', '%' . $request->input('buscador-medidor') . '%');
        }

        // Aplicar el ordenamiento
        $data = $query
            ->orderBy($sortBy, $direction)
            ->paginate(100)
            ->appends($request->except('page')); // Conservar parámetros en la paginación

        $operarios = User::where('rol', 'user')->get();
        $totalResultados = $query->count();

        return view('Data.Asignacion.desasignacion', compact('data', 'operarios', 'sortBy', 'direction', 'totalResultados'));
    }

    public function desasignarOperario(Request $request)
    {
        $request->validate([
            'Programacion' => 'required|array',
        ]);

        $programaciones = $request->input('Programacion');

        Data::whereIn('id', $programaciones)->update(['id_user' => null]);

        return redirect()->route('desasignar.index')->with('success', 'Operario desasignado exitosamente');
    }

    // =============================      USERDATA      =============================
    public function asignadosListar(Request $request)
    {
        $userId = Auth::user()->id;
        session(['previous_url' => $request->fullUrl()]);


        // Crear la consulta base
        $query = Data::where('id_user', $userId)
            ->where(function ($query) {
                $query->where('estado', 0)
                    ->orWhereNull('estado');
            });

        // Obtener los datos paginados
        $data = $query->paginate();

        return view('Data.DataUser.index', compact('data'));
    }


    public function asignadosEdit($id)
    {

        $data = Data::findOrFail($id);

        return view('Data.DataUser.edit', compact('data'));
    }


    public function asignadosUpdate(Request $request, $id)
    {
        $data = Data::findOrFail($id);

        $direccion = $data->direccion;

        $validatedData = $request->validate([

            'lector' => 'required|string',
            'auditor' => 'required|string',
            'atendio_usuario' => 'required|string|in:si,no',
            'observacion_inspeccion' => 'required|string|max:700',
            'foto' => 'required|image|mimes:jpeg,png,jpg,bmp,tiff|max:51200',
        ], [

            'auditor.required' => 'El medidor es obligatorio.',
            'auditor.string' => 'El medidor debe ser un texto válido.',

            'lector.required' => 'La lectura es obligatoria.',
            'lector.string' => 'El medidor debe ser un texto válido.',

            'atendio_usuario.required' => 'La categoría es obligatoria.',
            'atendio_usuario.in' => 'si, no',

            'observacion_inspeccion.required' => 'La observación es obligatoria.',
            'observacion_inspeccion.string' => 'La observación debe ser un texto válido.',
            'observacion_inspeccion.max' => 'La observación no puede contener más de 700 caracteres.',

            'foto.required' => 'La evidencia es obligatoria.',
            'foto.image' => 'La evidencia debe ser una imagen válida.',
            'foto.mimes' => 'La evidencia debe estar en formato JPEG, PNG, JPG, BMP o TIFF.',
            'foto.max' => 'La evidencia no debe superar los 50MB.',

        ]);

        $data->fill($validatedData);

        // Subir la foto localmente
        $mesActual = date('F');
        $userFolder = auth()->user()->name;
        $direccion = $data->direccion;
        $fotoFile = $validatedData['foto'];
        $fotoFileName = uniqid() . '.' . $fotoFile->getClientOriginalExtension();
        $fotoPath = "Apptualiza/{$mesActual}/{$userFolder}/{$direccion}/evidencia/{$fotoFileName}";

        // Guardamos el archivo en storage/app/public/...
        Storage::disk('public')->put($fotoPath, File::get($fotoFile));

        /// Guardamos SOLO la ruta relativa en BD
        $data->url_foto = $fotoPath;

        // Cambiar el estado y guardar todo
        $data->estado = 1;
        $data->save();

        return redirect()->route('ticket.options', ['id' => $data->id])->with('success', 'Datos actualizados correctamente');
    }

    // =============================      AGENDAR      =============================

    // Mostrar formulario vacío para crear nuevo registro
    public function create()
    {
        return view('Data.Agendar.agendar');
    }

    // Guardar nuevo registro
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nombres' => 'required|string|max:255|regex:/^[\pL0-9\s\-]+$/u',
            'nombre_auditor' => 'required|string|max:255|regex:/^[\pL0-9\s\-]+$/u',
            'cuentaContrato' => 'required|string|max:255|regex:/^[^#]+$/',
            'direccion' => 'required|string|max:255|regex:/^[^#]+$/',
            'causanl_obs' => 'required|string|max:250',
            'obs_adic' => 'required|string|max:255',
            'ciclo' => 'required|string|in:105, 114, 119, 123',
            'medidor' => 'required|string|max:255|regex:/^[^#]+$/',

        ], [
            'nombres.required' => 'El nombre es obligatorio.',
            'nombres.regex' => 'El nombre solo puede contener letras mayusculas, minusculas, espacios, guiones y numeros.',
            'nombre_auditor' => 'El nombre del auditor es obligatorio.',
            'cuentaContrato.required' => 'La cuenta contrato es obligatoria.',
            'direccion.required' => 'La dirección es obligatoria.',
            'direccion.regex' => 'Solo ingrese caracteres alfanuméricos.',
            'causanl_obs.required' => 'El causanl_obs es obligatorio.',
            'obs_adic.required' => 'El obs_adic es obligatorio.',
            'ciclo.required' => 'La categoría es obligatoria.',
            'ciclo.in' => '105, 114, 119, 123',
            'medidor' => 'La categoría es obligatoria.',

        ]);
        // Crear registro
        $data = Data::create($validatedData);

        return redirect()->route('schedule.create')->with('success', 'Registro creado exitosamente.');
    }

    // =============================      IMPORTAR      =============================
    public function showUploadForm()
    {
        return view('Data.Importar.import');
    }

    public function replaceData(Request $request)
    {
        // dd($request->all());
        // Validar el archivo
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls'
        ]);

        // Limpiar todos los registros existentes en la tabla Data
        Data::truncate(); // Elimina todos los registros de la tabla

        // Procesar el archivo de importación
        Excel::import(new DataImport, $request->file('file'));

        // Mensaje de éxito
        return redirect()->back()->with('success', 'Datos reemplazados exitosamente.');
    }

    public function addData(Request $request)
    {
        // Validar el archivo
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls'
        ]);

        // Procesar el archivo de importación
        Excel::import(new DataImport, $request->file('file'));

        // Mensaje de éxito
        return redirect()->back()->with('success', 'Datos agregados exitosamente.');
    }

    // =============================      COMPLETADOS      =============================
    public function completadosIndex(Request $request)
    {

        // Obtener los parámetros de ordenamiento
        $sortBy = $request->get('sortBy', 'id'); // Columna por defecto
        $direction = $request->get('direction', 'asc'); // Dirección por defecto

        // Validar que la columna y la dirección de ordenamiento sean válidas
        $validColumns = ['operario', 'nombres', 'direccion', 'cuentaContrato', 'causanl_obs', 'obs_adic', 'ciclo', 'nombre_auditor','medidor'];
        if (!in_array($sortBy, $validColumns)) {
            $sortBy = 'id';
        }

        if (!in_array($direction, ['asc', 'desc'])) {
            $direction = 'asc';
        }

        // Obtener los datos ordenados
        $datas = Data::where('estado', 1)
            ->orderBy($sortBy, $direction)
            ->paginate(100);

        $totalResultados = Data::where('id_user', null)->count();

        return view('Data.Completados.index', [
            'datas' => $datas,
            'totalResultados' => $totalResultados,
            'sortBy' => $sortBy,
            'direction' => $direction,
        ]);
    }

    public function editCompletados($dataId)
    {
        $data = Data::find($dataId);

        $atendio_usuario = [
            "si",
            "no",
        ];

        $ciclo =
            [
                "105",
                "114",
                "119",
                "123",
            ];

        return view('Data.Completados.edit', compact('data', 'ciclo', 'atendio_usuario'));
    }
    public function updateCompletados(Request $request, $dataId)
    {
        $data = Data::findOrFail($dataId);


        $validatedData = $request->validate([
            // Nuevas validaciones agregadas
            'nombres' => 'required|string|max:255|regex:/^[\pL\s\-]+$/u',
            'direccion' => 'required|string|max:255|regex:/^[^#]+$/',
            'cuentaContrato' => 'required|string|max:100',
            'nombre_auditor' => 'required|string|max:100',
            'causanl_obs' => 'required|string|max:220',
            'obs_adic' => 'required|string|max:220',
            'ciclo' => 'required|string|in:105, 114, 119, 123',
            'medidor' => 'required|string|max:100',

            'atendio_usuario' => 'required|string|in:si,no',
            'lector' => 'required|string',
            'auditor' => 'required|string',
            'observacion_inspeccion' => 'required|string',
        ], [
             'nombres.required' => 'El nombre es obligatorio.',
            'nombres.regex' => 'El nombre solo puede contener letras mayusculas, minusculas, espacios, guiones y numeros.',

            'direccion.required' => 'La dirección es obligatoria.',
            'direccion.regex' => 'Solo ingrese caracteres alfanuméricos.',

            'cuentaContrato.required' => 'obligatorio',

            'causanl_obs' => 'obligatorio',

            'obs_adic.required' => 'obligatorio.',

            'medidor' => 'obligatorio.',

            'nombre_auditor' => 'El nombre del auditor es obligatorio.',

            'ciclo.required' => 'La categoría es obligatoria.',
            'ciclo.in' => '105, 114, 119, 123',

            'atendio_usuario.required' => 'La categoría es obligatoria.',
            'atendio_usuario.in' => 'si, no',

            'lector.required' => 'obligatorio.',

            'lectura.required' => 'La lectura es obligatoria.',

            'auditor.required' => 'obligatorio.',

            'observacion_inspeccion.required' => 'La observación es obligatoria.',
            'observacion_inspeccion.string' => 'La observación debe ser un texto válido.',

        ]);

        $data->fill($validatedData);

        $data->estado = 1;
        $data->save();

        return redirect()->route('ticket.options', ['id' => $data->id])->with('success', 'Datos actualizados correctamente');
    }

    public function completadosDestroy($dataId)
    {
        $deleted = DB::table('data')->where('id', $dataId)->delete();

        return redirect()->route('completados.index')
            ->with('success', 'Registro eliminado correctamente.');

    }

    public function completadosFiltrar(Request $request)
    {
        $sortBy = $request->get('sortBy', 'id'); // Columna de orden por defecto
        $direction = $request->get('direction', 'asc'); // Dirección de orden por defecto

        // Validar la columna y la dirección de ordenamiento
        $validColumns = ['operario', 'nombres', 'direccion', 'cuentaContrato', 'nombre_auditor'];
        if (!in_array($sortBy, $validColumns)) {
            $sortBy = 'id';
        }
        if (!in_array($direction, ['asc', 'desc'])) {
            $direction = 'asc';
        }

        // Construir la consulta con filtros
        $query = Data::where('estado', 1);

        if ($request->filled('buscador-operario')) {
            // buscamos el id del usuario por el nombre
            $userId = User::where('name', 'like', '%' . $request->input('buscador-operario') . '%')->pluck('id');
            // filtramos por el id del usuario
            $query->whereIn('id_user', $userId);
        }

        if ($request->filled('buscador-orden')) {
            $query->where('orden', 'like', '%' . $request->input('buscador-orden') . '%');
        }

        if ($request->filled('buscador-nombre')) {
            $query->where('nombres', 'like', '%' . $request->input('buscador-nombre') . '%');
        }

        if ($request->filled('buscador-direccion')) {
            $query->where('direccion', 'like', '%' . $request->input('buscador-direccion') . '%');
        }


        // Aplicar el ordenamiento
        $datas = $query
            ->orderBy($sortBy, $direction)
            ->paginate(100)
            ->appends($request->except('page')); // Conservar parámetros en la paginación

        $totalResultados = $query->count();

        return view('Data.Completados.index', compact('datas', 'sortBy', 'direction', 'totalResultados'));
    }

    public function completadosShow($id)
    {
        $data = Data::findOrFail($id); // Encontrar el registro por ID
        return view('Data.show', compact('data'));
    }

    // =============================      EXPORTAR      =============================
    public function exportarIndex()
    {
        $ciclos = Data::select('ciclo')->distinct()->pluck('ciclo');
        return view('Data.Exportar.export', compact('ciclos'));
    }

    public function exportarFiltrar(Request $request)
    {
        // Obtener el valor del ciclo del request
        $ciclo = $request->input('ciclo');

        // Filtrar los datos según el ciclo
        if ($ciclo === '0') {
            // Si el ciclo es 'all', mostrar todos los registros
            $datas = Data::where('estado', 1)->paginate(10);
        } elseif ($ciclo) {
            // Si se ha seleccionado un ciclo específico, filtrar por ese ciclo
            $datas = Data::where('estado', 1)
                ->where('ciclo', $ciclo)
                ->paginate(10);
        } else {
            // Si no se ha seleccionado ningún ciclo, no se deben mostrar resultados
            $datas = collect(); // Esto es equivalente a una consulta vacía
        }

        // Obtener ciclos únicos para el filtro en la vista
        $ciclos = Data::select('ciclo')->distinct()->pluck('ciclo');

        // Si la solicitud es AJAX, devolver los datos como JSON
        if ($request->ajax()) {
            return response()->json([
                'datas' => $datas,
                'ciclos' => $ciclos,
                'pagination' => [
                    'total' => $datas->total(),
                    'current_page' => $datas->currentPage(),
                    'last_page' => $datas->lastPage(),
                ]
            ]);
        }

        // Si no es AJAX, mostrar la vista normal
        return view('Data.Exportar.export', compact('datas', 'ciclos', 'ciclo'));
    }

    public function exportData(Request $request)
    {
        $ciclo = $request->input('ciclo');

        if ($ciclo === 'null') {
            return redirect()->back()->with('error', 'Debes seleccionar un ciclo para exportar.');
        }
        // Filtrar los registros según el ciclo y el estado = 1
        $query = Data::where('estado', 1);
        if ($ciclo && $ciclo !== 'all') {
            $query->where('ciclo', $ciclo);
        }
        // Obtener la cantidad de registros que serán exportados
        $cantidadRegistros = $query->count();
        // Obtener la hora actual
        $horaActual = now()->format('Y-m-d_H-i-s');  // Formato: año-mes-día_hora-minuto-segundo

        // Crear el nombre del archivo
        $nombreArchivo = 'Apptualiza_' . $horaActual . '_' . $cantidadRegistros . '.xlsx';

        // Realizar la exportación a Excel con el nombre generado
        return Excel::download(new DataExport($ciclo), $nombreArchivo);
    }
}
