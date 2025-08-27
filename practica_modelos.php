<?php

require_once 'vendor/autoload.php';

// Configurar la aplicación Laravel para usar en consola
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Category;
use App\Models\Product;
use App\Models\Customer;

echo "=== PRÁCTICA DE MODELOS ELOQUENT ===\n\n";

/*
El equivalente en SQL sería:
INSERT INTO categories (name, slug, description, color, is_active, created_at, updated_at)
VALUES ('Electrónica', 'electronica', 'Dispositivos electrónicos y gadgets', '#FF5733', true, NOW(), NOW());
*/

Category::firstOrCreate([
    'name' => 'Electrónica',
    'slug' => 'electronica',
    'description' => 'Dispositivos electrónicos y gadgets',
    'color' => '#FF5733',
    'is_active' => true,
]);

Category::firstOrCreate([
    'name' => 'Ropa',
    'slug' => 'ropa',
    'description' => 'Prendas de vestir para todas las edades',
    'color' => '#33FF57',
    'is_active' => false,
]);

Category::firstOrCreate([
    'name' => 'Hogar',
    'slug' => 'hogar',
    'description' => 'Artículos para el hogar y decoración',
    'color' => '#3357FF',
    'is_active' => false,
]);

// desactivar la categoría 2
$cat1 = Category::where('slug', 'hogar')->first();
if ($cat1) {
    $cat1->is_active = false;
    $cat1->save();
}

/*
El equivalente en SQL del fragmento anterior sería:
UPDATE categories SET is_active = false WHERE id = 2;
*/

/*
El equivalente en SQL sería:
SELECT * FROM categories;
*/
$categorias = Category::all();
foreach ($categorias as $categoria) {
    echo "{$categoria->id} {$categoria->name} ({$categoria->description}) " .
        ($categoria->is_active ? 'Activa' : 'No activa') . "\n";
}
