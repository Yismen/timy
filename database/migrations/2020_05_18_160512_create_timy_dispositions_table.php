<?php

use Dainsys\Timy\Disposition;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimyDispositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('timy_dispositions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->boolean('payable')->default(false);
            $table->boolean('invoiceable')->default(false);
            $table->timestamps();
        });

        /**
         * Create
         */
        Disposition::insert(config('timy.initial_dispositions'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('timy_dispositions');
    }
}
