<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Validator;
use App\Library\DistanceCalculator;
use App\DeviceDescriptor;
use Illuminate\Support\Facades\Log;
use Datetime;
use App\Http\Resources\Spectrums as SpectrumResource;
use App\RulesetInfo;
use App\Spectrums;
use App\SpectrumProfilePoints;
use App\DatabaseSpec;

class deviceValidation extends Controller
{
    
    public function dev_valid(Request $request)
    {
        $valid = Validator::make(
            $request->all(),
            [
                'deviceValidities' => 'required',
               
            ]
        );
        if ($valid->fails()) {
            return response()->json(
                ['201' => $valid->errors()]
            );
        } else {                          
            $data = DeviceDescriptor::find($request['deviceValidities']['deviceDesc']['modelId']);
            
            if (empty($data) || $data == "") {
                Log::info($data);
                return response()->json(
                    [   $request['deviceValidities'],
                        'isValid' => "False"
                    
                    ]
                );
            } else {
                return response()->json(
                    [   $request['deviceValidities'],
                        'isValid' => "True"
                    
                    ]
                );
            }
        }    
    }
}
