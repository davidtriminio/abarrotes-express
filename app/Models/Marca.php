<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Marca extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'marcas';
    protected $fillable = [
        'nombre',
        'imagen',
        'descripcion',
        'disponible'
    ];

    public function productos(): HasMany
    {
        return $this->hasMany(Producto::class, 'marca_id');
    }

    public function cupones()
    {
        return $this->hasMany(Cupon::class, 'marca_id');
    }


}
