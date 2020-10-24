<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Query\Expression;

class CreateDeviceDescriptorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('DeviceDescriptor-Master', function (Blueprint $table) {
            
            $table->string('serialNumber', 100);
            $table->string('deviceId', 100);
            $table->string('password', 100);
            $table->string('username', 100);
            $table->string('modelId', 100);
            $table->string('manufacturerId', 100);
            $table->string('region', 100);
            $table->string('district', 100);
            $table->string('operator', 100);
            $table->float('radiatedpower');
            $table->float('conductedpower');
            $table->float('transmitter_power');
            $table->float('latitude');
            $table->float('longitude');
            $table->float('antennaheight');
            $table->string('antennaheighttype');
            $table->primary('deviceId');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('DeviceDescriptor-Master');
    }
}
