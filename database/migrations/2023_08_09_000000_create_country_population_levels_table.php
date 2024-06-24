<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountryPopulationLevelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('country_population_levels', function (Blueprint $table) {
            $table->id();
            $table->integer('count_value');
            $table->float('avg_population', 10, 2);
            $table->unsignedBigInteger('population_level_id');
            $table->unsignedBigInteger('country_region_id');
            $table->foreign('country_region_id')
                  ->references('id')
                  ->on('country_regions')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
            $table->foreign('population_level_id')
                  ->references('id')
                  ->on('population_levels')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('country_population_levels');
    }
}
