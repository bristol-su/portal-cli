<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sites', function (Blueprint $table) {
            $table
                ->foreign('current_feature_id')
                ->references('id')
                ->on('features')
                ->nullOnDelete();
        });

        Schema::table('features', function (Blueprint $table) {
            $table
                ->foreign('site_id')
                ->references('id')
                ->on('sites')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });

        Schema::table('local_packages', function (Blueprint $table) {
            $table
                ->foreign('feature_id')
                ->references('id')
                ->on('features')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // TODO
    }
}
