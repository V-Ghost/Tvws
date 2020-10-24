<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeviceDescriptorClientTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deviceDescriptor-Client', function (Blueprint $table) {
            $table->string('serialNumber', 100);
            $table->string('deviceId', 100);
            $table->string('password', 100);
            $table->string('username', 100);
            $table->string('modelId', 100);
            $table->string('manufacturerId', 100);
            $table->string('phoneNumber',100);
            $table->string('deviceType', 100);
            $table->string('region', 100);
            $table->string('district', 100);
            $table->string('operator', 100);
           
            $table->float('latitude');
            $table->float('longitude');
            $table->string('antennaheight',100);
            $table->float('antennaheighttype');
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
        Schema::dropIfExists('deviceDescriptor-Client');
    }
}
