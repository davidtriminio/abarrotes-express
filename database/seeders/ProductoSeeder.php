<?php

namespace Database\Seeders;

use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Marca;
use Database\Seeders\Concerns\GeneratesPlaceholderImages;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductoSeeder extends Seeder
{
    use GeneratesPlaceholderImages;

    public function run(): void
    {
        $productos = [
            // Calzado
            ['marca' => 'Nike', 'categoria' => 'Calzado', 'nombre' => 'Tenis Nike Air Max', 'precio' => 120.00, 'cantidad' => 50],
            ['marca' => 'Adidas', 'categoria' => 'Calzado', 'nombre' => 'Tenis Adidas Ultraboost', 'precio' => 140.00, 'cantidad' => 45],
            ['marca' => 'Vans', 'categoria' => 'Calzado', 'nombre' => 'Tenis Vans Old Skool', 'precio' => 65.00, 'cantidad' => 100],

            // Pantalones
            ['marca' => 'Levi\'s', 'categoria' => 'Pantalones', 'nombre' => 'Jeans Levi\'s 501 Original', 'precio' => 80.00, 'cantidad' => 120],
            ['marca' => 'Zara', 'categoria' => 'Pantalones', 'nombre' => 'Pantalón de Vestir Slim Fit', 'precio' => 55.00, 'cantidad' => 60],

            // Camisetas y Blusas
            ['marca' => 'Tommy Hilfiger', 'categoria' => 'Camisetas y Blusas', 'nombre' => 'Polo Clásica', 'precio' => 45.00, 'cantidad' => 85],
            ['marca' => 'H&M', 'categoria' => 'Camisetas y Blusas', 'nombre' => 'Camiseta Básica de Algodón', 'precio' => 15.00, 'cantidad' => 200],

            // Chaquetas y Abrigos
            ['marca' => 'Levi\'s', 'categoria' => 'Chaquetas y Abrigos', 'nombre' => 'Chaqueta de Mezclilla Trucker', 'precio' => 95.00, 'cantidad' => 40],
            ['marca' => 'Zara', 'categoria' => 'Chaquetas y Abrigos', 'nombre' => 'Abrigo de Lana', 'precio' => 110.00, 'cantidad' => 30],

            // Ropa Deportiva
            ['marca' => 'Under Armour', 'categoria' => 'Ropa Deportiva', 'nombre' => 'Camiseta de Compresión', 'precio' => 35.00, 'cantidad' => 70],
            ['marca' => 'Puma', 'categoria' => 'Ropa Deportiva', 'nombre' => 'Pants Deportivos', 'precio' => 40.00, 'cantidad' => 65],

            // Ropa Interior
            ['marca' => 'Calvin Klein', 'categoria' => 'Ropa Interior', 'nombre' => 'Pack de 3 Boxers', 'precio' => 30.00, 'cantidad' => 150],

            // Vestidos
            ['marca' => 'H&M', 'categoria' => 'Vestidos', 'nombre' => 'Vestido Floral de Verano', 'precio' => 25.00, 'cantidad' => 90],

            // Accesorios
            ['marca' => 'Nike', 'categoria' => 'Accesorios', 'nombre' => 'Gorra Deportiva', 'precio' => 20.00, 'cantidad' => 110],
        ];

        foreach ($productos as $prod) {
            $categoria = Categoria::where('nombre', $prod['categoria'])->first();
            $marca = Marca::where('nombre', $prod['marca'])->first();

            if ($categoria && $marca) {
                $imagenesGeneradas = [];
                $urlsIndividuales = [];
                $slug = Str::slug($prod['nombre']);

                // Generamos 5 imágenes locales únicas para este producto (sin llamadas externas)
                for ($i = 1; $i <= 5; $i++) {
                    $path = $this->generatePlaceholderImage('productos', "{$slug}-{$i}", "{$prod['nombre']} #{$i}");
                    $urlsIndividuales["imagen{$i}"] = $path;
                    $imagenesGeneradas[] = $path;
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
                        // El modelo castea 'imagenes' a array y se encarga de codificarlo a JSON al guardar
                        'imagenes' => $imagenesGeneradas,
                        'fecha_expiracion' => now()->addYears(2)->toDateString(),
                    ], $urlsIndividuales)
                );
            }
        }
    }
}
