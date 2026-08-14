<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promocion extends Model
{
    use HasFactory;

    protected $table='promociones';

    protected $fillable = ['nombre', 'descripcion', 'descuento', 'fecha_inicio', 'fecha_fin', 'activa'];

    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'producto_promocion');
    }
}
