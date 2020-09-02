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
        Schema::create('DeviceDescriptor', function (Blueprint $table) {
            
            $table->string('serialNumber', 100);
            $table->string('manufacturerId', 100);
            $table->string('modelId', 100);
            $table->float('latitude');
            $table->float('longitude');
            $table->primary('modelId');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('DeviceDescriptor');
    }
}
