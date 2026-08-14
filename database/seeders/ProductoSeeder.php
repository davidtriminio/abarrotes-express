<?php

namespace Database\Seeders;

use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Marca;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductoSeeder extends Seeder
{
    public function run(): void
    {
        $productos = [
            // Calzado
            ['marca' => 'Nike', 'categoria' => 'Calzado', 'nombre' => 'Tenis Nike Air Max', 'precio' => 120.00, 'cantidad' => 50, 'keyword' => 'sneakers,shoes'],
            ['marca' => 'Adidas', 'categoria' => 'Calzado', 'nombre' => 'Tenis Adidas Ultraboost', 'precio' => 140.00, 'cantidad' => 45, 'keyword' => 'sneakers,adidas'],
            ['marca' => 'Vans', 'categoria' => 'Calzado', 'nombre' => 'Tenis Vans Old Skool', 'precio' => 65.00, 'cantidad' => 100, 'keyword' => 'skateshoes,vans'],

            // Pantalones
            ['marca' => 'Levi\'s', 'categoria' => 'Pantalones', 'nombre' => 'Jeans Levi\'s 501 Original', 'precio' => 80.00, 'cantidad' => 120, 'keyword' => 'jeans,denim'],
            ['marca' => 'Zara', 'categoria' => 'Pantalones', 'nombre' => 'Pantalón de Vestir Slim Fit', 'precio' => 55.00, 'cantidad' => 60, 'keyword' => 'trousers,pants'],

            // Camisetas y Blusas
            ['marca' => 'Tommy Hilfiger', 'categoria' => 'Camisetas y Blusas', 'nombre' => 'Polo Clásica', 'precio' => 45.00, 'cantidad' => 85, 'keyword' => 'poloshirt,shirt'],
            ['marca' => 'H&M', 'categoria' => 'Camisetas y Blusas', 'nombre' => 'Camiseta Básica de Algodón', 'precio' => 15.00, 'cantidad' => 200, 'keyword' => 'tshirt,cotton'],

            // Chaquetas y Abrigos
            ['marca' => 'Levi\'s', 'categoria' => 'Chaquetas y Abrigos', 'nombre' => 'Chaqueta de Mezclilla Trucker', 'precio' => 95.00, 'cantidad' => 40, 'keyword' => 'jacket,denim'],
            ['marca' => 'Zara', 'categoria' => 'Chaquetas y Abrigos', 'nombre' => 'Abrigo de Lana', 'precio' => 110.00, 'cantidad' => 30, 'keyword' => 'coat,winter'],

            // Ropa Deportiva
            ['marca' => 'Under Armour', 'categoria' => 'Ropa Deportiva', 'nombre' => 'Camiseta de Compresión', 'precio' => 35.00, 'cantidad' => 70, 'keyword' => 'gym,sportswear'],
            ['marca' => 'Puma', 'categoria' => 'Ropa Deportiva', 'nombre' => 'Pants Deportivos', 'precio' => 40.00, 'cantidad' => 65, 'keyword' => 'sweatpants,sports'],

            // Ropa Interior
            ['marca' => 'Calvin Klein', 'categoria' => 'Ropa Interior', 'nombre' => 'Pack de 3 Boxers', 'precio' => 30.00, 'cantidad' => 150, 'keyword' => 'underwear,boxers'],

            // Vestidos
            ['marca' => 'H&M', 'categoria' => 'Vestidos', 'nombre' => 'Vestido Floral de Verano', 'precio' => 25.00, 'cantidad' => 90, 'keyword' => 'dress,floral'],

            // Accesorios
            ['marca' => 'Nike', 'categoria' => 'Accesorios', 'nombre' => 'Gorra Deportiva', 'precio' => 20.00, 'cantidad' => 110, 'keyword' => 'cap,hat'],
        ];

        // Usamos un contador global para el 'lock' de LoremFlickr, garantizando que NINGUNA imagen se repita.
        $lockId = 1;

        foreach ($productos as $prod) {
            $categoria = Categoria::where('nombre', $prod['categoria'])->first();
            $marca = Marca::where('nombre', $prod['marca'])->first();

            if ($categoria && $marca) {

                $imagenesGeneradas = [];
                $urlsIndividuales = [];
                $keyword = $prod['keyword'];

                // Generamos 5 imágenes únicas para este producto usando LoremFlickr
                for ($i = 1; $i <= 5; $i++) {
                    $url = "https://loremflickr.com/320/240/{$keyword}?lock={$lockId}";
                    $urlsIndividuales["imagen{$i}"] = $url;
                    $imagenesGeneradas[] = $url;

                    $lockId++; // Incrementamos el lock para la siguiente imagen
                }

                Producto::updateOrCreate(
                    ['nombre' => $prod['nombre']],
                    array_merge([
                        'marca_id' => $marca->id,
                        'categoria_id' => $categoria->id,
                        'descripcion' => 'Excelente prenda de moda - ' . $prod['nombre'],
                        'precio' => $prod['precio'],
                        'cantidad_disponible' => $prod['cantidad'],
                        'disponible' => true,
                        'en_oferta' => rand(0, 1) === 1,
                        'porcentaje_oferta' => rand(0, 1) === 1 ? rand(5, 30) : 0,
                        // Guardamos el arreglo completo en la columna 'imagenes' como JSON
                        'imagenes' => json_encode($imagenesGeneradas),
                        // Mantenemos una fecha de expiración simbólica para cumplir con la migración
                        'fecha_expiracion' => now()->addYears(2)->toDateString(),
                    ], $urlsIndividuales) // Mezclamos el array que contiene 'imagen1' ... 'imagen5'
                );
            }
        }
    }
}
