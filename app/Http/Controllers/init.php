<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\RulesetInfo;
use App\Http\Resources\RulesetInfoCollection as RulesetInfoCollection;
use Validator;
use App\DatabaseSpec;
use App\Http\Resources\DatabaseSpecCollection as DatabaseSpecCollection;


class init extends Controller
{
    public function index()
    {
        $ruleset = RulesetInfo::all();
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
                ['100' => $valid->errors()]
            );
        } else {
            $f = json_encode($request['location'], JSON_NUMERIC_CHECK);
            if ($f > 100) {
                $error = [
                    'ERROR' => 'OUTSIDE_COVERAGE_AREA',

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
                        'ERROR' => 'NOT_SUPPORTED',

                    ];
                    return $error;
                }



                return new RulesetInfoCollection($array);
                // return json_encode($array);
            }
        }
    }
}
