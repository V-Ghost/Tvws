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

class deviceRegistration extends Controller
{

    public function dev_reg(Request $request)
    {
        $valid = Validator::make(
            $request->all(),
            [
                'deviceDesc' => 'required',
                'location' => 'required',
            ]
        );
        if ($valid->fails()) {
            return response()->json(
                ['201' => $valid->errors()]
            );
        } else {
            $ruleset = RulesetInfo::all();
            $array = array();
            $notSupported = true;
            foreach ($ruleset as $r) {
                $y = json_encode($r['rulesetId']);
                foreach ($request['deviceDesc']['rulesetIDs'] as $x) {
                    $z = json_encode($x['rulesetId']);

                    if ($y == $z) {

                        array_push($array, $r);

                        $notSupported = false;
                        break;
                    }
                }
            }
            if ($notSupported) {
                $error = [
                    '102' => 'NOT_SUPPORTED',

                ];
                return $error;
            }

            $lat = json_encode($request['location']['point']['center']['latitude'], JSON_NUMERIC_CHECK);
            $long = json_encode($request['location']['point']['center']['longitude'], JSON_NUMERIC_CHECK);
            $s = new DistanceCalculator;
            $f = $s->distance(5.655921, -0.182405, $lat, $long);
            
            
            if ($f > 50) {
                $error = [
                    '104' => 'OUTSIDE_COVERAGE_AREA',

                ];
                return $error;
            }
           $device = new DeviceDescriptor();
           $device->serialNumber = $request['deviceDesc']['serialNumber'];
           $device->manufacturerId = $request['deviceDesc']['manufacturerId'];
           $device->modelId = $request['deviceDesc']['modelId'];
           $device->latitude = $request['deviceDesc']['latitude'];
           $device->longitude = $request['deviceDesc']['longitude'];
           $DatabaseSpec = DatabaseSpec::all();

          if( $device->save()){
            return response()->json(
                [
                 $request['deviceDesc']['rulesetIDs'],
                 'databaseChange'=> $DatabaseSpec,
                ]
            );
          }

        }
    }
   
}
