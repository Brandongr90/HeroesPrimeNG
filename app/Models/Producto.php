<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'producto';
    protected $fillable = ['nombre', 'genero', 'descripcion', 'url', 'categoria'];
    public $timestamps = false;
    public $primaryKey = 'id';
}
