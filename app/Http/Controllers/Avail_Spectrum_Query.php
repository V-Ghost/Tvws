<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\DeviceDescriptor;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\DeviceDescriptorCollection as DeviceDescriptorCollection;
use App\RulesetInfo;
use App\Spectrums;

class Avail_Spectrum_Query extends Controller
{

    public function index()
    { 

    }



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
            }else{
                // $modelId = json_encode();
                // $data = DeviceDescriptor::all();
                $data = DeviceDescriptor::find($request['deviceDesc']['modelId']);

               
                 if(empty($data) || $data == ""){
                    Log::info($data);
                     return response()->json(
                         ['error' => "not resgistered"]
                     );
                 }else{
                    $ruleset = RulesetInfo::find($request['deviceDesc']['rulesetIDs']);
                    if(empty($ruleset)){
                       return response()->json(
                         ['error' => "no spectrums avaliable"]
                     );
                    }
                    $ruleset = RulesetInfo::find($request['deviceDesc']['rulesetIDs'])->Spectrums;
                    $r = Spectrums::find($ruleset[0]["id"])->SpectrumsProfilePoints;
                    Log::info($ruleset[0]["id"]);
                    Log::info($r);
                    return response()->json(
                        ['rulesetInfo' => $request['deviceDesc']['rulesetIDs'],
                        'spectrumSchedules' => [$ruleset,
                        $r
                        ]
                        ]
                    );
                 }
                
            }
        }
      
       
    }
}
