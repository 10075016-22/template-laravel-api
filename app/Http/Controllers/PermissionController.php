<?php

namespace App\Http\Controllers;

use App\Models\HeadersTable;
use App\Models\Table;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $permissions = Permission::get();
            return response()->json($permissions);
        } catch (\Throwable $th) {
            return response()->json([ 'message' => 'An error has occurred', 'error' => $th->getMessage() ], 500);
        }
    }

    public function gridIndex(Request $request) 
    {
        try {
            $params = $request->query();
            $table = Table::whereId($params['nIdTable'])->first() ?? (Object) [];
            $headers = HeadersTable::whereTableId($params['nIdTable'])->orderBy('order')->get();
            $data = Permission::get();

            return response()->json([
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
            $permissions = Permission::create($request->all());
            return response()->json([
                'message' => 'The record has been successfully created',
                'data' => $permissions
            ]);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'An error has occurred', 'error' => $th->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $permissions = Permission::whereId($id)->get();
            return response()->json($permissions);
        } catch (\Throwable $th) {
            return response()->json([ 'message' => 'An error has occurred', 'error' => $th->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $permission = Permission::whereId($id)->update($request->all());
            return response()->json([
                'message' => 'The record has been successfully updated',
                'data' => $permission
            ]);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'An error has occurred', 'error' => $th->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $permission = Permission::find($id);
            $permission->delete();
            return response()->json([ 'message' => 'The record has been successfully deleted' ]);
        } catch (\Throwable $th) {
            return response()->json([ 'message' => 'An error has occurred', 'error' => $th->getMessage()], 500);
        }
    }
}
