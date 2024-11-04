<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'orden_id',
        'producto_id',
    ];

    public function productos(): HasMany
    {
        return $this->hasMany(Producto::class, 'producto_id');
    }

    public function ordenes()
    {
        return $this->hasMany(Orden::class, 'orden_id');
    }

    public function user()
    {
        return $this->hasMany(User::class, 'user_id');
    }
}
