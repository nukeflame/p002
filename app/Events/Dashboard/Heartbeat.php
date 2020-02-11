<?php

namespace App\Events\Dashboard;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Events\DashboardEvent;

// use GuzzleHttp\Exception\GuzzleException;
// use GuzzleHttp\Client;

class Heartbeat extends DashboardEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $client = new Client(); //GuzzleHttp\Client
        // $result = $client->post('your-request-uri', [
        //     'form_params' => [
        //         'sample-form-data' => 'value'
        //         ]
        //         ]);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastAs()
    {
        return 'server.status';
    }
}
