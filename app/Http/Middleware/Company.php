<?php
namespace App\Http\Middleware;

use Closure;
use Store;

class Company
{
	protected $auth;

	/**
	 * Creates a new instance of the middleware.
	 */
	public function __construct()
	{
	}

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  Closure $next
	 * @param  $roles
	 * @return mixed
	 */
	public function handle($request, Closure $next, $roles)
	{
	    $company = Store::get('company');
		if (!in_array($company->role_id, explode('|', $roles))) {
			return response('You are not authorized to access this page', 403);
		}

		return $next($request);
	}
}
