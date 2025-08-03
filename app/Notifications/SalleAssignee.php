<?php

namespace App\Notifications;

use App\Models\Devoir;
use App\Models\Salle;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SalleAssignee extends Notification
{
    use Queueable;

    protected $devoir;
    protected $salle;

    /**
     * Create a new notification instance.
     */
    public function __construct(Devoir $devoir, Salle $salle)
    {
        $this->devoir = $devoir;
        $this->salle = $salle;
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
            ->subject('Salle assignée pour le devoir : ' . $this->devoir->titre)
            ->greeting('Bonjour ' . $notifiable->nom . ',')
            ->line('Une salle a été assignée pour votre devoir "' . $this->devoir->titre . '".')
            ->line('Salle : ' . $this->salle->nom . ' (' . $this->salle->localisation . ')')
            ->line('Capacité : ' . $this->salle->capacite . ' places')
            ->line('Date du devoir : ' . $this->devoir->date_devoir->format('d/m/Y à H:i'))
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
            'salle_id' => $this->salle->id,
            'titre' => $this->devoir->titre,
            'message' => 'La salle ' . $this->salle->nom . ' a été assignée à votre devoir "' . $this->devoir->titre . '"',
            'type' => 'salle_assignee',
            'url' => '/enseignant/devoirs/' . $this->devoir->id
        ];
    }
}
