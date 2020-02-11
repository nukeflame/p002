<?php

namespace App\Events\Leaves;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use App\Events\LeavesEvent;
use Auth;

class CreateLeaveEvent extends LeavesEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $leave;
    public $userId;
    
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($leave)
    {
        $this->userId = Auth::user()->id;
        $this->leave = $leave;
    }


    public function broadcastWith()
    {
        return ['leave' => $this->leave, 'userId' => $this->userId];
    }
}
