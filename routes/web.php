<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocumentationController;

Route::get('/', fn() => view('welcome'));

// Ruta para el cliente API
Route::get('/api-client', fn() => view('api-client'))->name('api-client');

// Ruta para la página de prueba del cliente API
Route::get('/api-client-test', fn() => view('api-client-test'))->name('api-client-test');

// Rutas para documentación
Route::get('/docs/api-client', [DocumentationController::class, 'apiClientDocs'])->name('docs.api-client');
Route::get('/docs/css-structure', [DocumentationController::class, 'cssStructureDocs'])->name('docs.css-structure');
Route::get('/docs/test-page', [DocumentationController::class, 'testPageDocs'])->name('docs.test-page');
