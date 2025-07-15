<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocumentationController;

Route::get('/', fn() => view('welcome'));

// Ruta para el cliente API
Route::get('/api-client', fn() => view('api-client'))->name('api-client');

// Rutas para documentación
Route::get('/docs/api-client', [DocumentationController::class, 'apiClientDocs'])->name('docs.api-client');
Route::get('/docs/css-structure', [DocumentationController::class, 'cssStructureDocs'])->name('docs.css-structure');
