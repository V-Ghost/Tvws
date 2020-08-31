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
            $table->id();
            $table->dateTime('created_at', 0);
            $table->string('rulesetId',100);
            $table->float('resolutionBwHz');
            $table->foreign('rulesetId')->references('rulesetId')->on('RulesetInfos');
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
