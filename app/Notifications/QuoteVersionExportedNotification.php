<?php

namespace App\Notifications;

use App\Models\QuoteVersion;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class QuoteVersionExportedNotification extends Notification
{
    use Queueable;

    public function __construct(private readonly QuoteVersion $version)
    {
        //
    }

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $quote = $this->version->quote;

        return (new MailMessage)
            ->subject('Cotización '.$quote->quote_number.' lista para revisión')
            ->greeting('Hola,')
            ->line('La cotización '.$quote->quote_number.' de FAB STUDIO ya fue revisada, aprobada y exportada por el equipo.')
            ->line('Puedes ingresar al portal para revisar el estado del proyecto y sus documentos asociados.')
            ->action('Abrir portal FAB STUDIO', url('/portal'))
            ->line('Si tienes dudas, responde este correo para coordinar la revisión con el estudio.');
    }
}
