<?php

namespace App\Console\Commands\Tenants;

use App\Repositories\Companies\CompanyRepository;
use App\Repositories\Companies\TenancyRepository;
use Illuminate\Console\Command;
use DB;

class TenantPopulateSettingsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant:setting {company_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initialize company settings';

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
     * @param TenancyRepository $tenancyRepository
     * @return mixed
     */
    public function handle(TenancyRepository $tenancyRepository)
    {
        $this->warn('Initialization of company settings started');
        $companyId = $this->argument('company_id');

        DB::transaction(function() use ($companyId, $tenancyRepository) {
            $company = (new CompanyRepository)->find($companyId);
            $tenancyRepository->populateDefaultCompanySettings($company);
        });

        $this->info('Done.');
    }
}
