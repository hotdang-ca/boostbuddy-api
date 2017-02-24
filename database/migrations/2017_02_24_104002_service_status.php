<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ServiceStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('servicerequests', function (Blueprint $table) {
          $table->string('status')->nullable();
          $table->string('service_provider')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('servicerequests', function (Blueprint $table) {
          $table->dropColumn('status');
          $table->dropColumn('service_provider');
        });
    }
}
