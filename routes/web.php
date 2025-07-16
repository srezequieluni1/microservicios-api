<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocumentationController;

Route::get('/', fn() => view('welcome'));

// Ruta para previsualizar emails (solo para desarrollo)
if (app()->environment('local')) {
    Route::get('/email-preview/reset-password', function () {
        $user = App\Models\User::first() ?? App\Models\User::factory()->make([
            'name' => 'Usuario Demo',
            'email' => 'demo@ejemplo.com'
        ]);

        $notification = new App\Notifications\ResetPasswordNotification('demo-token-123456');

        return $notification->toMail($user);
    })->name('email.preview.reset');

    Route::get('/email-preview/verify-email', function () {
        $user = App\Models\User::first() ?? App\Models\User::factory()->make([
            'name' => 'Usuario Demo',
            'email' => 'demo@ejemplo.com'
        ]);

        $notification = new App\Notifications\CustomVerifyEmailNotification();

        return $notification->toMail($user);
    })->name('email.preview.verify');
}

// Ruta para el cliente API
Route::get('/api-client', fn() => view('api-client'))->name('api-client');

// Rutas para documentación
Route::get('/docs/api-client', [DocumentationController::class, 'apiClientDocs'])->name('docs.api-client');
Route::get('/docs/css-structure', [DocumentationController::class, 'cssStructureDocs'])->name('docs.css-structure');
