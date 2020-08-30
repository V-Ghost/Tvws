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

class Avail_Spectrum_Query extends Controller
{
    //  function distance($lat1, $lon1, $lat2, $lon2) { 
    //     $pi80 = M_PI / 180; 
    //     $lat1 *= $pi80; 
    //     $lon1 *= $pi80; 
    //     $lat2 *= $pi80; 
    //     $lon2 *= $pi80; 
    //     $r = 6372.797; // mean radius of Earth in km 
    //     $dlat = $lat2 - $lat1; 
    //     $dlon = $lon2 - $lon1; 
    //     $a = sin($dlat / 2) * sin($dlat / 2) + cos($lat1) * cos($lat2) * sin($dlon / 2) * sin($dlon / 2); 
    //     $c = 2 * atan2(sqrt($a), sqrt(1 - $a)); 
    //     $km = $r * $c; 
    //     //echo ' '.$km; 
    //     return $km; 
    //     }
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
                            ['error' => "no spectrums avaliable"]
                        );
                    }
                    $ruleset = RulesetInfo::find($request['deviceDesc']['rulesetIDs'])->Spectrums;
                    $spectrum = Spectrums::find($ruleset[0]["id"]);
                    $spectrumProfiles = SpectrumProfilePoints::where('Spectrums_id', $ruleset[0]["id"])->get();
                    Log::info($ruleset[0]["id"]);
                    
                    $now = new DateTime();
                     date_default_timezone_set('Africa/Accra');
                     $c = date('Y-m-d H:i:s');
                //    $now = new DateTime(null, new \DateTimeZone('America/New_York'));
                //    $now->format('Y-m-d H:i:s');
                    Log::info($c);
                    return response()->json(
                        [
                            'rulesetInfo' => $request['deviceDesc']['rulesetIDs'],
                            'spectrumSchedules' => [
                                "eventTime" => [
                                    "startTime" => "",
                                    "stopTime" => ""
                                ],
                                "Spectrum" => [
                                    "resolutionBwHz" => $spectrum->resolutionBwHz,
                                    "profiles" => $spectrumProfiles
                                ],

                            ],



                        ]
                    );
                }
            }
        }
    }
}
