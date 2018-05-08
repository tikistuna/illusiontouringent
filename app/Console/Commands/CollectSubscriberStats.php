<?php

namespace App\Console\Commands;


use App\Http\Controllers\SuscriberController;
use Illuminate\Console\Command;

class CollectSubscriberStats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriber:collect';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Collects # of phone and email subscribers, both verified and unverified, respectively.';

    protected $suscriber;

	/**
	 * Create a new command instance.
	 *
	 * @param SuscriberController $suscriber
	 */
    public function __construct(SuscriberController $suscriber)
    {
        parent::__construct();

        $this->suscriber = $suscriber;

    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
    	$this->suscriber->getStats();
    }
}
