<?php

namespace App\Console;

use App\Console\Commands\Tenants\TenantAddCommand;
use App\Console\Commands\Tenants\TenantPopulateSettingsCommand;
use App\Console\Commands\Tenants\TenantPopulateBuildingStatusesCommand;
use App\Console\Commands\LocationUpdateCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        TenantAddCommand::class,
        TenantPopulateSettingsCommand::class,
        TenantPopulateBuildingStatusesCommand::class,
        LocationUpdateCommand::class,
        \App\Console\Commands\PublishPriceGroup::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
         $schedule->command('price-group:publish')->everyMinute();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
