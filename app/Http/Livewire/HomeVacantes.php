<?php

namespace App\Http\Livewire;

use App\Models\Vacante;
use Livewire\Component;

class HomeVacantes extends Component
{
    public $termino; 
    public $categoria;
    public $salario;

    protected $listeners = ['terminosBusqueda' => 'buscar'];
    public function buscar($termino, $categoria, $salario) {
        $this->termino = $termino;
        $this->categoria = $categoria;
        $this->salario = $salario;

    }

    public function render()
    {
        // $vacantes = Vacante::all();

        $vacantes = Vacante::when($this->termino, function($query) { // when solo se va a ejecutar si hay un $this->termino
            $query->where('titulo', 'LIKE', "%" . $this->termino . "%"); // con el operador % decimos que no importa si está al inicio o final, pero mientras tenga el término de búsqueda lo va a encontrar (tanto izquierda como derecha del título - 2 %)
            // LIKE es un operador que se usa para buscar en base de datos de SQL
        })
        ->when($this->categoria, function($query) {
            $query->where('categoria_id', $this->categoria);
        })
        ->when($this->termino, function($query) {
            $query->orWhere('empresa', 'LIKE', "%" . $this->termino . "%");
        })
        ->when($this->salario, function($query) {
            $query->where('salario_id', $this->salario);
        })
        ->paginate(20);

        return view('livewire.home-vacantes', [
            'vacantes' => $vacantes
        ]);
    }
}
