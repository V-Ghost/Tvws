<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Validator;
use App\Library\DistanceCalculator;
use App\DeviceDescriptor;
use App\DeviceDescriptorClient;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
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
                
                'deviceDesc.password' => 'required',
                'deviceDesc.rulesetIDs' => 'required',
                'deviceDesc.username' => 'required',

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

                if (strcmp("master", $request['key']) == 0) {
                    $id = json_encode($request['deviceDesc']['username']);

                    $data = DeviceDescriptor::find($id);

                    if (empty($data) || !Hash::check($password, $data["password"])) {

                       
                        return response()->json(
                            [

                                'data' => $data,
                                'isValid' => "False"

                            ]
                        );
                    } else {
                        $data["password"] = "*********";
                        return response()->json(
                            [
                                'data' => $data,
                                'isValid' => "True"

                            ]
                        );
                    }
                } elseif (strcmp("client", $request['key']) == 0) {
                    $id = json_encode($request['deviceDesc']['username']);
                    $data = DeviceDescriptorClient::find($id);

                    if (empty($data) || !Hash::check($password, $data["password"])) {
                       
                        return response()->json(
                            [

                                'data' => $data,
                                'isValid' => "False"

                            ]
                        );
                    } else {
                        $data["password"] = "*********";
                        return response()->json(
                            [
                                'data' => $data,
                                'isValid' => "True"

                            ]
                        );
                    }
                } else {
                    return response()->json(
                        [
                            "201" => "The key field is incorrect"

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
