<?php

namespace App\Http\Controllers;

use App\Interface\ResponseClass;
use App\Models\HeadersTable;
use App\Models\Table;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    protected $response;
    public function __construct(ResponseClass $response)
    {
        $this->response = $response;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $roles = Role::get();
            return $this->response->success($roles);
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
            $data = Role::get();

            return $this->response->success([
                'data'  => $data,
                'tabla' => $table,
                'headers' => $headers
            ]);
        } catch (\Throwable $th) {
            return response()->json([]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $role = Role::create($request->all());
            return $this->response->success($role);
        } catch (\Throwable $th) {
            return $this->response->error('An error has occurred');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $roles = Role::whereId($id)->get();
            return $this->response->success($roles);
        } catch (\Throwable $th) {
            return $this->response->error('An error has occurred');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $role = Role::whereId($id)->update($request->all());
            return $this->response->success($role);
        } catch (\Throwable $th) {
            return $this->response->error('An error has occurred');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $role = Role::find($id);
            if (!$role) {
                return $this->response->error('The record does not exist');
            }
            $role->delete();
            return $this->response->success([]);
        } catch (\Throwable $th) {
            return $this->response->error('An error has occurred');
        }
    }
}
