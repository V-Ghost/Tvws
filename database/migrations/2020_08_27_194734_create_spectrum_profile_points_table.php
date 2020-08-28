<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpectrumProfilePointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('SpectrumProfilePoints', function (Blueprint $table) {
            
            // $table->string('rulesetId',100);
            $table->float('hz');
            $table->float('dbm');
            $table->foreignId('Spectrums_id')->constrained('Spectrums');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('SpectrumProfilePoints');
    }
}
