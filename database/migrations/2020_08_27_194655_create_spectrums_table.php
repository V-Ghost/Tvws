<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpectrumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Spectrums', function (Blueprint $table) {
            $table->float('ID');
            $table->string('Transmitter_Name', 100);
            $table->string('Region', 100);
            $table->float('RF_channel');
            $table->string('TV_Band_Number', 100);
            $table->float('Transmit_lat');
            $table->float('Transmit_long');
            $table->float('Channel_BW');
            $table->float('Lower_Frequency');
            $table->float('Center_Frequency');
            $table->float('Upper_Frequency');
            $table->float('Transmit_Power_kW');
            $table->float('Antenna_height');
            $table->float('Transmit_distance');
            $table->dateTime('created_at', 0);
           });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Spectrums');
    }
}
