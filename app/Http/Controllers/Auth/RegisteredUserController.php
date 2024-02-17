<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $mensajes = [
            'required' => 'El campo :attribute es obligatorio.',
            'email' => 'El campo :attribute debe ser una dirección de correo válida.',
            'unique' => 'El valor ingresado para :attribute ya está en uso.',
            'before' => 'La fecha de nacimiento debe ser anterior a la fecha actual.',
            'fechaNacimiento.date' => 'El campo :attribute debe ser una fecha válida.',
            'password' => 'La contraseña debe tener al menos 8 caracteres y contener al menos una mayúscula.',
            'date_format' => 'El campo :attribute debe tener el formato de fecha correcto.',
        ];
        
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'password' => ['required', 'confirmed', 'string', 'min:8', 'regex:/[A-Z]/'],
            'fechaNacimiento' => ['required', 'date_format:Y-m-d', 'before:' . now()->format('Y-m-d'), 'after:' . now()->subYears(200)->format('Y-m-d')],
        ], $mensajes);
        
        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'fecha_nacimiento' => $request->fechaNacimiento,
            'pais_residencia' => $request->input('PaisResidencia')
        ]);

        event(new Registered($user));

        return redirect(RouteServiceProvider::HOME);
    }
}
