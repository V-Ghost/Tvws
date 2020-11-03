<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\RulesetInfo;
use App\Library\DistanceCalculator;
use App\Http\Resources\RulesetInfoCollection as RulesetInfoCollection;
use Validator;
use App\DeviceDescriptorClient;
 use App\DatabaseSpec;
use App\Http\Resources\DatabaseSpecCollection as DatabaseSpecCollection;
use Exception;

class init extends Controller
{
    public function index()
    {
        $ruleset = RulesetInfo::all();
        
        
        return new RulesetInfoCollection($ruleset);
    }

    public function insert(Request $request)
    {
        $valid = Validator::make(
            $request->all(),
            [
                'authority' => 'required',
                'rulesetId' => 'required',

            ]
        );

        if ($valid->fails()) {
            return response()->json(
                ['201' => $valid->errors()]
            );
        } else {
            $r = new RulesetInfo();
            $r->authority = $request['authority'];
            $r->rulesetId = $request['rulesetId'];
            if ($r->save()) {
                return response()->json(
                    [

                        'Status' => "Done",
                    ]
                );
            }
        }
    }
    public function initialise(Request $request)
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
            try {
                $lat = json_encode($request['location']['point']['center']['latitude'], JSON_NUMERIC_CHECK);
                $long = json_encode($request['location']['point']['center']['longitude'], JSON_NUMERIC_CHECK);
                $s = new DistanceCalculator;
                try {
                    
                    $f = $s->distance( 7.959665, -1.198714, $lat, $long);
                } catch (Exception $e) {
                    return response()->json(
                        [
                            'Error' => 'Lant',

                        ]
                    );
                }



                if ($f > 385) {
                    $error = [
                        '104' => 'OUTSIDE_COVERAGE_AREA',

                    ];
                    return $error;
                }
                // switch($f){
                // case :
                // };
                $ruleset = RulesetInfo::all();
                if (empty($request['deviceDesc']['rulesetIDs'])) {
                    return   $error = [
                        '104' => 'OUTSIDE_COVERAGE_AREA',

                    ];
                } else {
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
                    $DatabaseSpec = DatabaseSpec::all();



                    return response()->json(
                        [

                            'rulesetInfos' => $array,
                            'databaseChange' => $DatabaseSpec,
                        ]
                    );
                }
            } catch (Exception $e) {
                Log::alert($e->getMessage());

                return response()->json(
                    $e
                );
            }
        }
    }
}
