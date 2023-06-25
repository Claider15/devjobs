<?php

namespace App\Http\Livewire;

use App\Models\Vacante;
use App\Notifications\NuevoCandidato;
use Livewire\Component;
use Livewire\WithFileUploads;

class PostularVacante extends Component
{
    use WithFileUploads;
    public $cv;
    public $vacante;

    public function mount(Vacante $vacante) {
        $this->vacante = $vacante;
    }

    protected $rules = [
        'cv' => 'required|mimes:pdf'
    ];

    public function postularme() {

        $datos = $this->validate(); // en caso de que haya errores, los va a pasar a la vista y va a decir donde están los errores
    
        // Almacenar el cv
        $cv = $this->cv->store('public/cv'); // lo va a colocar en el disco duro (en alguna ruta)
        $datos['cv'] = str_replace('public/cv/', '', $cv);


        // Crear el candidato a la vacante
        $this->vacante->candidatos()->create([
            'user_id' => auth()->user()->id,
            'cv' => $datos['cv']
            // vacante_id no es necesario porque ya ha sido definida en la funcion candidatos(). Ya sabe cuál es el id y se asigna; gracias a la relación
        ]);

        // Crear notificación y enviar el email
        $this->vacante->reclutador->notify(new NuevoCandidato($this->vacante->id, $this->vacante->titulo, auth()->user()->id)); // id del usuario que está interesado en la vacante

        // Mostrar al usuario un mensaje de OK
        session()->flash('mensaje', 'Se envió correctamente tu información. Mucha suerte!!');

        return redirect()->back();
    }
    
    public function render()
    {
        return view('livewire.postular-vacante');
    }
}
