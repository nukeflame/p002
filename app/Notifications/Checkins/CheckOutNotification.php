<?php

namespace App\Notifications\Checkins;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Auth;
use Illuminate\Notifications\Messages\BroadcastMessage;
use App\Http\Resources\User\User as ResourceUser;
use App\Http\Resources\Checkin\Checkin as ResourceCheckin;
use App\Checkin;

class CheckOutNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $check;
    public $user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Checkin $check)
    {
        $this->check = $check;
        $this->user = Auth::user();
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database','broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'user' => new ResourceUser($this->user),
            'checkout' => new ResourceCheckin($this->check),
        ];
    }

    /**
     * Get the broadcastable representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return BroadcastMessage
     */
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'data' => [
                'user' => new ResourceUser($this->user),
                'checkout' => new ResourceCheckin($this->check),
            ]
        ]);
    }
}

// App\Http\Resources\Notification;
