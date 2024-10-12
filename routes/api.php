<?php

use App\Http\Controllers\UserController;
use App\Http\Middleware\JwtMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/login', [UserController::class, 'authenticate']);

// Grouping usign jwt middleware
Route::middleware([JwtMiddleware::class])->group(function () {
    
    // services
    Route::group(['prefix' => 'v1'], function() {
        Route::put("/users/restore/{id}", [UserController::class, "restoreUser"]);
        Route::resource("/users", UserController::class);
    });

    // associated to tables
    Route::group(['prefix' => 'grid'], function() {
        Route::get("/users", [UserController::class, "gridIndex"]);
    });

});

Route::get('/test', function (Request $request) {
    return response()->json(['message' => 'Why so seriuos?']);
});