<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeviceDescriptorClient extends Model
{
    protected $table = 'deviceDescriptor-Client';
    public $incrementing = false;
    public $timestamps = false;
    protected $primaryKey = 'deviceId';
    protected $keyType = 'string';
}
