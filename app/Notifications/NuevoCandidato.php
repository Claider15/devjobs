<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NuevoCandidato extends Notification
{
    use Queueable;

    public $id_vacante;
    public $nombre_vacante;
    public $usuario_id;

    /**
     * Create a new notification instance.
     */
    public function __construct($id_vacante, $nombre_vacante, $usuario_id)
    {
        // Se va a pasar la inf. que quieres que tenga esta notificación
        $this->id_vacante = $id_vacante;
        $this->nombre_vacante = $nombre_vacante;
        $this->usuario_id = $usuario_id;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database']; // esta notificación se va a enviar por emai y se va a almacenar en la BD
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $url = url('/notificaciones');

        return (new MailMessage)
                    ->line('Has recibido un nuevo candidato en tu vacante')
                    ->line('La vacante es: ' . $this->nombre_vacante)
                    ->action('Ver Notificaciones', $url)
                    ->line('Gracias por utilizar DevJobs');
    }

    // Almacena las notificaciones en la base de datos
    public function toDatabase(object $notifiable) {
        return [
            // el arreglo se convierte automáticamente en un objeto y se guarda en la BD
            'id_vacante' => $this->id_vacante,
            'nombre_vacante' => $this->nombre_vacante,
            'usuario_id' => $this->usuario_id
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    // public function toArray(object $notifiable): array
    // {
    //     return [
    //         // Almacena en un arreglo dif. inf. para esa notificación
    //     ];
    // }
}
