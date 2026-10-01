<?php

namespace App\Http\Controllers;

use App\Models\Token;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    private function checkCredentials(array $data): ?User
    {
        // Buscar el usuario en la tabla
        $user = User::where('email', $data['email'])->first();

        // Comprobar si el usuario existe y si la contraseña coincide
        if (!$user || !Hash::check($data['password'], $user->password)) {
            return null;
        }

        // Retornar el usuario
        return $user;
    }

    private function checkToken (array $data)
    {
        $token = Token::where('token', $data['token'])->first();

        if (!$token) {
            return null;
        }

        return User::find($token->user_id);
    }

    public function get()
    {
        try {
            $users = User::orderByDesc('id')->paginate(10);

            $users->getCollection()->transform(function ($user) {
                return [
                    $user->username => collect($user)->except('username')
                ];
            });

            return response()->json([
                'success' => true,
                'users' => $users->getCollection()->values()
            ]);
        } catch (Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage()
            ], 401);
        }
    }

    public function create(Request $request)
    {
        try {
            // Recoger los datos
            $data = $request->validate([
                'username' => ['required', 'string', 'min:3', 'max:30'],
                'email' => ['required', 'email', 'unique:users,email', 'max:255'],
                'password' => ['required', 'string', 'min:8', 'max:50']
            ]);

            // Hasheamos la contraseña
            $data['password'] = Hash::make($data['password']);

            // Creamos el usuario
            $user = User::create($data);

            // Devolvemos el json final
            return response()->json($user, 201);
        } catch (Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage()
            ], 401);
        }
    }

    public function login(Request $request)
    {
        try {
            // Recoger los datos
            $data = $request->validate([
                'email' => ['required', 'email', 'max:255'],
                'password' => ['required', 'string', 'min:8', 'max:50']
            ]);

            // Comprobar las credenciales
            $user = $this->checkCredentials($data);

            // Retornar error si el usuario no existe
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Credenciales incorrectas.'
                ], 401);
            }

            // Buscamos un token ya existente
            $old_token = Token::where('user_id', $user->id)->first();

            // Borrarmos el token existente si existe
            if ($old_token) {
                $old_token->delete();
            }

            // Creamos el nuevo token
            $token = hash('sha256', Str::random(64));

            // Creamos nueva entrada en la tabla token
            Token::create([
                'user_id' => $user->id,
                'token' => $token
            ]);

            // Devolvemos el json final
            return response()->json([
                'success' => true,
                'message' => 'Has iniciado sesión correctamente.',
                $user->username => collect($user)
                    ->except('username')
                    ->put('token', $token)
            ], 201);
        } catch (Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage()
            ], 401);
        }
    }

    // Función updateusername que actualice el nombre pasando solo el username y el nuevo nombre
    public function update_username(Request $request)
    {
        try {
            // Recoger los datos
            $data = $request->validate([
                'username' => ['required', 'string', 'min:3', 'max:30'],
                'token' => ['required', 'string']
            ]);

            // Comprobar la coincidencia del token
            $user = $this->checkToken($data);

            // Devolver error si el token no coincide
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token inválido.'
                ], 401);
            }

            // Cambiar nombre de usuario
            $user->username = $data['username'];
            $user->save();

            // Devolver el json final
            return response()->json([
                'success' => true,
                'message' => 'Nombre de usuario cambiado correctamente.',
                $user->username => collect($user)->except('username')
            ], 201);
        } catch (Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage()
            ], 401);
        }
    }
}
