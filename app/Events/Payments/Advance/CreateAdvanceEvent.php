<?php

namespace App\Events\Payments\Advance;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use App\Events\PaymentsEvents;
use Auth;

class CreateAdvanceEvent extends PaymentsEvents
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $advance;
    public $userId;
    
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($advance)
    {
        $this->userId = Auth::user()->id;
        $this->advance = $advance;
    }


    public function broadcastWith()
    {
        return ['advance' => $this->advance, 'userId' => $this->userId];
    }

    /**
     * * The event's broadcast name. 
     * *
     * * @return string
     * */
    public function broadcastAs()
    {
        return 'Advance.CreateAdvanceEvent';
    }
}
