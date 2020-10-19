<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Log;
use App\Spectrums;
use App\DatabaseSpec;
use App\DeviceDescriptor;
use Datetime;
use App\RulesetInfo;





class Spectrum_Use_Resp extends Controller
{

    public function spectrum_Use(Request $request)
    {
        $valid = Validator::make(
            $request->all(),
            [
                'deviceDesc.manufacturerId' => 'required',
                'deviceDesc.serialNumber' => 'required',
                'spectra' => 'required',
            ]
        );
        $y = json_encode($request['deviceDesc']['serialNumber']);
        $x = json_encode($request['deviceDesc']['manufacturerId']);


        $id = $x . $y;
        Log::info($id);
        $data = DeviceDescriptor::find($id);
        if (empty($data)) {
            Log::info($data);
            return response()->json(
                ['error' => "not resgistered"]
            );
        } else {
            try {
                $ruleset = RulesetInfo::find($request['deviceDesc']['rulesetIDs']);
                if (empty($ruleset)) {
                    return response()->json(
                        ['error' => "not supported"]
                    );
                }
                if ($valid->fails()) {
                    return response()->json(
                        ['201' => $valid->errors()]
                    );
                } else {
                    date_default_timezone_set('Africa/Accra');

                    $startTime = date('Y-m-d H:i:s');
                    foreach($request['spectra'] as $r){
                        $spectrum = Spectrums::where('ID', $r['ID'])->update(['created_at' => $startTime]);
                    }
                   
                    $DatabaseSpec = DatabaseSpec::all();
                    return response()->json(
                        [
                            'databaseChange' => $DatabaseSpec
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
