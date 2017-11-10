<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\BuildingStatus;

class AddIsSystemToBuildingStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('building_statuses', function (Blueprint $table) {
            $table->boolean('is_system')->default(0)->after('is_active');
        });

        BuildingStatus::updateOrCreate(
            ['name' => 'Draft'],
            ['type' => 'build', 'priority' => 0, 'is_system' => 1, 'is_active' => 'yes']
        );
        BuildingStatus::updateOrCreate(
            ['name' => 'Pending'],
            ['type' => 'build', 'priority' => 1, 'is_system' => 1, 'is_active' => 'yes']
        );
        BuildingStatus::updateOrCreate(
            ['name' => 'Building Started'],
            ['type' => 'build', 'priority' => 2, 'is_system' => 1, 'is_active' => 'yes']
        );
        $priority = 3;

        $buildingStatuses = BuildingStatus::orderBy('priority')->get();
        foreach ($buildingStatuses as $buildingStatus) {
            if ( !in_array($buildingStatus->name, ['Draft', 'Pending', 'Building Started', 'Ready to Deliver']) ) {
                $buildingStatus->update(['type' => 'build', 'priority' => $priority]);
                $priority++;
            }
        }

        BuildingStatus::updateOrCreate(
            ['name' => 'Ready to Deliver'],
            ['type' => 'build', 'priority' => $priority, 'is_system' => 1, 'is_active' => 'yes']
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('building_statuses', function (Blueprint $table) {
            $table->dropColumn('is_system');
        });
    }
}