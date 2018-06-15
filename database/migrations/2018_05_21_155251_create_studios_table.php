<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudiosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('studios', function (Blueprint $table) {
            $table->increments('id')->index('id');
            $table->string('name', 255)->nullable(false);
            $table->string('url', 255)->nullable();
            $table->string('tel', 20)->nullable(false);
            $table->string('zip', 20)->nullable(false);
            $table->string('prefecture', 20)->nullable(false);
            $table->string('city_1', 100)->nullable(false);
            $table->string('city_2', 100)->nullable();
            $table->point('location')->nullable();
            $table->integer('studio_count')->nullable()->default(0);
            $table->time('open_dt')->nullable();
            $table->time('end_dt')->nullable();
            $table->decimal('cheapest_price', 10, 3)->nullable()->default(0);
            $table->boolean('is_web_reservation')->nullable()->default(false);

            $table->softDeletes();
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
        Schema::dropIfExists('studios');
    }
}
