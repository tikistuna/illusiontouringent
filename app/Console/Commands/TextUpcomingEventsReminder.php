<?php

namespace App\Console\Commands;

use App\Contracts\TextMessage\TextMessager;
use App\Http\Controllers\EventController;
use Illuminate\Console\Command;

class TextUpcomingEventsReminder extends Command
{

	private $textMessager;
	private $eventController;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'texts:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends a text message reminder to subscribers about upcoming events';

	/**
	 * Create a new command instance.
	 *
	 * @param TextMessager $textMessager
	 */
    public function __construct(TextMessager $textMessager, EventController $eventController)
    {
        parent::__construct();
        $this->textMessager = $textMessager;
        $this->eventController = $eventController;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
		$this->eventController->sendTextReminders($this->textMessager);
    }
}
