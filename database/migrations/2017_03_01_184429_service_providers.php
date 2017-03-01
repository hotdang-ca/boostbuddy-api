<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ServiceProviders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('serviceproviders', function (Blueprint $table) {
          $table->increments('id');

          $table->string('name');
          $table->string('billing_name')->nullable();
          $table->string('address');
          $table->string('phone');
          $table->string('email');
          $table->string('uuid');

          // the distance they would accept jobs for
          $table->float('lat');
          $table->float('lng');
          $table->double('radius');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('serviceproviders');
    }
}
