<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Log;

class Spectrum_Registration extends Controller
{
    public function registration(Request $request)
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
            ['201' => $valid->errors()]
        );
    } else {
      return $request;
    }
    }
}
