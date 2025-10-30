<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Data;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserController extends Controller
{
    use RegistersUsers;


    public function index()
    {
        $users = User::all();
        return view('Users.index', compact('users'));
    }

    public function create()
    {
        return view('Users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'rol' => 'required|string',
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'name.string' => 'El nombre debe ser una cadena de texto.',
            'name.max' => 'El nombre no puede superar los 255 caracteres.',

            'email.required' => 'El correo electrónico es obligatorio.',
            'email.string' => 'El correo electrónico debe ser una cadena de texto.',
            'email.email' => 'Debe ingresar un correo electrónico válido.',
            'email.max' => 'El correo electrónico no puede superar los 255 caracteres.',
            'email.unique' => 'Este correo electrónico ya está registrado.',

            'password.required' => 'La contraseña es obligatoria.',
            'password.string' => 'La contraseña debe ser una cadena de texto.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',

            'rol.required' => 'El rol es obligatorio.',
            'rol.string' => 'El rol debe ser una cadena de texto.',
        ]);

        if ($request->rol == 'user') {
            $request->validate([
                'firma' => 'required|file|image|max:5048',
            ]);
        }

        $user = new User();
        $user->name = $request['name'];
        $user->email = $request['email'];
        $user->password = Hash::make($request['password']);
        $user->rol = $request['rol'];

        // Si el rol es técnico, guardar su firma en carpeta personalizada
        if ($request->rol == 'user' && $request->hasFile('firma')) {

            $nombreTecnico = Str::slug($request->name, '_'); // ejemplo: juan_perez
            $mesActual = date('F');

            // Crear ruta personalizada para el técnico
            $firmaFile = $request->file('firma');
            $firmaFileName = uniqid() . '.' . $firmaFile->getClientOriginalExtension();

            $firmaPath = "Apptualiza/{$mesActual}/{$nombreTecnico}/firma del tecnico/{$firmaFileName}";

            // Guardar el archivo en storage/app/public/...
            Storage::disk('public')->put($firmaPath, file_get_contents($firmaFile));

            // Guardar la ruta relativa en la BD
            $user->firma_path = $firmaPath;
        }

        $user->save();

        return redirect()->route('users.index')->with('success', 'Usuario creado con éxito.');
    }

    public function edit(User $user)
    {
        return view('Users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'rol' => 'required|string',
        ]);

        // Si el rol es usuario, la firma es obligatoria si no tiene una y validar si se sube una nueva
        if ($request->rol == 'user') {
            if (!$user->firma_path && !$request->hasFile('firma')) {
                $request->validate([
                    'firma' => 'required|file|image|max:5048',
                ]);
            } elseif ($request->hasFile('firma')) {
                $request->validate([
                    'firma' => 'file|image|max:5048',
                ]);
            }
        }
        // Manejar la firma si es usuario
        if ($request->rol == 'user' && $request->hasFile('firma')) {
            // Eliminar la firma anterior si existe
            if ($user->firma_path && Storage::disk('public')->exists($user->firma_path)) {
                Storage::disk('public')->delete($user->firma_path);
            }

            // Guardar la nueva firma
            $firmaPath = $request->file('firma')->store('firmas', 'public');
            $user->firma_path = $firmaPath;
        }
        // Si el rol cambia de usuario a admin, eliminar la firma
        elseif ($request->rol == 'admin' && $user->firma_path) {
            if (Storage::disk('public')->exists($user->firma_path)) {
                Storage::disk('public')->delete($user->firma_path);
            }
            $user->firma_path = null;
        }

        $user->name = $request['name'];

        $user->email = $request['email'];

        $user->password = $request->filled('password') ? bcrypt($request->password) : $user->password;

        $user->rol = $request->rol;

        $user->save();

        return redirect()->route('users.index')->with('success', 'Usuario actualizado con éxito.');
    }

    public function destroy(User $user)
    {
        if ($user->rol === 'admin') {
            return redirect()->route('users.index')->with('error', 'No puede eliminar a un administrador.');
        }

        $data = Data::all();
        foreach ($data as $dataItem) {
            if ($dataItem->id_user == $user->id && $dataItem->estado == 0) {
                return redirect()->route('users.index')->with('error', 'No se puede eliminar un usuario que tiene asignaciones.');
            }
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'Usuario eliminado con éxito.');
    }
}
