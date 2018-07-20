<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode as MaintenanceMode;

class CheckForMaintenanceMode
{

	protected $app;

	public function __construct(Application $app){
		$this->app = $app;
	}

	/**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

    	if($this->app->isDownForMaintenance() && !in_array($request->getClientIp(), $_ENV['WHITELISTED_IP_ADDRESSES'])){
    		$maintenanceMode = new MaintenanceMode($this->app);
    		return $maintenanceMode->handle($request, $next);
	    }

        return $next($request);
    }
}
