<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Spectrums extends Model
{

    
    // public $timestamps = false;
    // protected $primaryKey = 'ID';
    protected $table = 'Spectrums';
    public function setUpdatedAtAttribute($value)
{
    // to Disable updated_at
}
const UPDATED_AT = null;
    
}
