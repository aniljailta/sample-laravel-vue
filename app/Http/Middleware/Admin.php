<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Request;

class Admin
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
        if (Auth::user()->hasRole('administrator')) {
            return $next($request);
        }

        if ($request->ajax()) {
            return response('You are not authorized to access this page', 401);
        }

        return redirect()->to('/')->with('messages', [
            'title' => 'Not authorized',
            'text' => 'You are not authorized to access this page',
            'type' => '403'
        ]);
    }
}
