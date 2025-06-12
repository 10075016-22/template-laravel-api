<?php

namespace App\Http\Controllers;

use App\Interface\ResponseClass;
use App\Models\Module;
use Illuminate\Http\Request;

class ModuleController extends Controller
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
            $modules = Module::get();
            return $this->response->success($modules);
        } catch (\Throwable $th) {
            return $this->response->error('An error has occurred');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $module = Module::create($request->all());
            return $this->response->success($module);
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
            $modules = Module::whereId($id)->get();
            return $this->response->success($modules);
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
            $module = Module::whereId($id)->update($request->all());
            return $this->response->success($module);
        } catch (\Throwable $th) {
            return $this->response->error('An error has occurred');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Module $module)
    {
        try {
            $module->delete();
            if(!$module) {
                return $this->response->notFound('Module not found');
            }
            return $this->response->success([]);
        } catch (\Throwable $th) {
            return $this->response->error('An error has occurred');
        }
    }
}
