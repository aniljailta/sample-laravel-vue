<?php

namespace App\Http\Middleware;

use App\Services\Tenancy\TenancyService;

use Closure;
use Auth;
use Request;
use Store;

class Tenant
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
        // APP_URL = admin host
        // skip tenant scoping if current host is super admin app
        /*
        if (parse_url(env('APP_URL'), PHP_URL_HOST) === $_SERVER['HTTP_HOST']) {
            return $next($request);
        }
        */

        $tenancyService = new TenancyService;
        $companyTenant = $tenancyService->detectTenant();
        $tenancyService->applyTenant($companyTenant);
        
        return $next($request);
    }
}
