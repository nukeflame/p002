<?php

namespace App\Console\Commands\Dashboard;

use Illuminate\Console\Command;
use App\Events\Dashboard\Heartbeat;
use App\User;

class SendHeartbeatCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dashboard:send-heartbeat';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a heartbeat to the internet connection tile';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Sending heartbeat...');

        event(new Heartbeat());
        // broadcast(new PrivateEvent(User::find(1)));

        $this->info('All done! ');
    }
}
