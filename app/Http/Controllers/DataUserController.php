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
    public function asignadospendientes(Request $request)
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

        return view('Data.DataUser.pendientes', compact('data'));
    }

public function index(Request $request)
{
    $userId = Auth::id();
    $search = $request->input('search');

    $data = Data::where('id_user', $userId)
                ->when($search, function($query, $search) {
                    $query->where(function($q) use ($search) {
                        $q->where('nombres', 'like', "%{$search}%")
                          ->orWhere('ciclo', 'like', "%{$search}%")
                          ->orWhere('direccion', 'like', "%{$search}%");
                    });
                })
                ->orderBy('created_at', 'desc')
                ->paginate(1)
                ->appends($request->only('search'));

    return view('Data.DataUser.index', compact('data', 'search'));
}

}
