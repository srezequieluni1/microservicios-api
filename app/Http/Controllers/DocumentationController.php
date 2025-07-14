<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class DocumentationController extends Controller
{
    /**
     * Mostrar la documentación del API Client
     */
    public function apiClientDocs()
    {
        $readmePath = base_path('API_CLIENT_README.md');

        if (!File::exists($readmePath)) {
            return response()->view('documentation.not-found', [
                'title' => 'Documentación API Client',
                'file' => 'API_CLIENT_README.md'
            ], 404);
        }

        $content = File::get($readmePath);

        return view('documentation.markdown', [
            'title' => 'Documentación API Client',
            'content' => $content,
            'backUrl' => '/api-client-test'
        ]);
    }

    /**
     * Mostrar la documentación de la estructura CSS
     */
    public function cssStructureDocs()
    {
        $readmePath = base_path('CSS_STRUCTURE_README.md');

        if (!File::exists($readmePath)) {
            return response()->view('documentation.not-found', [
                'title' => 'Documentación Estructura CSS',
                'file' => 'CSS_STRUCTURE_README.md'
            ], 404);
        }

        $content = File::get($readmePath);

        return view('documentation.markdown', [
            'title' => 'Documentación Estructura CSS',
            'content' => $content,
            'backUrl' => '/api-client-test'
        ]);
    }    /**
     * Mostrar la documentación del Test Page
     */
    public function testPageDocs()
    {
        $readmePath = base_path('API_CLIENT_TEST_README.md');

        if (!File::exists($readmePath)) {
            return response()->view('documentation.not-found', [
                'title' => 'Documentación Test Page',
                'file' => 'API_CLIENT_TEST_README.md'
            ], 404);
        }

        $content = File::get($readmePath);

        return view('documentation.markdown', [
            'title' => 'Documentación Test Page',
            'content' => $content,
            'backUrl' => '/api-client-test'
        ]);
    }

    /**
     * Mostrar la documentación del Historial de Consultas
     */
    public function historyDocs()
    {
        $readmePath = base_path('API_CLIENT_HISTORY_README.md');

        if (!File::exists($readmePath)) {
            return response()->view('documentation.not-found', [
                'title' => 'Documentación Historial',
                'file' => 'API_CLIENT_HISTORY_README.md'
            ], 404);
        }

        $content = File::get($readmePath);

        return view('documentation.markdown', [
            'title' => 'Documentación Historial de Consultas',
            'content' => $content,
            'backUrl' => '/api-client'
        ]);
    }
}
