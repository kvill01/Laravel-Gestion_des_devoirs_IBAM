<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Devoirs;

class SurveillantAffectation extends Notification implements ShouldQueue
{
    use Queueable;

    protected $devoir;

    /**
     * Create a new notification instance.
     */
    public function __construct(Devoirs $devoir)
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
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $acceptUrl = url("/surveillant/devoir/{$this->devoir->id}/repondre/accepte");
        $refuseUrl = url("/surveillant/devoir/{$this->devoir->id}/repondre/refuse");

        return (new MailMessage)
            ->subject('Affectation à un devoir')
            ->greeting("Bonjour {$notifiable->nom} {$notifiable->prenom},")
            ->line("Vous avez été affecté(e) comme surveillant pour le devoir suivant :")
            ->line("Devoir : {$this->devoir->nom_devoir}")
            ->line("Cours : {$this->devoir->cours->intitule}")
            ->line("Date : " . $this->devoir->date_heure->format('d/m/Y H:i'))
            ->line("Durée : {$this->devoir->duree_minutes} minutes")
            ->line("Salle : {$this->devoir->salle->nom}")
            ->line("Veuillez indiquer votre disponibilité en cliquant sur l'un des liens suivants :")
            ->action('Accepter', $acceptUrl)
            ->line("Ou")
            ->action('Refuser', $refuseUrl)
            ->line("Merci de répondre dans les plus brefs délais.");
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
            'nom_devoir' => $this->devoir->nom_devoir,
            'date_heure' => $this->devoir->date_heure,
            'salle' => $this->devoir->salle->nom,
        ];
    }
}
