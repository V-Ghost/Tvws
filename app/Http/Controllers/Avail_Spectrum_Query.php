<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\DeviceDescriptor;
use Illuminate\Support\Facades\Log;

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
            }else{
                $modelId = json_encode($request['deviceDesc']['modelId']);
                $data = DeviceDescriptor::find($modelId);
                 if($data == null){
                     Log::info('null');
                 }else{
                    return response()->json(
                        ['modelid' => $data]
                    );
                 }
                
            }
        }
      
       
    }
}
