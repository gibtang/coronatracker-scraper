<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDataAggregatedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_aggregated', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('deaths');
            $table->text('source_deaths');
            $table->bigInteger('confirmed');
            $table->text('source_confirmed');
            $table->bigInteger('recovered');
            $table->text('source_recovered');
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
        Schema::dropIfExists('data_aggregated');
    }
}
