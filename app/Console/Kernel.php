<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('subscriber:collect')->dailyAt('03:40')->timezone('America/Chicago');
		$schedule->command('urlclicks:refresh')->dailyAt('03:50')->timezone('America/Chicago');
	    $schedule->command('texts:send')->dailyAt('18:00')->timezone('America/Chicago');
	    $schedule->command('monitor:check-uptime')->everyMinute();
	    $schedule->command('monitor:check-certificate')->daily();
	    $schedule->command('backup:clean')->dailyAt('2:00');
	    $schedule->command('backup:run')->dailyAt('2:30');
	    $schedule->command('backup:monitor')->dailyAt('3:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
