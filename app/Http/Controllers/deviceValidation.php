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
               

                $password = json_encode($request['deviceDesc']['password']);
               
                if (strcmp("master", $request['key']) == 0) {
                  

                    $data = DeviceDescriptor::find($request['deviceDesc']['username']);

                    if (empty($data) )
                    // && !Hash::check($password, $data["password"]))
                     {
                       
                        return response()->json(
                            [

                                'data' => "",
                                'isValid' => "False",
                                'reason' => "username doesnot exist"

                            ]
                        );
                    }
                    if(Hash::check($request['deviceDesc']['password'], $data["password"])){
                        $spectrum = Spectrums::all();
                   
                        $now = new DateTime();
                    date_default_timezone_set('Africa/Accra');

                     $startTime = date('Y-m-d H:i:s');
                    $s = strtotime("+24 hours", strtotime($startTime));
                    $oneDayAgo = strtotime("-2 minutes", strtotime($startTime));
                    $array = array();
                    $lat = json_encode($data['latitude'], JSON_NUMERIC_CHECK);
                    $long = json_encode($data['longitude'], JSON_NUMERIC_CHECK);
                    $distance = new DistanceCalculator;
                    foreach($spectrum as $r){
                       
                        if ($r["created_at"] < date('Y-m-d H:i:s', $oneDayAgo)) {
                           
                            $f = $distance->distance($r["Transmit_lat"], $r["Transmit_long"], $lat, $long);
                           
                            if($f > $r["Transmit_distance"]){
                               
                               array_push($array,$r);
                            }
                        } 
                        
                    }
                        $data["password"] = "*********";
                        return response()->json(
                            [
                                'data' => $data,
                                'isValid' => "True",
                                $array

                            ]
                        );
                    } 
                    else {
                        return response()->json(
                            [

                                'data' => "",
                                'isValid' => "False",
                                'reason' => "wrong password"

                            ]
                        );
                    }
                } elseif (strcmp("client", $request['key']) == 0) {
                   
                    $data = DeviceDescriptorClient::find($request['deviceDesc']['username']);
                    
                    if (empty($data) )
                    // && !Hash::check($password, $data["password"]))
                     {
                       
                        return response()->json(
                            [

                                'data' => "",
                                'isValid' => "False",
                                'reason' => "username doesnot exist"

                            ]
                        );
                    }
                    if(Hash::check($request['deviceDesc']['password'], $data["password"])){
                        $spectrum = Spectrums::all();
                   
                        $now = new DateTime();
                    date_default_timezone_set('Africa/Accra');

                     $startTime = date('Y-m-d H:i:s');
                    $s = strtotime("+24 hours", strtotime($startTime));
                    $oneDayAgo = strtotime("-2 minutes", strtotime($startTime));
                    $array = array();
                    $lat = json_encode($data['latitude'], JSON_NUMERIC_CHECK);
                    $long = json_encode($data['longitude'], JSON_NUMERIC_CHECK);
                    $distance = new DistanceCalculator;
                    foreach($spectrum as $r){
                       
                        if ($r["created_at"] < date('Y-m-d H:i:s', $oneDayAgo)) {
                           
                            $f = $distance->distance($r["Transmit_lat"], $r["Transmit_long"], $lat, $long);
                           
                            if($f > $r["Transmit_distance"]){
                               
                               array_push($array,$r);
                            }
                        } 
                        
                    }
                        $data["password"] = "*********";
                        return response()->json(
                            [
                                'data' => $data,
                                'isValid' => "True",
                                $array

                            ]
                        );
                    } 
                    else {
                        return response()->json(
                            [

                                'data' => "",
                                'isValid' => "False",
                                'reason' => "wrong password"

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
               

                return response()->json(
                    $e
                );
            }
        }
    }
}
