<?php

namespace App\Notifications;

use App\Models\Devoir;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DevoirTermine extends Notification
{
    use Queueable;

    protected $devoir;

    /**
     * Create a new notification instance.
     */
    public function __construct(Devoir $devoir)
    {
        $this->devoir = $devoir;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $url = url('/enseignant/devoirs/' . $this->devoir->id);
        
        return (new MailMessage)
            ->subject('Devoir terminé : ' . $this->devoir->titre)
            ->greeting('Bonjour ' . $notifiable->nom . ',')
            ->line('Votre devoir "' . $this->devoir->titre . '" a été marqué comme terminé.')
            ->line('Vous pouvez maintenant procéder à l\'évaluation des copies.')
            ->action('Voir les détails', $url)
            ->line('Merci d\'utiliser notre application !');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'devoir_id' => $this->devoir->id,
            'titre' => $this->devoir->titre,
            'message' => 'Votre devoir "' . $this->devoir->titre . '" est terminé',
            'type' => 'devoir_termine',
            'url' => '/enseignant/devoirs/' . $this->devoir->id
        ];
    }
}
