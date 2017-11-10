<?php

use App\Models\ManufacturerCompany;
use App\Models\Order;
use App\Models\OrderReference;
use App\Models\Sale;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Company;
use App\Models\RtoCompany;

class AddCompanyTenantIdFieldToTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::transaction(function () {
            /*
            $rtoCompanyID = (int) Artisan::call('tenant:add-initial', [
                'role_id' => 'rto',
                'domain' => null,
                'is_active' => 1,
                '--no-interaction' => true
            ]);
            */
            $rtoCompany = RtoCompany::first();
            if (!$rtoCompany) {
                $rtoCompany = new RtoCompany;
                $rtoCompany->save();
            }
            // $rtoCompanyID = $rtoCompany->company_id;

            $rtoCompanyTenant = $rtoCompany->company()->create([
                'role_id' => 'rto',
                'domain' => null,
                'is_active' => 1
            ]);
            $rtoCompanyID = $rtoCompanyTenant->id;

            $manufacturerCompanyID = (int) Artisan::call('tenant:add', [
                '--no-interaction' => true,
                'role_id' => 'manufacturer',
                'domain' => parse_url(env('APP_URL'), PHP_URL_HOST),
                'is_active' => 1,
            ]);

            // add new columns and default company id
            $this->addTenantColumns('manufacturer', MANUFACTURER_COMPANY_ID, $manufacturerCompanyID);
            $this->addTenantColumns('global', COMPANY_ID, $manufacturerCompanyID);
            $this->addTenantColumns('rto', RTO_COMPANY_ID, $rtoCompanyID);
        });
    }

    /**
     * @param string $role
     * @param string $column
     * @param int $companyID
     */
    private function addTenantColumns(string $role, string $column, int $companyID) {
        $tenantableTables = $this->getTenantableTables($role);
        $total = count($tenantableTables);
        echo "Total tables: {$total} \n";

        foreach($tenantableTables as $i => $table) {
            $i++;
            $this->addTenantColumn($table, $column);
            echo "[{$i}/{$total}] Add column `{$column}` to `{$table}` table \n";
        }

        if ($role === 'rto') {
            // add default rto company id to specific rows (orders.payment_type = 'rto')
            Order::where('payment_type', 'rto')->withTrashed()->update([$column => $companyID]);

            OrderReference::whereHas('order', function($q) {
                $q->where('payment_type', 'rto');
                $q->withTrashed();
            })->withTrashed()->update([$column => $companyID]);

            Sale::whereHas('order', function($q) {
                $q->where('payment_type', 'rto');
                $q->withTrashed();
            })->withTrashed()->update([$column => $companyID]);

        } else {
            // add default manufacturer | company id to tables
            foreach($tenantableTables as $i => $table) {
                $i++;
                $this->applyDefaultCompanyId($table, $column, $companyID);
                echo "[{$i}/{$total}] Set default company id `{$column}` = '{$companyID}' `{$table}` table \n";
            }
        }

        echo "Completed \n";
    }

    /**
     * Add new company tenant column + foreign key
     * @param string $table
     * @param string $column
     */
    private function addTenantColumn(string $table, string $column) {
        if (!Schema::hasColumn($table, $column)) {
            if ($column === RTO_COMPANY_ID) {
                $targetTable = (new RtoCompany())->getTable();
                Schema::table($table, function (Blueprint $table) use ($column, $targetTable) {
                    $table->integer($column)->unsigned()->nullable();
                    $table->foreign($column)
                        ->references(COMPANY_ID)
                        ->on($targetTable)
                        ->onUpdate('cascade')
                        ->onDelete('restrict');
                });
            }
            if ($column === MANUFACTURER_COMPANY_ID) {
                $targetTable = (new ManufacturerCompany())->getTable();
                Schema::table($table, function (Blueprint $table) use ($column, $targetTable) {
                    $table->integer($column)->unsigned()->nullable();
                    $table->foreign($column)
                        ->references(COMPANY_ID)
                        ->on($targetTable)
                        ->onUpdate('cascade')
                        ->onDelete('restrict');
                });
            }
            if ($column === COMPANY_ID) {
                $targetTable = (new Company())->getTable();
                Schema::table($table, function (Blueprint $table) use ($column, $targetTable) {
                    $table->integer($column)->unsigned()->nullable();
                    $table->foreign($column)
                        ->references('id')
                        ->on($targetTable)
                        ->onUpdate('cascade')
                        ->onDelete('restrict');
                });
            }
        }
    }

    /**
     * Set default company ID for new column
     * @param $table
     * @param string $column
     * @param int $companyID
     */
    private function applyDefaultCompanyId($table, string $column, int $companyID) {
        // force set all current data to initial company
        DB::table($table)->update([$column => $companyID,]);

        // remove nullable property for company id
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        DB::statement("ALTER TABLE `{$table}` CHANGE `" . $column . "` `" . $column . "` INT(10) UNSIGNED NOT NULL;");
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }

    /**
     * Tables which requires new column
     * @param string $role
     * @return array
     */
    private function getTenantableTables(string $role) {
        // RTO_COMPANY_ID
        $rto = [
            'orders',
            'order_references',
            'sales',
        ];

        // MANUFACTURER_COMPANY_ID
        $manufacturer = [
            'colors',
            'bills',
            'expenses',
            'buildings',
            'building_plants',
            'building_models',
            'building_packages',
            'building_package_categories',
            'dealers',
            'deliveries',
            'options',
            'orders',
            'order_contacts',
            'order_references',
            'plants',
            'price_groups',
            'price_group_prices',
            'qrcodes',
            'locations',
            'sales',
            'styles',
            'trailers',
            'trucks',
        ];

        // COMPANY_ID
        $global = [
            'users',
            'files'
        ];

        $tables = [
            // 'company_manufacturer_settings',
            // 'company_rto_settings',
            'rto' => $rto,
            'manufacturer' => $manufacturer,
            'global' => $global,
        ];

        return $tables[$role];
    }

    /**
     * @param string $role
     * @param string $column
     */
    private function dropTenantColumns(string $role, string $column) {
        $tenantableTables = $this->getTenantableTables($role);
        $total = count($tenantableTables);
        echo "Total tables: {$total} \n";

        foreach($tenantableTables as $i => $table) {
            $i++;
            if (Schema::hasColumn($table, $column)) {
                Schema::table($table, function (Blueprint $table) use($column) {
                    $table->dropForeign($table->getTable() . '_' . $column . '_foreign');
                    $table->dropColumn($column);
                });
            }
            echo "Progress: {$i}/{$total} \n";
        }

        echo "Completed \n";
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->dropTenantColumns('rto');
        $this->dropTenantColumns('manufacturer');
        $this->dropTenantColumns('global');
    }
}
