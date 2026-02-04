<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('control_numbers', function (Blueprint $table) {
            $table->enum('type', ['APPLICATION', 'REGISTRATION'])->default('APPLICATION')->after('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('control_numbers', function (Blueprint $table) {
            //
        });
    }
};
