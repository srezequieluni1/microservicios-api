<?php
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\ApiTestController;

Route::get('/ping', fn() => response()->json(['status' => 'ok']));

// Rutas de ejemplo para probar el cliente API
Route::prefix('test')->group(function () {
    Route::get('/get-example', [ApiTestController::class, 'getExample']);
    Route::post('/post-example', [ApiTestController::class, 'postExample']);
    Route::put('/put-example/{id?}', [ApiTestController::class, 'putExample']);
    Route::delete('/delete-example/{id?}', [ApiTestController::class, 'deleteExample']);
    Route::patch('/patch-example/{id?}', [ApiTestController::class, 'patchExample']);
    Route::get('/status', [ApiTestController::class, 'statusExample']);
    Route::get('/delay', [ApiTestController::class, 'delayExample']);
    Route::get('/users', [ApiTestController::class, 'usersExample']);
    Route::get('/headers', [ApiTestController::class, 'headersExample']);
    Route::get('/protected', [ApiTestController::class, 'protectedExample']);
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Ruta para verificar email (no requiere autenticación)
Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])
    ->middleware('signed')
    ->name('verification.verify');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // Reenviar email de verificación
    Route::post('/email/resend', [AuthController::class, 'resendVerificationEmail']);
});
