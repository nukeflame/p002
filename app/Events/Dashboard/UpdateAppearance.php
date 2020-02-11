<?php

namespace App\Events\Dashboard;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Events\DashboardEvent;

class UpdateAppearance extends DashboardEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /** @var string */
    public $mode;
     
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(string $mode)
    {
        $this->mode = $mode;
    }
}
