<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeviceDescriptor extends Model
{
    protected $table = 'DeviceDescriptor-Master';
    public $incrementing = false;
    public $timestamps = false;
    protected $primaryKey = 'deviceId';
    protected $keyType = 'string';
}
