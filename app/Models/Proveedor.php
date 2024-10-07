<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Proveedor extends Model
{
    use HasFactory;
    
    protected $table = 'proveedores';

    protected $fillable = [
        'nombre',
        'id_producto',
        'contracto',
        'cantidad_producto',
        'pago',
        'estado',
    ];
   


    public function producto()
{
    return $this->belongsTo(Producto::class, 'id_producto');
}
    
}
