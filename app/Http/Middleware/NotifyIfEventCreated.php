<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Notifications\EventCreated;
use Closure;
use Illuminate\Support\Facades\Auth;

class NotifyIfEventCreated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response =  $next($request);

        if(Auth::user()->id !== 1){
            if($request->path() !== 'admin/excel' and $request->isMethod('post')){
                User::find(1)->notify(new EventCreated(Auth::user()->name));
            }
        }

        return $response;
    }
}
