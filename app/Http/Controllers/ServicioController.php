<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use Exception;
use Illuminate\Http\Request;

class ServicioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $servicios = Servicio::all();

        return view('Servicios.index', compact('servicios'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('Servicios.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255|unique:servicios,nombre',
            'precio' => 'required|numeric|regex:/^\d{1,9}(\.\d{1,2})?$/',
            'descripcion' => 'required|string|max:1000',
        ],[
            'nombre.required' => 'El nombre del servicio es obligatorio.',
            'nombre.string' => 'El nombre del servicio debe ser una cadena de texto.',
            'nombre.max' => 'El nombre del servicio no debe superar los 255 caracteres.',
            'nombre.unique' => 'Ya existe un servicio con ese nombre.',
        
            'precio.required' => 'El precio del servicio es obligatorio.',
            'precio.numeric' => 'El precio debe ser un número válido.',
            'precio.regex' => 'El precio debe tener como máximo 9 dígitos enteros y 2 decimales.',

            'descripcion.required' => 'La descripción es obligatoria.',
            'descripcion.string' => 'La descripción debe ser un texto.',
            'descripcion.max' => 'La descripción no debe exceder los 1000 caracteres.',
        ]);
        
        try{
            Servicio::create([
                'nombre' => $validatedData['nombre'],
                'precio' => $validatedData['precio'],
                'descripcion' => $validatedData['descripcion'],
            ]);
            
        }catch(Exception $e){
            return redirect()->back()->with('error','No se pudo crear el servicio: '.$e->getMessage());
        }

        return redirect()->route('servicio.index')->with('success','Servicio guardado correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try{
            $servicio = Servicio::find($id);
        }catch(Exception $e){
            return redirect()->back()->with('error','Error al editar servicio: '. $e->getMessage());
        }

        return view('Servicios.edit',compact('servicio'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $servicio = Servicio::findOrFail($id);

        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255|unique:servicios,nombre,'. $servicio->id,
            'precio' => 'required|numeric|regex:/^\d{1,9}(\.\d{1,2})?$/',
            'descripcion' => 'required|string|max:1000',
        ],[
            'nombre.required' => 'El nombre del servicio es obligatorio.',
            'nombre.string' => 'El nombre del servicio debe ser una cadena de texto.',
            'nombre.max' => 'El nombre del servicio no debe superar los 255 caracteres.',
            'nombre.unique' => 'Ya existe un servicio con ese nombre.',
        
            'precio.required' => 'El precio del servicio es obligatorio.',
            'precio.numeric' => 'El precio debe ser un número válido.',
            'precio.regex' => 'El precio debe tener como máximo 9 dígitos enteros y 2 decimales.',

            'descripcion.required' => 'La descripción es obligatoria.',
            'descripcion.string' => 'La descripción debe ser un texto.',
            'descripcion.max' => 'La descripción no debe exceder los 1000 caracteres.',
        ]);

        $servicio->nombre = $validatedData['nombre'];
        $servicio->precio = $validatedData['precio'];
        $servicio->descripcion = $validatedData['descripcion'];
        $servicio->save();

        return redirect()->route('servicio.index')->with('success','Servicio actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            $servicio = Servicio::find($id);
            
            $servicio->delete();

        } catch(Exception $e){
            return redirect()->back()->with('error','No se pudo eliminar el servicio: '. $e->getMessage());
        }

        return redirect()->back()->with('success','Servicio eliminado');
    }
}
