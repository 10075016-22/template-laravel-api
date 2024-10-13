<?php

use App\Http\Controllers\ModuleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\JwtMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/login', [UserController::class, 'authenticate']);

// Grouping usign jwt middleware
Route::middleware([JwtMiddleware::class])->group(function () {
    Route::post('/logout', [UserController::class, 'logout']);


    // services
    Route::group(['prefix' => 'v1'], function() {

        // modules
        Route::resource("/modules", ModuleController::class);

        // users
        Route::put("/users/restore/{id}", [UserController::class, "restoreUser"]);
        Route::resource("/users", UserController::class);

        // permissions
        Route::resource("/permissions", PermissionController::class);

        // roles
        Route::resource("/roles", RoleController::class);
    });

    // associated to tables
    Route::group(['prefix' => 'grid'], function() {
        Route::get("/users", [UserController::class, "gridIndex"]);
        Route::get("/roles", [RoleController::class, "gridIndex"]);
        Route::get("/permissions", [PermissionController::class, "gridIndex"]);
    });

});

Route::get('/test', function (Request $request) {
    return response()->json(['message' => 'Why so seriuos?']);
});