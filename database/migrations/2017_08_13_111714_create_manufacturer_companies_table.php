<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateManufacturerCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manufacturer_companies', function (Blueprint $table) {
            $table->increments(COMPANY_ID);
            $table->string('name')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->string('pinterest')->nullable();
            $table->string('gplus')->nullable();

            $table->string('boss_first_name')->nullable();
            $table->string('boss_last_name')->nullable();
            $table->string('boss_email')->nullable();
            $table->string('boss_phone')->nullable();
            $table->string('time_zone')->nullable();
            $table->mediumInteger('per_page');
            $table->string('mail_host')->nullable();
            $table->smallInteger('mail_port')->nullable();
            $table->string('mail_username')->nullable();
            $table->string('mail_password')->nullable();
            $table->string('mail_encryption')->nullable();

            $table->boolean('rto_is_used')->default(false);

            $table->integer('estimated_delivery_period')->default(5);
            $table->integer('lead_time')->default(21);
            $table->integer('initial_contact_eligibility')->default(0);
            $table->text('footnote')->nullable();

            $table->enum('delivery_dispatch', ['dispatch', 'driver'])->nullable();
            $table->string('delivery_contact_name')->nullable();
            $table->string('delivery_contact_phone')->nullable();
            $table->string('delivery_contact_email')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('manufacturer_companies');
    }
}
