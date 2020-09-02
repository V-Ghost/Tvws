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

class Avail_Spectrum_Query extends Controller
{

    public function index()
    { }



    public function avail_spec(Request $request)
    {

        if (empty($request['requestType'])) {
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
                // $modelId = json_encode();
                // $data = DeviceDescriptor::all();
                $data = DeviceDescriptor::find($request['deviceDesc']['modelId']);


                if (empty($data) || $data == "") {
                    Log::info($data);
                    return response()->json(
                        ['error' => "not resgistered"]
                    );
                } else {
                    $ruleset = RulesetInfo::find($request['deviceDesc']['rulesetIDs']);
                    
                    if (empty($ruleset)) {
                        return response()->json(
                            ['error' => "not supported"]
                        );
                    }
                    $ruleset = RulesetInfo::find($request['deviceDesc']['rulesetIDs'])->Spectrums;


                    $spectrum = Spectrums::find($ruleset[0]["id"]);

                    $spectrumProfiles = SpectrumProfilePoints::where('Spectrums_id', $spectrum["id"])->get();


                    $now = new DateTime();
                    date_default_timezone_set('Africa/Accra');

                    $startTime = date('Y-m-d H:i:s');

                    $s = strtotime("+24 hours", strtotime($startTime));
                    $oneDayAgo = strtotime("-24 hours", strtotime($startTime));
                   
                    $DatabaseSpec = DatabaseSpec::all();
                    if ($spectrum["created_at"] >date('Y-m-d H:i:s', $oneDayAgo)) {
                        return response()->json(
                            [
                                'error' => 'no avaliable spectrums'
                            ]
                        );
                    } else {
                        $stopTime = date('Y-m-d H:i:s', $s);
                       
                        return response()->json(
                            [
                                'timestamp' =>  $startTime,
                                'deviceDesc' => $request['deviceDesc'],
                                'spectrumSpecs' => [
                                    'rulesetInfo' => $request['deviceDesc']['rulesetIDs'],
                                    'spectrumSchedules' => [
                                        "eventTime" => [
                                            "startTime" => $startTime,
                                            "stopTime" => $stopTime
                                        ],
                                        "Spectrum" => [
                                            "resolutionBwHz" => $spectrum->resolutionBwHz,
                                            "profiles" => $spectrumProfiles
                                        ],

                                    ],
                                ],
                                'databaseChange'=> $DatabaseSpec,
                            ]
                        );
                       
                    }
                }
            }
        }
    }
}
