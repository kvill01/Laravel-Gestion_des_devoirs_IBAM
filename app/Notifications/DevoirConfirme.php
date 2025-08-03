<?php

namespace App\Notifications;

use App\Models\Devoir;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DevoirConfirme extends Notification
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
            ->subject('Devoir confirmé : ' . $this->devoir->nom_devoir)
            ->greeting('Bonjour ' . $notifiable->nom . ',')
            ->line('Votre devoir "' . $this->devoir->nom_devoir . '" a été confirmé par l\'administration.')
            ->line('Date du devoir : ' . $this->devoir->date_heure->format('d/m/Y à H:i'))
            ->line('Durée : ' . $this->devoir->duree_minutes . ' minutes')
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
            'titre' => $this->devoir->nom_devoir,
            'message' => 'Votre devoir "' . $this->devoir->nom_devoir . '" a été confirmé',
            'date_devoir' => $this->devoir->date_heure->format('d/m/Y à H:i'),
            'type' => 'devoir_confirme',
            'url' => '/enseignant/devoirs/' . $this->devoir->id
        ];
    }
}
