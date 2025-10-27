<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $table = 'Personas';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'integer';
    protected $fillable = [
        'carnet', 
        'nombre', 
        'sexo', 
        'telefono', 
        'direccion', 
        'fecha_ingreso',
        'visible'
    ];

    public function docente()
    {
        return $this->hasOne(Docente::class);
    }

    public function administrativo()
    {
        return $this->hasOne(Administrativo::class);
    }

}
