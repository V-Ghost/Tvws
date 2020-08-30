<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\RulesetInfo;
use App\Library\DistanceCalculator;
use App\Http\Resources\RulesetInfoCollection as RulesetInfoCollection;
use Validator;
use App\DatabaseSpec;
use App\Http\Resources\DatabaseSpecCollection as DatabaseSpecCollection;


class init extends Controller
{
    public function index()
    {
        $ruleset = RulesetInfo::all();
        if ($ruleset == null) {
            Log::info('null');
        }
        return new RulesetInfoCollection($ruleset);
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
            $lat = json_encode($request['location']['point']['center']['latitude'], JSON_NUMERIC_CHECK);
            $long = json_encode($request['location']['point']['center']['longitude'], JSON_NUMERIC_CHECK);
            $s = new DistanceCalculator;
            $f = $s->distance(5.657634, -0.066219, $lat, $long);
            
            Log::info($f);
            if ($f > 100) {
                $error = [
                    '104' => 'OUTSIDE_COVERAGE_AREA',

                ];
                return $error;
            }
            $ruleset = RulesetInfo::all();
            if (empty($request['deviceDesc']['rulesetIDs'])) {
                return new RulesetInfoCollection($ruleset);
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



                return new RulesetInfoCollection($array);
            }
        }
    }
}
