<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\DeviceDescriptor;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\DeviceDescriptorCollection as DeviceDescriptorCollection;

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
                // $modelId = json_encode();
                // $data = DeviceDescriptor::all();
                $data = DeviceDescriptor::find($request['deviceDesc']['modelId']);
                Log::info($data);
                 if(empty($data) || $data == ""){
                     return response()->json(
                         ['error' => "not resgistered"]
                     );
                 }else{
                    // Log::info($data['manufacturerId']);
                   return $data;
                 }
                
            }
        }
      
       
    }
}
