<?php

namespace App\Models;

use Carbon\Carbon;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Cupon extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'codigo',
        'tipo_descuento',
        'descuento_porcentaje',
        'descuento_dinero',
        'fecha_inicio',
        'fecha_expiracion',
        'estado',
        'compra_minima',
        'compra_cantidad',
        'user_id',
        'producto_id',
        'categoria_id',
        'marca_id'
    ];

    protected $table = 'cupones';


    public function getEstadoAttribute($value)
    {

        if (Carbon::now()->greaterThan($this->fecha_expiracion)) {
            return false;
        }


        return $value;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    public function marca()
    {
        return $this->belongsTo(Marca::class, 'marca_id');
    }



    public function getDescuentoAttribute()
    {
        if ($this->tipo_descuento === 'porcentaje') {
            return number_format($this->descuento_porcentaje, 2) . '%';
        } elseif ($this->tipo_descuento === 'dinero') {
            return number_format($this->descuento_dinero, 2) . ' LPS';
        }
        return null;
    }

    public function setDescuentoPorcentajeAttribute($value)
    {
        $this->attributes['descuento_porcentaje'] = $value;
    }
}
