<?php

namespace App\Console\Commands;

use App\Services\PriceGroups\PriceGroupService;
use Illuminate\Console\Command;

class PublishPriceGroup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'price-group:publish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'To Publishing A Price Group';

    /**
     * Execute the console command.
     *
     * @param PriceGroupService $priceGroupService
     * @return mixed
     */
    public function handle(PriceGroupService $priceGroupService)
    {
        $response = $priceGroupService->publishPriceGroup();
        echo $response;
    }
}
