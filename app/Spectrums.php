<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Spectrums extends Model
{

    public $incrementing = false;
    public $timestamps = false;
    protected $table = 'Spectrums';
    public function SpectrumProfilePoints()
    {
        return $this->hasMany('App\SpectrumProfilePoints','Spectrums_id');
    }
}
