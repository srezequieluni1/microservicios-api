<?php
use App\Http\Controllers\Api\AuthController;

Route::get('/ping', fn() => response()->json(['status' => 'ok']));

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
