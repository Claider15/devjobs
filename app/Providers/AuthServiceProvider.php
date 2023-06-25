<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        VerifyEmail::toMailUsing(function($notifiable, $url) {
            return (new MailMessage)
                ->subject('Verificar Cuenta')
                ->line('Tu Cuenta ya está casi lista, solo debes presionar el enlace a continuación.')
                ->action('Confirmar Cuenta', $url)
                ->line('Si no creaste esta cuenta, puedes ignorar este mensaje');
        });
    }
}
