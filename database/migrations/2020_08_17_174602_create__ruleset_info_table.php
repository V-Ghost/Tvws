<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRulesetInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('RulesetInfos', function (Blueprint $table) {
            $table->string('authority',100);
           
            $table->string('rulesetId',100);
            $table->float('maxLocationChange');
            $table-> integer('maxPollingSecs');
            $table->primary('rulesetId');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('RulesetInfos');
    }
}
