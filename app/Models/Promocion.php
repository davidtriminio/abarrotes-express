<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promocion extends Model
{
    use HasFactory;

    protected $table='promociones';

    protected $fillable = [ 
    'producto_id',
    'estado',
    'fecha_inicio',
    'fecha_expiracion',
    'promocion',
    ];

    public function producto(){
       return  $this->belongsTo(Producto::class, 'producto_id');
    }
}
