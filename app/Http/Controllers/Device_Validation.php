<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Device_Validation extends Controller
{
    public function device_validation(Request $request){
      
            $valid = Validator::make(
                $request->all(),
                [
                    'deviceValidities' => 'required',
                    
                ]
            );
            if ($valid->fails()){
                return response()->json(
                    ['201' => $valid->errors()]
                );
            }else{
                
            }
    }
}
