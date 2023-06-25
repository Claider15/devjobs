<?php

namespace App\Http\Livewire;

use App\Models\Salario;
use App\Models\Vacante;
use Livewire\Component;
use App\Models\Categoria;
use Livewire\WithFileUploads;
use Illuminate\Support\Carbon;

class EditarVacante extends Component
{
    
    public $vacanteId;
    public $titulo;
    public $salario;
    public $categoria;
    public $empresa;
    public $ultimo_dia;
    public $descripcion;
    public $imagen;
    public $imagenNueva;

    use WithFileUploads;
    
    protected $rules = [ // interno de livewire
        'titulo' => 'required|string',
        'salario' => 'required',
        'categoria' => 'required',
        'empresa' => 'required',
        'ultimo_dia' => 'required',
        'descripcion' => 'required',
        'imagenNueva' => 'nullable|image|max:1024'
    ];

    public function mount(Vacante $vacante) { // cuando el componente ha sido instanciado y ha sido ejecutado una vez
        $this->vacanteId = $vacante->id; // Esto no va a funcionar (por si solo - $this->id es palabra reservada de livewire)
        $this->titulo = $vacante->titulo;
        $this->salario = $vacante->salario_id;
        $this->categoria = $vacante->categoria_id;
        $this->empresa = $vacante->empresa;
        $this->ultimo_dia = Carbon::parse($vacante->ultimo_dia)->format('Y-m-d');
        $this->descripcion = $vacante->descripcion;
        $this->imagen = $vacante->imagen;
    }

    public function editarVacante() {
        $datos = $this->validate(); // interno de livewire. $datos es un arreglo con la inf. de lo último que el usuario tenga ingresado en este formulario

        // Revisar si hay una nueva imagen
        if($this->imagenNueva) {
            $imagen = $this->imagenNueva->store('public/vacantes');
            $datos['imagen'] = str_replace('public/vacantes/', '', $imagen);
        }


        // Encontrar la vacante a editar
        $vacante = Vacante::find($this->vacanteId); // $vacante es un objeto

        // Asignar los valores (reescribir)
        $vacante->titulo = $datos['titulo'];
        $vacante->salario_id = $datos['salario'];
        $vacante->categoria_id = $datos['categoria'];
        $vacante->empresa = $datos['empresa'];
        $vacante->ultimo_dia = $datos['ultimo_dia'];
        $vacante->descripcion = $datos['descripcion'];
        $vacante->imagen = $datos['imagen'] ?? $vacante->imagen;

        // Guardar la vacante
        $vacante->save();

        // Redireccionar
        session()->flash('mensaje', 'La vacante se actualizó correctamente');

        return redirect()->route('vacantes.index');
    }

    public function render()
    {
        // Consultar BD
        $salarios = Salario::all();
        $categorias =Categoria::all();
        return view('livewire.editar-vacante', [
            'salarios' => $salarios,
            'categorias' => $categorias
        ]);
    }
}
