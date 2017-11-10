<?php

namespace App\Providers;

use App\Exceptions\GeneralException;
use App\Http\Requests\Request;

use App\Models\RtoCompany;
use App\Models\Dealer;
use App\Models\Order;
use App\Models\Building;
use App\Models\BuildingOption;
use App\Models\BuildingHistory;
use App\Models\BuildingLocation;
use App\Models\BuildingPackage;
use App\Models\BuildingPackageCategory;
use App\Models\Option;
use App\Models\OptionCatalog;
use App\Models\Style;
use App\Models\StyleCatalog;
use App\Models\Color;
use App\Models\ColorCatalog;
use App\Models\OrderReference;
use App\Models\User;
use App\Models\Plant;
use App\Models\Customer;

use App\Observers\PlantObserver;
use App\Observers\BuildingObserver;
use App\Observers\BuildingOptionObserver;
use App\Observers\UserObserver;
use App\Observers\CustomerObserver;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     * @return void
     * @throws GeneralException
     */
    public function boot()
    {
        require_once(__DIR__ .'/../constants.php');

        Relation::morphMap([
            'user' => User::class,
            'dealer' => Dealer::class,
            'rto-company' => RtoCompany::class,
            'building' => Building::class,
            'building-package' => BuildingPackage::class,
            'building-package-category' => BuildingPackageCategory::class,
            'order' => Order::class,
            'order-reference' => OrderReference::class,
            'status' => BuildingHistory::class,
            'location' => BuildingLocation::class,
            'option' => Option::class,
            'option-catalog' => OptionCatalog::class,
            'style' => Style::class,
            'style-catalog' => StyleCatalog::class,
            'color' => Color::class,
            'color-catalog' => ColorCatalog::class
        ]);

        Plant::observe(PlantObserver::class);
        Building::observe(BuildingObserver::class);
        BuildingOption::observe(BuildingOptionObserver::class);
        User::observe(UserObserver::class);
        Customer::observe(CustomerObserver::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }
}
