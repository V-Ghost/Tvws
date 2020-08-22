<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;

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
                
            }
        }
        return $request;
       
    }
}
