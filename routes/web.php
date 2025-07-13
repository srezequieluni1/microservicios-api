<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn() => view('welcome'));

// Ruta para el cliente API
Route::get('/api-client', fn() => view('api-client'))->name('api-client');

// Ruta para la página de prueba del cliente API
Route::get('/api-client-test', fn() => view('api-client-test'))->name('api-client-test');
