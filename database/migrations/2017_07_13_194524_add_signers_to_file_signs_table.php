<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSignersToFileSignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('file_signs', function (Blueprint $table) {
            $table->string('signer_role')->nullable()->after('is_esigned');
            $table->integer('signer_id')->nullable()->after('signer_role');
            $table->index(['signer_role', 'signer_id']);
        });

        // Update signer role and id for existed rows
        $results = DB::table('file_signs')
            ->join('files', 'file_signs.file_id', '=', 'files.id')
            ->join('orders', 'orders.id', '=', 'files.storable_id')
            ->join('order_references', 'order_references.id', '=', 'orders.reference_id')
            ->select(['order_references.id as signer_id', 'file_signs.id as id'])
            ->get();

        foreach($results as $result)
        {
            DB::table('file_signs')->where('id', $result->id)->update([
                'signer_role' => 'customer',
                'signer_id' => $result->signer_id
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('file_signs', function (Blueprint $table) {
            $table->dropColumn('signer_role');
            $table->dropColumn('signer_id');
        });
    }
}
