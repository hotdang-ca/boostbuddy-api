<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NewServiceRequest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('servicerequests', function (Blueprint $table) {
        $table->increments('id');

        $table->string('firstname');
        $table->string('lastname');
        $table->string('phone');
        $table->string('email');
        $table->string('car_description');
        $table->string('service_type');

        $table->string('origin_label');
        $table->string('origin_desc');
        $table->string('origin_lat');
        $table->string('origin_lng');

        $table->string('destination_label')->nullable();
        $table->string('destination_desc')->nullable();
        $table->string('destination_lat')->nullable();
        $table->string('destination_lng')->nullable();

        $table->integer('tow_distance')->nullable();
        $table->integer('quoted_price');

        $table->string('order_number');
        $table->boolean('isPaid');
        $table->integer('amountPaid')->nullable();

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
      Schema::dropIfExists('servicerequests');
    }
}
