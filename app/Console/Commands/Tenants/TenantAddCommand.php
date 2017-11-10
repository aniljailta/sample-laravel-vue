<?php

namespace App\Console\Commands\Tenants;

use App\Services\Tenancy\TenancyService;
use Illuminate\Console\Command;

class TenantAddCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant:add {role_id} {domain} {is_active}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add new tenant company';

    /**
     * Create a new command instance
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @param TenancyService $tenancyService
     * @return mixed
     */
    public function handle(TenancyService $tenancyService)
    {
        $tenantParams = [
            'role_id' => $this->argument('role_id'),
            'domain' => $this->argument('domain'),
            'is_active' => $this->argument('is_active'),
        ];
        $this->table(array_keys($tenantParams), [$tenantParams]);

        if ($this->confirm('Add new tenant?', 'yes')) {
            $company = $tenancyService->addTenant($tenantParams);
            $this->info("New tenant company {$company->role_id}#{$company->id} {$company->domain}' successfully added.");
            return $company->id;
        }
    }
}
