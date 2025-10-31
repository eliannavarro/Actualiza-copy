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

        // Eliminar los detalles asociados
        DetalleVisita::where('id_data', $visita->id)->delete();

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
        $validColumns = ['nombres', 'cuentaContrato', 'direccion', 'causanl_obs', 'obs_adic'];
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
        $validColumns = ['nombres', 'cuentaContrato', 'direccion', 'causanl_obs', 'obs_adic'];
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
        $validColumns = ['operario', 'nombres', 'cuentaContrato','direccion', 'causanl_obs', 'obs_adic'];
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
        $validColumns = ['operario', 'nombres', 'cuentaContrato', 'causanl_obs', 'obs_adic'];
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
        $data = $query->paginate(1);

        return view('Data.DataUser.index', compact('data'));
    }

    public function asignadosEdit($id)
    {

        $data = Data::findOrFail($id);

        $resultados = [
            "Fuga Imperceptible",
            "Fuga Perceptible",
            "Medidor Instalado en Reversa",
            "Predio sin Fuga",
            "Fuga No Visible No Localizada",
            "Sector sin Suministro de Agua",
            "Acceso Dificultoso",
            "Revisión Inconclusa",
            "Fuga Visible",
            "Fuga En Instalación",
            "No Hay Medidor en el Predio",
            "Fuga Aguas Residuales",
        ];

        return view('Data.DataUser.edit', compact('data', 'resultados'));
    }


    public function asignadosUpdate(Request $request, $id)
    {
        $data = Data::findOrFail($id);

        $direccion = $data->direccion;

        $request->merge([
            'numeroPersonas' => (int) $request->numeroPersonas,
        ]);

        $validatedData = $request->validate([
            'numeroPersonas' => 'required|integer',
            'categoria' => 'required|string|in:residencial,comercial,industrial',
            'puntoHidraulico' => 'required|integer',
            'medidor' => 'required|string',
            'lectura' => 'required|string',
            'aforo' => 'required|string',
            'observacion_inspeccion' => 'required|string|max:700',
            'resultado' => 'required|string',
            'foto' => 'required|image|mimes:jpeg,png,jpg,bmp,tiff|max:51200',
            'firmaUsuario' => 'required|string',
            // 'firmaTecnico' => 'required|string',
        ], [
            'numeroPersonas.required' => 'El número de personas es obligatorio.',
            'numeroPersonas.integer' => 'El número de personas debe ser un número válido.',

            'categoria.required' => 'La categoría es obligatoria.',
            'categoria.in' => 'La categoría debe ser Residencial, Comercial o Industrial.',

            'puntoHidraulico.required' => 'El punto hidráulico es obligatorio.',
            'puntoHidraulico.integer' => 'El punto hidráulico debe ser un número válido.',

            'medidor.required' => 'El medidor es obligatorio.',
            'medidor.string' => 'El medidor debe ser un texto válido.',

            'lectura.required' => 'La lectura es obligatoria.',
            'lectura.integer' => 'La lectura debe ser un número entero.',

            'aforo.required' => 'El aforo es obligatorio.',
            'aforo.string' => 'El aforo debe ser un texto válido.',

            'observacion_inspeccion.required' => 'La observación es obligatoria.',
            'observacion_inspeccion.string' => 'La observación debe ser un texto válido.',
            'observacion_inspeccion.max' => 'La observación no puede contener más de 700 caracteres.',

            'resultado.required' => 'El resultado es obligatorio.',
            'resultado.string' => 'El resultado debe ser un texto válido.',

            'foto.required' => 'La evidencia es obligatoria.',
            'foto.image' => 'La evidencia debe ser una imagen válida.',
            'foto.mimes' => 'La evidencia debe estar en formato JPEG, PNG, JPG, BMP o TIFF.',
            'foto.max' => 'La evidencia no debe superar los 50MB.',

            'firmaUsuario.required' => 'La firma del usuario es obligatoria.',
            'firmaUsuario.string' => 'La firma del usuario debe ser un texto válido.',

            // 'firmaTecnico.required' => 'La firma del técnico es obligatoria.',
            // 'firmaTecnico.string' => 'La firma del técnico debe ser un texto válido.',
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

        try {
            //  Guardar firma del CLIENTE
            if ($request->has('firmaUsuario')) {
                $image = $request->input('firmaUsuario');
                $image = preg_replace('/^data:image\/\w+;base64,/', '', $image);
                $imageData = base64_decode($image);

                $firmaUsuarioFileName = uniqid() . '.png';
                $firmaUsuarioPath = "Apptualiza/{$mesActual}/{$userFolder}/{$direccion}/firma del usuario/{$firmaUsuarioFileName}";

                Storage::disk('public')->put($firmaUsuarioPath, $imageData);

                //  Guardar solo en la tabla `data`
                $data->firmaUsuario = $firmaUsuarioPath;
            }

            //  Guardar la firma del técnico (ya creada al registrar su usuario)
            $user = auth()->user();
            if ($user && $user->firma_path) {
                $data->firmaTecnico = $user->firma_path;
            }

        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error al procesar las firmas: ' . $e->getMessage());
        }

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
            'cuentaContrato' => 'required|string|max:255|regex:/^[^#]+$/',
            'direccion' => 'required|string|max:255|regex:/^[^#]+$/',
            'causanl_obs' => 'required|string|max:250',
            'obs_adic' => 'required|string|max:255',
        ], [
            'nombres.required' => 'El nombre es obligatorio.',
            'nombres.regex' => 'El nombre solo puede contener letras mayusculas, minusculas, espacios, guiones y numeros.',
            'cuentaContrato.required' => 'La cuenta contrato es obligatoria.',
            'direccion.required' => 'La dirección es obligatoria.',
            'direccion.regex' => 'Solo ingrese caracteres alfanuméricos.',
            'causanl_obs.required' => 'El causanl_obs es obligatorio.',
            'obs_adic.required' => 'El obs_adic es obligatorio.',
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
        $validColumns = ['operario', 'nombres', 'direccion', 'barrio', 'telefono', 'correo', 'orden'];
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

        $resultados = [
            "Fuga Imperceptible",
            "Fuga Perceptible",
            "Medidor Instalado en Reversa",
            "Predio sin Fuga",
            "Fuga No Visible No Localizada",
            "Sector sin Suministro de Agua",
            "Acceso Dificultoso",
            "Revisión Inconclusa",
            "Fuga Visible",
            "Fuga En Instalación",
            "No Hay Medidor en el Predio",
            "Fuga Aguas Residuales",
        ];

        $ciclos = [
            "BRN - ELE",
            "BRN - CLD",
            "BRN - CNG",
            "BRN - ESP",
            "BQ - R03",
            "BQ - R07",
            "BQ - R21",
            "BQ - R60",
            "BQ - R10",
            "BQ - R33",
            "BQ - R59",
            "BQ - R04",
            "BQ - R11",
            "BQ - RFS",
            "BQ - R61",
            "BQ - R26",
            "BQ - R36",
            "BQ - R09",
            "BQ - R38",
            "BQ - R08",
            "BQ - R41",
            "BQ - R45",
            "BQ - R35",
            "BQ - R13",
            "BQ - R23",
            "BQ - R34",
            "BQ - R01",
            "BQ - R44",
            "BQ - R32",
            "BQ - R55",
            "BQ - R39",
            "BQ - R22",
            "BQ - R37",
            "BQ - R14",
            "BQ - R43",
            "BQ - R05",
            "BQ - R42",
            "BQ - R19",
            "BQ - R54",
            "BQ - R40",
            "BQ - R02",
            "BQ - R12",
            "BQ - R06",
            "BQ - R15",
            "BQ - R20",
            "BQ - R18",
            "BQ - R24",
            "BQ - R27",
            "BQ - R16",
            "BQ - R30",
            "BQ - R25",
            "BQ - R17",
            "BQ - R29",
            "BQ - R28",
            "BQ - R51",
            "BQ - R31",
            "BQ - R52",
            "BQ - R53",
            "BQ - R56",
            "BQ - R57",
            "GLP - PLT",
            "GLP - ABO",
            "GLP - ABA",
            "GLP - MDF",
            "JDA - R1N",
            "JDA - SJS",
            "JDA - R4T ",
            "JDA - R5R",
            "JDA - R2V",
            "MLB - RM1",
            "MLB - RM2",
            "PMR - RA1",
            "PMR - RA2",
            "PMR - RA3",
            "PIJ - RP1",
            "PIJ - RP2",
            "PIJ - RP3",
            "POL - RL1",
            "POL - RL2",
            "PNR - RD1",
            "PNR - RD2",
            "PNR - RD3",
            "PTO - RPB",
            "PTO - RVC",
            "PTO - RBB",
            "PTO - RMM",
            "PTO - RSS",
            "PTO - RMC",
            "SGD - RS2",
            "SGD - RS1",
            "SGD - RS3",
            "SLG - RL3",
            "SLG - RL1",
            "SLG - RL2",
            "SLG - RL4",
            "SLG - RL5",
            "STO - RO2",
            "STO - RO1",
            "STO - RO3",
            "SLD - R41",
            "SLD - R20",
            "SLD - R24",
            "SLD - R43",
            "SLD - R04",
            "SLD - R21",
            "SLD - R22",
            "SLD - R18",
            "SLD - R23",
            "SLD - R05",
            "SLD - R30B",
            "SLD - R26",
            "SLD - R09",
            "SLD - R08",
            "SLD - R27",
            "SLD - R31",
            "SLD - R02",
            "SLD - R29",
            "SLD - R03",
            "SLD - R13A",
            "SLD - R28",
            "SLD - R28B",
            "SLD - R30A",
            "SLD - R15A",
            "SLD - R12",
            "SLD - R28A",
            "SLD - R13",
            "SLD - R25",
            "SLD - R15B",
            "SLD - R15",
            "SLD - R34",
            "SLD - R42",
            "SLD - R30C",
            "TBR - R3",
            "TBR - R2",
            "TBR - R4",
            "TBR - R1",
            "TBR - R5",
            "USR - R1",
        ];

        return view('Data.Completados.edit', compact('data', 'resultados', 'ciclos'));
    }
    public function updateCompletados(Request $request, $dataId)
    {
        $data = Data::findOrFail($dataId);

        $ciclos = [
            "BRN - ELE",
            "BRN - CLD",
            "BRN - CNG",
            "BRN - ESP",
            "BQ - R03",
            "BQ - R07",
            "BQ - R21",
            "BQ - R60",
            "BQ - R10",
            "BQ - R33",
            "BQ - R59",
            "BQ - R04",
            "BQ - R11",
            "BQ - RFS",
            "BQ - R61",
            "BQ - R26",
            "BQ - R36",
            "BQ - R09",
            "BQ - R38",
            "BQ - R08",
            "BQ - R41",
            "BQ - R45",
            "BQ - R35",
            "BQ - R13",
            "BQ - R23",
            "BQ - R34",
            "BQ - R01",
            "BQ - R44",
            "BQ - R32",
            "BQ - R55",
            "BQ - R39",
            "BQ - R22",
            "BQ - R37",
            "BQ - R14",
            "BQ - R43",
            "BQ - R05",
            "BQ - R42",
            "BQ - R19",
            "BQ - R54",
            "BQ - R40",
            "BQ - R02",
            "BQ - R12",
            "BQ - R06",
            "BQ - R15",
            "BQ - R20",
            "BQ - R18",
            "BQ - R24",
            "BQ - R27",
            "BQ - R16",
            "BQ - R30",
            "BQ - R25",
            "BQ - R17",
            "BQ - R29",
            "BQ - R28",
            "BQ - R51",
            "BQ - R31",
            "BQ - R52",
            "BQ - R53",
            "BQ - R56",
            "BQ - R57",
            "GLP - PLT",
            "GLP - ABO",
            "GLP - ABA",
            "GLP - MDF",
            "JDA - R1N",
            "JDA - SJS",
            "JDA - R4T ",
            "JDA - R5R",
            "JDA - R2V",
            "MLB - RM1",
            "MLB - RM2",
            "PMR - RA1",
            "PMR - RA2",
            "PMR - RA3",
            "PIJ - RP1",
            "PIJ - RP2",
            "PIJ - RP3",
            "POL - RL1",
            "POL - RL2",
            "PNR - RD1",
            "PNR - RD2",
            "PNR - RD3",
            "PTO - RPB",
            "PTO - RVC",
            "PTO - RBB",
            "PTO - RMM",
            "PTO - RSS",
            "PTO - RMC",
            "SGD - RS2",
            "SGD - RS1",
            "SGD - RS3",
            "SLG - RL3",
            "SLG - RL1",
            "SLG - RL2",
            "SLG - RL4",
            "SLG - RL5",
            "STO - RO2",
            "STO - RO1",
            "STO - RO3",
            "SLD - R41",
            "SLD - R20",
            "SLD - R24",
            "SLD - R43",
            "SLD - R04",
            "SLD - R21",
            "SLD - R22",
            "SLD - R18",
            "SLD - R23",
            "SLD - R05",
            "SLD - R30B",
            "SLD - R26",
            "SLD - R09",
            "SLD - R08",
            "SLD - R27",
            "SLD - R31",
            "SLD - R02",
            "SLD - R29",
            "SLD - R03",
            "SLD - R13A",
            "SLD - R28",
            "SLD - R28B",
            "SLD - R30A",
            "SLD - R15A",
            "SLD - R12",
            "SLD - R28A",
            "SLD - R13",
            "SLD - R25",
            "SLD - R15B",
            "SLD - R15",
            "SLD - R34",
            "SLD - R42",
            "SLD - R30C",
            "TBR - R3",
            "TBR - R2",
            "TBR - R4",
            "TBR - R1",
            "TBR - R5",
            "USR - R1",
        ];

        $validatedData = $request->validate([
            // Nuevas validaciones agregadas
            'nombres' => 'required|string|max:255|regex:/^[\pL\s\-]+$/u',
            'cedula' => 'required|numeric|digits_between:6,10',
            'direccion' => 'required|string|max:255|regex:/^[^#]+$/',
            'barrio' => 'required|string|max:100',
            'telefono' => 'required|digits:10',
            'correo' => 'nullable|email|max:255',
            'ciclo' => ['nullable', 'string', 'max:255', Rule::in($ciclos)],
            'numeroPersonas' => 'required|integer',
            'categoria' => 'required|string|in:residencial,comercial,industrial',
            'puntoHidraulico' => 'required|integer',
            'medidor' => 'required|string',
            'lectura' => 'required|string',
            'aforo' => 'required|string',
            'observacion_inspeccion' => 'required|string',
            'resultado' => 'required|string',
        ], [
            'nombres.required' => 'El nombre es obligatorio.',
            'nombres.regex' => 'El nombre solo puede contener letras, espacios y guiones.',

            'cedula.required' => 'La cédula es obligatoria.',
            'cedula.numeric' => 'La cédula solo puede contener números.',
            'cedula.digits_between' => 'La cédula debe tener entre 6 y 10 dígitos.',

            'direccion.required' => 'La dirección es obligatoria.',
            'direccion.regex' => 'Solo ingrese caracteres alfanuméricos.',

            'barrio.required' => 'El barrio es obligatorio.',

            'telefono.required' => 'El teléfono es obligatorio.',
            'telefono.digits' => 'El teléfono debe contener exactamente 10 dígitos.',

            'correo.email' => 'Debe ingresar un correo válido.',

            'ciclo.string' => 'Debe ingresar un ciclo válido.',
            'ciclo.in' => 'Debe ingresar un ciclo válido.',

            'numeroPersonas.required' => 'El número de personas es obligatorio.',
            'numeroPersonas.integer' => 'El número de personas debe ser un número válido.',

            'categoria.required' => 'La categoría es obligatoria.',
            'categoria.in' => 'La categoría debe ser Residencial, Comercial o Industrial.',

            'puntoHidraulico.required' => 'El punto hidráulico es obligatorio.',
            'puntoHidraulico.integer' => 'El punto hidráulico debe ser un número válido.',

            'medidor.required' => 'El medidor es obligatorio.',
            'medidor.string' => 'El medidor debe ser un texto válido.',

            'lectura.required' => 'La lectura es obligatoria.',

            'aforo.required' => 'El aforo es obligatorio.',
            'aforo.string' => 'El aforo debe ser un texto válido.',

            'observacion_inspeccion.required' => 'La observación es obligatoria.',
            'observacion_inspeccion.string' => 'La observación debe ser un texto válido.',

            'resultado.required' => 'El resultado es obligatorio.',
            'resultado.string' => 'El resultado debe ser un texto válido.',
        ]);

        $data->fill($validatedData);

        $data->estado = 1;

        // Mapa ciclo → municipio
        $cicloMunicipios = [
            "BRN - ELE" => "BARANOA",
            "BRN - CLD" => "BARANOA",
            "BRN - CNG" => "BARANOA",
            "BRN - ESP" => "BARANOA",
            "BQ - R03" => "BARRANQUILLA",
            "BQ - R07" => "BARRANQUILLA",
            "BQ - R21" => "BARRANQUILLA",
            "BQ - R60" => "BARRANQUILLA",
            "BQ - R10" => "BARRANQUILLA",
            "BQ - R33" => "BARRANQUILLA",
            "BQ - R59" => "BARRANQUILLA",
            "BQ - R04" => "BARRANQUILLA",
            "BQ - R11" => "BARRANQUILLA",
            "BQ - RFS" => "BARRANQUILLA",
            "BQ - R61" => "BARRANQUILLA",
            "BQ - R26" => "BARRANQUILLA",
            "BQ - R36" => "BARRANQUILLA",
            "BQ - R09" => "BARRANQUILLA",
            "BQ - R38" => "BARRANQUILLA",
            "BQ - R08" => "BARRANQUILLA",
            "BQ - R41" => "BARRANQUILLA",
            "BQ - R45" => "BARRANQUILLA",
            "BQ - R35" => "BARRANQUILLA",
            "BQ - R13" => "BARRANQUILLA",
            "BQ - R23" => "BARRANQUILLA",
            "BQ - R34" => "BARRANQUILLA",
            "BQ - R01" => "BARRANQUILLA",
            "BQ - R44" => "BARRANQUILLA",
            "BQ - R32" => "BARRANQUILLA",
            "BQ - R55" => "BARRANQUILLA",
            "BQ - R39" => "BARRANQUILLA",
            "BQ - R22" => "BARRANQUILLA",
            "BQ - R37" => "BARRANQUILLA",
            "BQ - R14" => "BARRANQUILLA",
            "BQ - R43" => "BARRANQUILLA",
            "BQ - R05" => "BARRANQUILLA",
            "BQ - R42" => "BARRANQUILLA",
            "BQ - R19" => "BARRANQUILLA",
            "BQ - R54" => "BARRANQUILLA",
            "BQ - R40" => "BARRANQUILLA",
            "BQ - R02" => "BARRANQUILLA",
            "BQ - R12" => "BARRANQUILLA",
            "BQ - R06" => "BARRANQUILLA",
            "BQ - R15" => "BARRANQUILLA",
            "BQ - R20" => "BARRANQUILLA",
            "BQ - R18" => "BARRANQUILLA",
            "BQ - R24" => "BARRANQUILLA",
            "BQ - R27" => "BARRANQUILLA",
            "BQ - R16" => "BARRANQUILLA",
            "BQ - R30" => "BARRANQUILLA",
            "BQ - R25" => "BARRANQUILLA",
            "BQ - R17" => "BARRANQUILLA",
            "BQ - R29" => "BARRANQUILLA",
            "BQ - R28" => "BARRANQUILLA",
            "BQ - R51" => "BARRANQUILLA",
            "BQ - R31" => "BARRANQUILLA",
            "BQ - R52" => "BARRANQUILLA",
            "BQ - R53" => "BARRANQUILLA",
            "BQ - R56" => "BARRANQUILLA",
            "BQ - R57" => "BARRANQUILLA",
            "GLP - PLT" => "GALAPA",
            "GLP - ABO" => "GALAPA",
            "GLP - ABA" => "GALAPA",
            "GLP - MDF" => "GALAPA",
            "JDA - R1N" => "JUAN DE ACOSTA",
            "JDA - SJS" => "JUAN DE ACOSTA",
            "JDA - R4T " => "JUAN DE ACOSTA",
            "JDA - R5R" => "JUAN DE ACOSTA",
            "JDA - R2V" => "JUAN DE ACOSTA",
            "MLB - RM1" => "MALAMBO",
            "MLB - RM2" => "MALAMBO",
            "PMR - RA1" => "PALMAR DE VARELA",
            "PMR - RA2" => "PALMAR DE VARELA",
            "PMR - RA3" => "PALMAR DE VARELA",
            "PIJ - RP1" => "PIOJÓ",
            "PIJ - RP2" => "PIOJÓ",
            "PIJ - RP3" => "PIOJÓ",
            "POL - RL1" => "POLONUEVO",
            "POL - RL2" => "POLONUEVO",
            "PNR - RD1" => "PONEDERA",
            "PNR - RD2" => "PONEDERA",
            "PNR - RD3" => "PONEDERA",
            "PTO - RPB" => "PUERTO COLOMBIA",
            "PTO - RVC" => "PUERTO COLOMBIA",
            "PTO - RBB" => "PUERTO COLOMBIA",
            "PTO - RMM" => "PUERTO COLOMBIA",
            "PTO - RSS" => "PUERTO COLOMBIA",
            "PTO - RMC" => "PUERTO COLOMBIA",
            "SGD - RS2" => "SABANAGRANDE",
            "SGD - RS1" => "SABANAGRANDE",
            "SGD - RS3" => "SABANAGRANDE",
            "SLG - RL3" => "SABANALARGA",
            "SLG - RL1" => "SABANALARGA",
            "SLG - RL2" => "SABANALARGA",
            "SLG - RL4" => "SABANALARGA",
            "SLG - RL5" => "SABANALARGA",
            "STO - RO2" => "SANTO TOMÁS",
            "STO - RO1" => "SANTO TOMÁS",
            "STO - RO3" => "SANTO TOMÁS",
            "SLD - R41" => "SOLEDAD",
            "SLD - R20" => "SOLEDAD",
            "SLD - R24" => "SOLEDAD",
            "SLD - R43" => "SOLEDAD",
            "SLD - R04" => "SOLEDAD",
            "SLD - R21" => "SOLEDAD",
            "SLD - R22" => "SOLEDAD",
            "SLD - R18" => "SOLEDAD",
            "SLD - R23" => "SOLEDAD",
            "SLD - R05" => "SOLEDAD",
            "SLD - R30B" => "SOLEDAD",
            "SLD - R26" => "SOLEDAD",
            "SLD - R09" => "SOLEDAD",
            "SLD - R08" => "SOLEDAD",
            "SLD - R27" => "SOLEDAD",
            "SLD - R31" => "SOLEDAD",
            "SLD - R02" => "SOLEDAD",
            "SLD - R29" => "SOLEDAD",
            "SLD - R03" => "SOLEDAD",
            "SLD - R13A" => "SOLEDAD",
            "SLD - R28" => "SOLEDAD",
            "SLD - R28B" => "SOLEDAD",
            "SLD - R30A" => "SOLEDAD",
            "SLD - R15A" => "SOLEDAD",
            "SLD - R12" => "SOLEDAD",
            "SLD - R28A" => "SOLEDAD",
            "SLD - R13" => "SOLEDAD",
            "SLD - R25" => "SOLEDAD",
            "SLD - R15B" => "SOLEDAD",
            "SLD - R15" => "SOLEDAD",
            "SLD - R34" => "SOLEDAD",
            "SLD - R42" => "SOLEDAD",
            "SLD - R30C" => "SOLEDAD",
            "TBR - R3" => "TUBARÁ",
            "TBR - R2" => "TUBARÁ",
            "TBR - R4" => "TUBARÁ",
            "TBR - R1" => "TUBARÁ",
            "TBR - R5" => "TUBARÁ",
            "USR - R1" => "USIACURÍ"
        ];

        // Obtener ciclo directamente desde el validatedData
        $ciclo = $validatedData['ciclo'];

        // Asignar municipio según el ciclo
        $data->municipio = $cicloMunicipios[$ciclo] ?? null;
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
        $validColumns = ['operario', 'nombres', 'direccion', 'telefono', 'correo', 'orden'];
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

    public function exportDataComplete(Request $request)
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
        return Excel::download(new DataExportComplete($ciclo), $nombreArchivo);
    }
}
