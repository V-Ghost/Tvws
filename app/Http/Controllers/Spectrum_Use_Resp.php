<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Log;
use App\Spectrums;
use App\DatabaseSpec;
use Datetime;




class Spectrum_Use_Resp extends Controller
{
    
    public function spectrum_Use(Request $request){
        $valid = Validator::make(
            $request->all(),
            [
                'deviceDesc' => 'required',
                'spectra' => 'required',
            ]
        );

        if ($valid->fails()) {
            return response()->json(
                ['201' => $valid->errors()]
            );
        } else {
            date_default_timezone_set('Africa/Accra');

            $startTime = date('Y-m-d H:i:s');

            $spectrum = Spectrums::where('resolutionBwHz', $request['spectra']['resolutionBwHz']) ->update(['created_at' => $startTime]);
            $DatabaseSpec = DatabaseSpec::all();
            return response()->json(
                [
                   'databaseChange'=> $DatabaseSpec
                ]
            );
        }
       
       
    }
}
