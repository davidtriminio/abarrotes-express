<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use mysql_xdevapi\Table;

class QuejaSugerencia extends Model
{
    use HasFactory;

    protected $table = 'quejas_sugerencias';
    protected $fillable = [
        'titulo',
        'tipo',
        'descripcion',
    ];
}
