<?php

namespace App\Http\Controllers;
// en app/Http/Controllers/DataController.php (o DataUserController.php)
use App\Models\Data;
use App\Models\User;
use Illuminate\Http\Request;

class DataUserController extends Controller
{
public function completados(Request $request)
{
    $sortBy = $request->get('sortBy', 'id');
    $direction = $request->get('direction', 'asc');

    $validColumns = ['operario','orden','nombres','ciclo','nombre_auditor','direccion','id'];
    if (!in_array($sortBy, $validColumns)) $sortBy = 'id';
    if (!in_array($direction, ['asc','desc'])) $direction = 'asc';

    // base query: solo completados (ajusta 'estado' y valor según tu DB)
    $query = Data::where('estado', 1);

    // filtros (si aplican)
    if ($request->filled('buscador-operario')) {
        $userIds = User::where('name', 'like', '%'.$request->input('buscador-operario').'%')->pluck('id');
        $query->whereIn('id_user', $userIds);
    }
    if ($request->filled('buscador-orden')) {
        $query->where('orden', 'like', '%'.$request->input('buscador-orden').'%');
    }
    if ($request->filled('buscador-nombre')) {
        $query->where('nombres', 'like', '%'.$request->input('buscador-nombre').'%');
    }
    if ($request->filled('buscador-direccion')) {
        $query->where('direccion', 'like', '%'.$request->input('buscador-direccion').'%');
    }

    $datas = $query->orderBy($sortBy, $direction)
                   ->paginate(100)
                   ->appends($request->except('page'));

    $totalResultados = $query->count();

    // Asegúrate de que la ruta de la vista corresponde:
    return view('Data.datauser.completados', compact('datas','sortBy','direction','totalResultados'));
}

public function index()
{
    $data = \App\Models\Data::all();
    return view('Data.DataUser.index', compact('data'));
}

}
