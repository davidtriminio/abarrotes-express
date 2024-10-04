<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Producto extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'marca_id',
        'categoria_id',
        'nombre',
        'imagenes',
        'imagen1',
        'imagen2',
        'imagen3',
        'imagen4',
        'imagen5',
        'descripcion',
        'precio',
        'disponible',
        'cantidad_disponible',
        'en_oferta',
        'porcentaje_oferta',
    ];

    protected $table = 'productos';

    #Convertimos las imagenes en arreglos;
    protected $casts = ['imagenes' => 'array'];


    public function categoria()
    {
        return $this->belongsTo(Categoria::class);

    }

    public function marca()
    {
        return $this->belongsTo(Marca::class);
    }

    public function imagenes()
    {
        return $this->hasMany(Imagen::class);
    }

    public function elementosOrden(): HasMany
    {
        return $this->hasMany(ElementoOrden::class, 'producto_id');
    }

    public function cupones()
    {
        return $this->hasMany(Cupon::class, 'producto_id');
    }

    public function favoritos()
    {
        return $this->hasMany(Favorito::class);
    }






    /*Función de almacenamiento en diferentes columnas*/
   /* protected static function booted()
    {
        static::saving(function ($model) {
            if (is_array($model->imagenes)) {
                // Convertir cada imagen a JSON para escaparlas
                $model->imagen1 = isset($model->imagenes[0]) ? json_encode($model->imagenes[0]) : null;
                $model->imagen2 = isset($model->imagenes[1]) ? json_encode($model->imagenes[1]) : null;
                $model->imagen3 = isset($model->imagenes[2]) ? json_encode($model->imagenes[2]) : null;
                $model->imagen4 = isset($model->imagenes[3]) ? json_encode($model->imagenes[3]) : null;
                $model->imagen5 = isset($model->imagenes[4]) ? json_encode($model->imagenes[4]) : null;
            } else {
                // Si no es un array, decodificar y luego convertir a JSON
                $imagenes = json_decode($model->imagenes, true);

                if (is_array($imagenes)) {
                    $model->imagen1 = isset($imagenes[0]) ? json_encode($imagenes[0]) : null;
                    $model->imagen2 = isset($imagenes[1]) ? json_encode($imagenes[1]) : null;
                    $model->imagen3 = isset($imagenes[2]) ? json_encode($imagenes[2]) : null;
                    $model->imagen4 = isset($imagenes[3]) ? json_encode($imagenes[3]) : null;
                    $model->imagen5 = isset($imagenes[4]) ? json_encode($imagenes[4]) : null;
                }
            }
        });
    }*/

}
