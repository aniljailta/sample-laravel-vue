<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip')->nullable(); // "32055"
            $table->string('building_in_same_address')->nullable(); // => "false"
            $table->string('building_location_address')->nullable();
            $table->string('building_location_city')->nullable();
            $table->string('building_location_state')->nullable();
            $table->string('building_location_zip')->nullable();
            $table->string('apartment_number')->nullable();
            $table->string('property_ownership')->nullable();
            $table->string('landlord_full_name')->nullable();
            $table->string('landlord_phone_number')->nullable();
            $table->string('cell_phone_number1')->nullable();
            $table->string('text_allowed1')->nullable(); // => "yes"
            $table->string('cell_phone_number2')->nullable();
            $table->string('text_allowed2')->nullable(); // => "yes"
            $table->string('home_phone_number')->nullable();
            $table->string('email_address')->nullable();
            $table->string('email_instead_of_mail')->nullable(); // => yes
            $table->string('renter_dob')->nullable(); // => "11-27-1980"
            $table->string('renter_ssn')->nullable(); // => "555-44-3333"
            $table->string('renter_dln')->nullable(); // => "555-44-3333"
            $table->string('renter_employer')->nullable();
            $table->string('renter_employer_phone_number')->nullable();
            $table->string('renter_employer_phone_extension')->nullable(); // 123
            $table->string('renter_supervisor')->nullable();
            $table->string('renter_supervisor_occupation')->nullable();

            $table->string('co_renter_first_name')->nullable();
            $table->string('co_renter_last_name')->nullable();
            $table->string('co_renter_dob')->nullable(); // => "11-27-1980"
            $table->string('co_renter_ssn')->nullable(); // => "555-44-3333"
            $table->string('co_renter_dln')->nullable(); // => "555-44-3333"
            $table->string('co_renter_employer')->nullable();
            $table->string('co_renter_employer_phone_number')->nullable();
            $table->string('co_renter_employer_phone_extension')->nullable(); // 123
            $table->string('co_renter_supervisor')->nullable();
            $table->string('co_renter_supervisor_occupation')->nullable();

            $table->string('reference1_name')->nullable();
            $table->string('reference1_relationship')->nullable();
            $table->string('reference1_phone_number')->nullable();
            $table->string('reference1_address')->nullable();
            $table->string('reference1_city')->nullable();
            $table->string('reference1_state')->nullable();
            $table->string('reference1_zip')->nullable();

            $table->string('reference2_name')->nullable();
            $table->string('reference2_relationship')->nullable();
            $table->string('reference2_phone_number');
            $table->string('reference2_address')->nullable();
            $table->string('reference2_city')->nullable();
            $table->string('reference2_state')->nullable();
            $table->string('reference2_zip')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('customers');
    }
}
