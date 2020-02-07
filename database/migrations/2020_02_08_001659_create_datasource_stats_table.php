<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDatasourceStatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('datasource_stats', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('country_name');
            $table->bigInteger('confirmed')->default(0);
            $table->bigInteger('deaths')->default(0);
            $table->bigInteger('recovered')->default(0);
            $table->string('datetime_string');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('datasource_stats');
    }
}
