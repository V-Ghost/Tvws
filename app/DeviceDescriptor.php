<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeviceDescriptor extends Model
{
    protected $table = 'DeviceDescriptor';
    public $incrementing = false;
    public $timestamps = false;
    protected $primaryKey = 'modelId';
    protected $keyType = 'string';
}
