<?php

namespace App\Models;

use App\Models\Salario;
use App\Models\Candidato;
use App\Models\Categoria;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vacante extends Model
{
    use HasFactory;

    protected $casts = ['ultimo_dia' => 'datetime'];

    protected $fillable = [
        'titulo',
        'salario_id',
        'categoria_id',
        'empresa',
        'ultimo_dia',
        'descripcion',
        'imagen',
        'user_id'
    ];

    public function categoria() {
        return $this->belongsTo(Categoria::class);
    }
    
    public function salario() {
        return $this->belongsTo(Salario::class);
    }

    public function candidatos() {
        return $this->hasMany(Candidato::class)->orderBy('created_at', 'DESC');
    }

    public function reclutador() { // esta relación va a ser hacia el reclutador (quien publicó la vacante)
        return $this->belongsTo(User::class, 'user_id');
    }
}
