<?php

namespace App\Notifications;

use App\Checkin;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class CheckInNotf extends Notification implements ShouldQueue
{
    use Queueable;

    public $check;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Checkin $check)
    {
        $this->check = $check;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the broadcastable representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return BroadcastMessage
     */
    // public function toBroadcast($notifiable)
    // {
    //     return new BroadcastMessage([
    //         'data' => [
    //             'attendance' => $this->attendance,
    //         ],
    //         'read_at' => null,
    //     ]);
    // }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'check' => $this->check,
        ];
    }
}
