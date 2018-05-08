<?php

namespace App\Console\Commands;

use App\Contracts\UrlShortener\UrlShortener;
use App\Http\Controllers\EventController;
use Illuminate\Console\Command;

class RefreshUrlClicks extends Command
{

	protected $event;
	protected $urlShortener;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'urlclicks:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refreshes stats on shortened urls';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(EventController $event, UrlShortener $urlShortener)
    {
        parent::__construct();
        $this->event = $event;
        $this->urlShortener = $urlShortener;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->event->RefreshUrlClicks($this->urlShortener);
    }
}
