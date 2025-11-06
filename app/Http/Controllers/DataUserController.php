<?php

namespace App\Http\Controllers;

use App\Models\Data;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DataUserController extends Controller
{
    public function completados(Request $request)
    {
        $sortBy = $request->get('sortBy', 'id');
        $direction = $request->get('direction', 'asc');

        $validColumns = ['operario', 'orden', 'nombres', 'ciclo', 'nombre_auditor', 'direccion', 'id'];
        if (!in_array($sortBy, $validColumns)) $sortBy = 'id';
        if (!in_array($direction, ['asc', 'desc'])) $direction = 'asc';

        $userId = Auth::id(); // ← ID del usuario autenticado

        // Solo completados del usuario actual
        $query = Data::where('estado', 1)
                     ->where('id_user', $userId);

        // Filtros de búsqueda
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

        return view('Data.DataUser.completados', compact('datas', 'sortBy', 'direction', 'totalResultados'));
    }

    public function index()
    {
        $userId = Auth::id(); // ← Solo órdenes del usuario autenticado

        // Solo pendientes
        $data = Data::where('estado', 'Pendiente')
                    ->where('id_user', $userId)
                    ->get();

        return view('Data.DataUser.index', compact('data'));
    }
    
}
