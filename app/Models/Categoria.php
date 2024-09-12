<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Categoria extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'id';
    protected $fillable = [
        'nombre',
        'imagen',
        'descripcion',
        'disponible'
    ];

    protected $table = 'categorias';



    use HasFactory;
    public function productos() {
        return $this->hasmany(Producto::class,'categoria_id');
     }

    public function cupones()
    {
        return $this->hasMany(Cupon::class, 'categoria_id');
    }
}
