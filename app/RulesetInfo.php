<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RulesetInfo extends Model
{
    protected $table = 'RulesetInfos';
    public $incrementing = false;
    public $timestamps = false;
    protected $primaryKey = 'rulesetId';

    public function Spectrums()
    {
        return $this->hasMany('App\Spectrums', 'rulesetId');
    }

    // public function SpectrumsProfilePoints()
    // {
    //     return $this->hasMany('App\SpectrumProfilePoints', 'rulesetId');
    // }
}
