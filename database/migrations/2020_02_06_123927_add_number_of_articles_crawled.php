<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNumberOfArticlesCrawled extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('scraper_statuses', function (Blueprint $table) {
            //
            $table->integer('number_of_articles_crawled')->default(0);
            $table->integer('number_of_articles_inserted')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('scraper_status', function (Blueprint $table) {
            //
        });
    }
}
