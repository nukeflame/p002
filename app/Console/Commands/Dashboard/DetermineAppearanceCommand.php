<?php

namespace App\Console\Commands\Dashboard;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Events\Dashboard\UpdateAppearance;

class DetermineAppearanceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dashboard:determine-appearance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Determine the looks of the dashboard';

    /** @var float */
    protected $antwerpLat = 51.260197;

    /** @var float */
    protected $antwerpLng = 4.402771;

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
        $this->info('Determining dashboard appearance...');

        $appearance = $this->sunIsUp()
            ? 'light-mode'
            : 'dark-mode';

        event(new UpdateAppearance($appearance));

        $this->info('All done! '. $appearance);
    }


    public function sunIsUp(): bool
    {
        $sunriseTimestamp = date_sunrise(
            now()->timestamp,
            SUNFUNCS_RET_TIMESTAMP,
            $this->antwerpLat,
            $this->antwerpLng
        );
        $sunrise = Carbon::createFromTimestamp($sunriseTimestamp);

        $sunsetTimestamp = date_sunset(
            now()->timestamp,
            SUNFUNCS_RET_TIMESTAMP,
            $this->antwerpLat,
            $this->antwerpLng
        );
        $sunset = Carbon::createFromTimestamp($sunsetTimestamp);

        return now()->between($sunrise, $sunset);
    }
}
