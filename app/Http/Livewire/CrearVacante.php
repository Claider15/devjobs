<?php

namespace App\Http\Livewire;

use App\Models\Salario;
use App\Models\Vacante;
use Livewire\Component;
use App\Models\Categoria;
use Livewire\WithFileUploads;

class CrearVacante extends Component
{
    public $titulo;
    public $salario;
    public $categoria;
    public $empresa;
    public $ultimo_dia;
    public $descripcion;
    public $imagen;

    use WithFileUploads;

    protected $rules = [
        'titulo' => 'required|string',
        'salario' => 'required',
        'categoria' => 'required',
        'empresa' => 'required',
        'ultimo_dia' => 'required',
        'descripcion' => 'required',
        'imagen' => 'required|image|max:1024'
    ];

    public function crearVacante() {
        $datos = $this->validate(); // en caso de que haya errores, los va a pasar a la vista y va a decir donde están los errores
    
        // Almacenar la imagen (la referencia)
        $imagen = $this->imagen->store('public/vacantes'); // lo va a colocar en el disco duro (en alguna ruta)
        $datos['imagen'] = str_replace('public/vacantes/', '', $imagen);

        // dd($nombreImagen);

        // Crear la vacante
        Vacante::create([
            'titulo' => $datos['titulo'],
            'salario_id' => $datos['salario'], // live:model('salario')
            'categoria_id' => $datos['categoria'], // live:model('categoria')
            'empresa' => $datos['empresa'],
            'ultimo_dia' => $datos['ultimo_dia'],
            'descripcion' => $datos['descripcion'],
            'imagen' => $datos['imagen'],
            'user_id' => auth()->user()->id // Livewire tiene acceso a todo loque tiene laravel
        ]);

        //Crear un mensaje
        session()->flash('mensaje', 'La vacante se publicó correctamente');

        // Redireccionar al usuario hacia las vacantes
        return redirect()->route('vacantes.index');
    }

    public function render()
    {
        // Consultar BD
        $salarios = Salario::all();
        $categorias =Categoria::all();

        return view('livewire.crear-vacante', [
            'salarios' => $salarios,
            'categorias' => $categorias
        ]);
    }
}
