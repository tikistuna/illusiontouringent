<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckAdmin
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
    	if(Auth::check() and Auth::user()->name === 'Tikis Jiménez' and Auth::user()->email === 'tikis@ljproductions.us'){
		    return $next($request);
	    }elseif(Auth::check() and Auth::user()->name === 'Vaquis' and Auth::user()->email === 'girlgamo99@hotmail.com'){
			if($request->isMethod('get') or $request->path() === 'admin/winners'){
				return $next($request);
			}else{
				abort(403, 'No tienes autorización para tomar esta acción');
			}
	    }else{
		    return redirect('/login');
	    }

    }
}
