<?php

use Illuminate\Database\Seeder;

class RulesetInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\RulesetInfo::class, 6)->create();
        // ->each(
        //     function ($RulesetInfos) {
        //         factory(App\Spectrums::class)->create([
        //             'rulesetId' => $RulesetInfos->rulesetId

        //         ])->each(
        //                 function ($Spectrums) {
        //                     factory(App\SpectrumProfilePoints::class)->create([
        //                         'Spectrums_id' => $Spectrums->id
            
        //                     ]);
                           
        //                 }
        //             );
        //         // factory(App\SpectrumProfilePoints::class)->create([
        //         //     'rulesetId' => $RulesetInfos->rulesetId

        //         // ]);
        //     }
        // );
        // ->each(
        //     function ($Spectrums) {
        //         factory(App\SpectrumProfilePoints::class)->create([
        //             'Spectrums_id' => $Spectrums->id

        //         ]);
               
        //     }
        // );
    }
}
