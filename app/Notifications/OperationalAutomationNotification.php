<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OperationalAutomationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private readonly string $title,
        private readonly string $summary,
        private readonly ?string $actionText = null,
        private readonly ?string $actionUrl = null,
    ) {
        $this->onQueue('notifications');
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
        $message = (new MailMessage)
            ->subject($this->title)
            ->greeting('Hola,')
            ->line($this->summary);

        if ($this->actionText && $this->actionUrl) {
            $message->action($this->actionText, $this->actionUrl);
        }

        return $message
            ->line('Este mensaje fue generado automaticamente por FAB STUDIO App.')
            ->salutation('FAB STUDIO');
    }
}
