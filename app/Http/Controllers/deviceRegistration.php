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
use Exception;


class DeviceRegistration extends Controller
{

    public function dev_reg(Request $request)
    {
        if (empty($request['key'])) {
            return response()->json(
                [
                    "201" => "The key field is required"

                ]
            );
        }
        $key = json_encode($request['key'], JSON_FORCE_OBJECT);

        if (strcmp("master", $request['key']) == 0) {


            $valid = Validator::make(
                $request->all(),
                [
                    'rulesetIDs' => 'required',
                    'deviceDesc.modelId' => 'required',
                    'deviceDesc.manufacturerId' => 'required',
                    'deviceDesc.serialNumber' => 'required',
                    'deviceDesc.latitude' => 'required',
                    'deviceDesc.longitude' => 'required',
                    'deviceDesc.transmitter_power' => 'required',
                    'deviceDesc.username' => 'required',
                    'deviceDesc.district' => 'required',
                    'deviceDesc.operator' => 'required',
                    'deviceDesc.region' => 'required',
                    'deviceDesc.radiatedpower' => 'required',
                    'deviceDesc.conductedpower' => 'required',
                    'deviceDesc.antennaheight' => 'required',
                    'deviceDesc.antennaheighttype' => 'required',
                    'key' => 'required',


                ]
            );
        } elseif (strcmp("client", $request['key']) == 0) {

            $valid = Validator::make(
                $request->all(),
                [
                    'rulesetIDs' => 'required',
                    'deviceDesc.modelId' => 'required',
                    'deviceDesc.manufacturerId' => 'required',
                    'deviceDesc.serialNumber' => 'required',
                    'deviceDesc.latitude' => 'required',
                    'deviceDesc.longitude' => 'required',
                    
                    'deviceDesc.username' => 'required',
                    'deviceDesc.district' => 'required',
                    'deviceDesc.operator' => 'required',
                    'deviceDesc.region' => 'required',
                   
                    'deviceDesc.antennaheight' => 'required',
                    'deviceDesc.antennaheighttype' => 'required',
                    'deviceDesc.deviceType' => 'required',
                    'deviceDesc.phoneNumber' => 'required',
                    'key' => 'required',


                ]
            );
        } else {
            return response()->json(
                [
                    "201" => "The key field is incorrect"

                ]
            );
        }
        if ($valid->fails()) {
            return response()->json(
                ['201' => $valid->errors()]
            );
        } else {

            try {
                $ruleset = RulesetInfo::all();
                $array = array();
                $notSupported = true;
                foreach ($ruleset as $r) {
                    $y = json_encode($r['rulesetId']);
                    foreach ($request['rulesetIDs'] as $x) {
                        $z = json_encode($x['rulesetId']);

                        if ($y == $z) {

                            array_push($array, $r);

                            $notSupported = false;
                            break;
                        }
                    }
                }
                if ($notSupported) {
                    $error = [
                        '102' => 'NOT_SUPPORTED',

                    ];
                    return $error;
                }

                $lat = json_encode($request['deviceDesc']['latitude'], JSON_NUMERIC_CHECK);
                $long = json_encode($request['deviceDesc']['longitude'], JSON_NUMERIC_CHECK);
                $s = new DistanceCalculator; //add try and catch
                $f = $s->distance(5.655921, -0.182405, $lat, $long);


                if ($f > 500) {
                    $error = [
                        '104' => 'OUTSIDE_COVERAGE_AREA',

                    ];
                    return $error;
                }
                $y = json_encode($request['deviceDesc']['serialNumber']);
                $x = json_encode($request['deviceDesc']['manufacturerId']);

                $password =  bin2hex(random_bytes(3));
                
                if (strcmp("client", $request['key']) == 0) {
                    $id = bin2hex(random_bytes(3));
                    $data = DeviceDescriptorClient::find($id);
                    while (!empty($data)) {
                        $id = bin2hex(random_bytes(3));
                        $data = DeviceDescriptorClient::find($id);
                    }

                    
                    $device = new DeviceDescriptorClient;
                    $device->serialNumber = $request['deviceDesc']['serialNumber'];
                    $device->manufacturerId = $request['deviceDesc']['manufacturerId'];
                    $device->modelId = $request['deviceDesc']['modelId'];
                    $device->latitude = $request['deviceDesc']['latitude'];
                    $device->longitude = $request['deviceDesc']['longitude'];
                    $device->username = $request['deviceDesc']['longitude'];
                    $device->region = $request['deviceDesc']['region'];
                    $device->district = $request['deviceDesc']['district'];
                    $device->deviceType = $request['deviceDesc']['deviceType'];
                    $device->phoneNumber = $request['deviceDesc']['phoneNumber'];
                    $device->operator = $request['deviceDesc']['operator'];
                   
                    $device->antennaheight = $request['deviceDesc']['antennaheight'];
                    $device->antennaheighttype = $request['deviceDesc']['antennaheighttype'];
                    $device->password = Hash::make($password);
                    $device->deviceId = $id;
                    $DatabaseSpec = DatabaseSpec::all();
                    $spectrum = Spectrums::all();
                   
                    $now = new DateTime();
                    date_default_timezone_set('Africa/Accra');

                     $startTime = date('Y-m-d H:i:s');
                    $s = strtotime("+24 hours", strtotime($startTime));
                    $oneDayAgo = strtotime("-2 minutes", strtotime($startTime));
                    $array = array();
                    $lat = json_encode($request['deviceDesc']['latitude'], JSON_NUMERIC_CHECK);
                    $long = json_encode($request['deviceDesc']['longitude'], JSON_NUMERIC_CHECK);
                    $distance = new DistanceCalculator;
                    foreach($spectrum as $r){
                       
                        if ($r["created_at"] < date('Y-m-d H:i:s', $oneDayAgo)) {
                           
                            $f = $distance->distance($r["Transmit_lat"], $r["Transmit_long"], $lat, $long);
                           
                            if($f > $r["Transmit_distance"]){
                               
                               array_push($array,$r);
                            }
                        } 
                        
                    }
                    if ($device->save()) {
                        return response()->json(
                            [
                                $request['rulesetIDs'],
                                "username" => $id,
                                "password" => $password,
                                'databaseChange' => $DatabaseSpec,
                                "avaliable Spectrums" => sizeof($array)
                            ]
                        );
                    }
                } elseif (strcmp("master", $request['key']) == 0) {
                    $id = bin2hex(random_bytes(3));
                    $data = DeviceDescriptor::find($id);
                    while (!empty($data)) {
                        $id = bin2hex(random_bytes(3));
                        $data = DeviceDescriptor::find($id);
                    }
                    
                    $device = new DeviceDescriptor;
                    $device->serialNumber = $request['deviceDesc']['serialNumber'];
                    $device->manufacturerId = $request['deviceDesc']['manufacturerId'];
                    $device->modelId = $request['deviceDesc']['modelId'];
                    $device->latitude = $request['deviceDesc']['latitude'];
                    $device->longitude = $request['deviceDesc']['longitude'];
                    $device->username = $request['deviceDesc']['longitude'];
                    $device->region = $request['deviceDesc']['region'];
                    $device->transmitter_power = $request['deviceDesc']['transmitter_power'];
                    $device->district = $request['deviceDesc']['district'];
                    $device->operator = $request['deviceDesc']['operator'];
                    $device->radiatedpower = $request['deviceDesc']['radiatedpower'];
                    $device->conductedpower = $request['deviceDesc']['conductedpower'];
                    $device->antennaheight = $request['deviceDesc']['antennaheight'];
                    $device->antennaheighttype = $request['deviceDesc']['antennaheighttype'];
                    $device->password = Hash::make($password);
                    $device->deviceId = $id;
                   
                    $spectrum = Spectrums::all();
                   
                    $now = new DateTime();
                    date_default_timezone_set('Africa/Accra');

                     $startTime = date('Y-m-d H:i:s');
                    $s = strtotime("+24 hours", strtotime($startTime));
                    $oneDayAgo = strtotime("-2 minutes", strtotime($startTime));
                    $array = array();
                    $lat = json_encode($request['deviceDesc']['latitude'], JSON_NUMERIC_CHECK);
                    $long = json_encode($request['deviceDesc']['longitude'], JSON_NUMERIC_CHECK);
                    $distance = new DistanceCalculator;
                    foreach($spectrum as $r){
                       
                        if ($r["created_at"] < date('Y-m-d H:i:s', $oneDayAgo)) {
                           
                            $f = $distance->distance($r["Transmit_lat"], $r["Transmit_long"], $lat, $long);
                           
                            if($f > $r["Transmit_distance"]){
                               
                               array_push($array,$r);
                            }
                        } 
                        
                    }
                   
                    
                    $DatabaseSpec = DatabaseSpec::all();
                    if ($device->save()) {
                        return response()->json(
                            [
                                $request['rulesetIDs'],
                                "username" => $id,
                                "password" => $password,
                                'databaseChange' => $DatabaseSpec,
                                "avaliable Spectrums" => sizeof($array)
                            ]
                        );
                    }
                } else {
                    return response()->json(
                        [
                            "201" => "The key field is required"

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
