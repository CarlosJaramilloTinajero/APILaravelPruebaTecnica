<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\HelpersController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Inyeccion de dependencia de la clase HelpersController
    public function __construct(protected HelpersController $helpersController) {}

    public function login(LoginRequest $request)
    {
        // Verificamos que exista el usuario con la credenciales email y password recibidas por el request 
        if (!Auth::attempt($request->only(['email', 'password']))) {
            return $this->helpersController->responseFailApi('Las credenciales no coinciden');
        }

        // Obtenemos la instancia del modelo User
        $user = User::where('email', $request->email)->first();

        // Creamos el token nuevo
        $token = $user->createToken('auth_token')->plainTextToken;

        // Retornamos el token generado del usuario
        return $this->helpersController->responseSuccessApi([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user_name' => $user->name,
        ]);
    }

    function register(RegisterRequest $request)
    {
        // Creamos el usuario con la informacion recibida por el request
        if (!$user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ])) {

            // Si no se creo el usuario con exito, respondemos con un mensaje de error
            return $this->helpersController->responseFailApi('Error al crear el usuario');
        }

        // Generamos el token
        $token = $user->createToken('auth_token')->plainTextToken;

        // Retornamos con una respuesta exitosa con el token generado y nombre de usuario.
        return $this->helpersController->responseSuccessApi([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user_name' => $user->name
        ]);
    }
}
