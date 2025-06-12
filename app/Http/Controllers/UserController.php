<?php

namespace App\Http\Controllers;

use App\Interface\ResponseClass;
use App\Models\HeadersTable;
use App\Models\Table;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    protected $response;
    public function __construct(ResponseClass $response)
    {
        $this->response = $response;
    }
    
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');
        try {
            if (! $token = JWTAuth::attempt($credentials)) return response()->json(['error' => 'Incorrect credentials', 'status' => 'error'], 400); // Credenciales incorrectas
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not create token.', 'status' => 'error'], 500);
        }

        $oUser = Auth::user();

        try {
            if($oUser->status !== 1) return response()->json(['error' => 'Inactive user', 'status' => 'error'], 400);
            if(!is_null($oUser->deleted_at)) return response()->json(['error' => 'Unauthorized user', 'status' => 'error'], 400);
            // Obtener roles del usuario
            $roles = $oUser->getRoleNames(); // Esto devolverá una colección de nombres de roles

            // Obtener permisos del usuario
            $permissions = $oUser->getAllPermissions(); // Esto devolverá una colección de permisos
            // $activity = [
            //     'method' => 'POST',
            //     'action' => 'Login',
            //     'description' => "El usuario inicia sesión: {$oUser->name}"
            // ];
        } catch (JWTException $th) {
            return response()->json(['error' => 'Could not create token'], 500);
        }
        $oUser->access_token = $token;
        // $oUser->aParametros = ParametroController::getAll();

        return $this->response->success($oUser);
    }

    public function getAuthenticatedUser()
    {
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) return response()->json(['User not found'], 404);
        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }
        return response()->json(compact('user'));
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index() 
    {
        try {
            $users = User::withRole()->get();
            return $this->response->success($users);
        } catch (\Throwable $th) {
            return $this->response->error('An error has occurred');
        }
    }

    public function gridIndex(Request $request) 
    {
        try {
            $params = $request->query();
            $table = Table::whereId($params['nIdTable'])->first() ?? (Object) [];
            $headers = HeadersTable::whereTableId($params['nIdTable'])->orderBy('order')->get();
            $users = User::withRole()->withTrashed()->get();

            return $this->response->success([
                'data'  => $users,
                'tabla' => $table,
                'headers' => $headers
            ]);
        } catch (\Throwable $th) {
            return response()->json([]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Usuario  $usuario
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $user = User::withRole()->find($id);
            return $this->response->success($user);
        } catch (\Throwable $th) {
            return $this->response->error('An error has occurred');
        }
    }

    /**
     * Request
     *   name
     *   fullname
     *   email
     *   password (in MD5)
     *   role_id
     */
    public function store(Request $request)
    {
        try {
            $user = [
                'name'      => $request->name,
                'fullname'  => $request->fullname,
                'email'     => $request->email,
                'password'  => Hash::make($request->password)
            ];

            $user = User::create($user);

            $role = Role::find($request->role_id);
            $user->assignRole($role);
            return $this->response->success($user);

        } catch (\Throwable $th) {
            return $this->response->error('An error has occurred');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Usuario  $usuario
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $user = User::find($id);

            if($user) {
                $password = isset($request->password) ? Hash::make($request->password) : null;
                $aUpdate = [ // array for update
                    'name'      => $request->name,
                    'fullname'  => $request->fullname,
                    'email'     => $request->email
                ];
                if(!is_null($password)) {
                    $aUpdate['password'] = $password;
                }

                $user->update($aUpdate);


                 // Elimina todos los roles actuales del usuario
                DB::table('model_has_roles')
                    ->where('model_id', $user->id)
                    ->where('model_type', 'App\Models\User') // Ajusta esto según el namespace de tu modelo de usuario si es diferente
                    ->delete();

                $role = Role::find($request->role_id);
                $user->assignRole($role);
            }
            return $this->response->success($user);
        } catch (\Throwable $th) {
            return $this->response->error('An error has occurred');
        }
    }


    // metodo para restaurar un usuario eliminado
    public function restoreUser($id) {
        try {
            $usuario = User::withTrashed()->find($id);
            if($usuario) {
                $usuario->restore();
                return $this->response->success($usuario);
            } else {
                return $this->response->notFound("User not found");
            }
        } catch (\Throwable $th) {
            return $this->response->error('An error has occurred');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Usuario  $usuario
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $usuario = User::find($id);

            if($usuario) $usuario->delete();

            return $this->response->success([]);
        } catch (\Throwable $th) {
            return $this->response->error('An error has occurred');
        }
    }

    public function logout(Request $request) {
        try {
            $user = Auth::user();
            JWTAuth::parseToken()->invalidate(); // Invalida el token actualmente en uso
            return $this->response->success([]);
        } catch (\Exception $e) {
            return $this->response->error('Failed to logout');
        }
    }



}
