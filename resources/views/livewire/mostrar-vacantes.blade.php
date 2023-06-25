<div>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        @forelse ($vacantes as $vacante)
            <div class="p-6 bg-white border-b border-gray-200 md:flex md:justify-between items-center">
                <div class="space-y-3" class="text-xl font-bold">
                    <a href="{{route('vacantes.show', $vacante->id)}}">{{$vacante->titulo}}</a>
                    <p class="text-sm text-gray-600 font-bold">{{$vacante->empresa}}</p>
                    <p class="text-sm text-gray-500">Último día: {{$vacante->ultimo_dia->format('d/m/Y')}}</p>
                </div>
    
                <div class="flex flex-col items-stretch md:flex-row md:items-center gap-3 mt-5 md:mt-0">
                    <a class="bg-slate-800 py-2 px-4 rounded-lg text-white text-xs font-bold uppercase text-center" href="{{route('candidatos.index', $vacante)}}">
                        {{$vacante->candidatos->count()}}
                        Candidatos
                    </a>
    
                    <a class="bg-blue-800 py-2 px-4 rounded-lg text-white text-xs font-bold uppercase text-center" href="{{route('vacantes.edit', $vacante->id)}}">
                        Editar
                    </a>
    
                    <button class="bg-red-600 py-2 px-4 rounded-lg text-white text-xs font-bold uppercase text-center" wire:click="$emit('mostrarAlerta', {{$vacante->id}})">
                        Eliminar
                    </button>
                </div>
            </div>
        
        @empty
            <p class="p-3 text-center text-sm text-gray-600">No hay vacantes que mostrar</p>
        @endforelse
    </div>
    
    <div class="mt-10">
        {{$vacantes->links()}}
    </div>
</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        Livewire.on('mostrarAlerta', vacanteId => {
            Swal.fire({
                title: 'Eliminar Vacante?',
                text: "Una vacante eliminada no se puede recuperar",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, ¡Eliminar!',
                cancellButtonText: 'Cancelar'
                }).then((result) => {
                if (result.isConfirmed) {
                    // eliminar la vacante
                    Livewire.emit('eliminarVacante', vacanteId)

                    Swal.fire(
                    'Se eliminó la vacante',
                    'Eliminado Correctamente',
                    'success'
                    )
                }
            })
        })

    </script>
@endpush

