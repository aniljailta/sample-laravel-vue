<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Request;
use DB;
use Store;

class Timezone
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
        if (env('ENABLE_TIME_ZONE', true)) {
            $this->setTimeZone();
        }

        return $next($request);
    }

    /**
     * Set time zone based on tenant settings
     */
    private function setTimeZone() {
        $companySettings = Store::get('company_settings');
        $timezone = $companySettings->time_zone;
        if ($timezone) {

            $dt = new \DateTime('now', new \DateTimeZone($timezone));
            $abbreviation = $dt->format('T');

            config(['app.timezone' => $timezone]);
            view()->share('time_zone', [
                'name' => $timezone,
                'abbr' => $abbreviation
            ]);

            $offsetToFormat = new \DateTime('now', new \DateTimeZone($timezone));
            $timezoneOffset = $offsetToFormat->format('P');

            DB::statement("SET time_zone = '".$timezoneOffset."'");
        }
    }
}
