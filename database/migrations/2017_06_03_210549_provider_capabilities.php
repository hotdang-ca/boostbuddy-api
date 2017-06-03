<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProviderCapabilities extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('serviceproviders', function (Blueprint $table) {
          $table->decimal('rateBoost', 5, 2)->default(0.00);
          $table->decimal('rateTire', 5, 2)->default(0.00);
          $table->decimal('rateFuel', 5, 2)->default(0.00);
          $table->decimal('rateLockout', 5, 2)->default(0.00);
          $table->decimal('rateTow', 5, 2)->default(0.00);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('serviceproviders', function (Blueprint $table) {
        $table->dropColumn('rateBoost');
        $table->dropColumn('rateTire');
        $table->dropColumn('rateFuel');
        $table->dropColumn('rateLockout');
        $table->dropColumn('rateTow');
      });
    }
}
