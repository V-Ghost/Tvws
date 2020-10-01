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

class DeviceValidation extends Controller
{

    public function dev_valid(Request $request)
    {
        $valid = Validator::make(
            $request->all(),
            [
                'key' => 'required',
                'deviceDesc.email' => 'required',
                'deviceDesc.password' => 'required',

            ]
        );
        if ($valid->fails()) {
            return response()->json(
                ['201' => $valid->errors()]
            );
        } else {
            try {
                $y = json_encode($request['deviceDesc']['serialNumber']);
                $x = json_encode($request['deviceDesc']['manufacturerId']);

                $password = json_encode($request['deviceDesc']['password']);

                $id = $x . $y;
                $data = DeviceDescriptor::find($id);

                if (empty($data)) {
                    Log::info($data);
                    return response()->json(
                        [
                            'manufacturerId' => $request['deviceDesc']['manufacturerId'],
                            'serialNumber' => $request['deviceDesc']['serialNumber'],
                            'isValid' => "False"

                        ]
                    );
                } else {
                    return response()->json(
                        [
                           'manufacturerId' => $request['deviceDesc']['manufacturerId'],
                            'serialNumber' => $request['deviceDesc']['serialNumber'],
                            'isValid' => "True"

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
