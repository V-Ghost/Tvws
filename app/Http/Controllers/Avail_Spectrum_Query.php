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

class Avail_Spectrum_Query extends Controller
{

    public function index(Request $request)
    {
    
     $valid = Validator::make(
        $request->all(),
        [
            'ID' => 'required',
            'Transmitter_Name' => 'required',
            'Region' => 'required',
            'RF_channel' => 'required',
            'TV_Band_Number' => 'required',
            'Transmit_lat' => 'required',
            'Transmit_long' => 'required',
            'Channel_BW' => 'required',
            'Lower_Frequency' => 'required',
            'Center_Frequency' => 'required',
            'Upper_Frequency' => 'required',
            'Transmit_Power_kW' => 'required',
            'Antenna_height' => 'required',
            'Transmit_distance' => 'required',
           ]
    );
    if ($valid->fails()) {
        return response()->json(
            ['201' => $valid->errors()]
        );
    } else {
        $spectrum = new Spectrums();
        $spectrum->ID = $request['ID'];
        $spectrum->Transmitter_Name = $request['Transmitter_Name']; 
        $spectrum->Region = $request['Region']; 
        $spectrum->RF_channel = $request['RF_channel']; 
        $spectrum->TV_Band_Number = $request['TV_Band_Number']; 
        $spectrum->Transmit_lat = $request['Transmit_lat']; 
        $spectrum->Transmit_long = $request['Transmit_long']; 
        $spectrum->Channel_BW = $request['Channel_BW']; 
        $spectrum->Lower_Frequency = $request['Lower_Frequency']; 
        $spectrum->Center_Frequency = $request['Center_Frequency']; 
        $spectrum->Upper_Frequency = $request['Upper_Frequency']; 
        $spectrum->Transmit_Power_kW = $request['Transmit_Power_kW']; 
        $spectrum->Antenna_height = $request['Antenna_height']; 
        $spectrum->Transmit_distance = $request['Transmit_distance']; 
        if ($spectrum->save()) {
            return response()->json(
                [
                   
                    'Status' => "Done",
                ]
            );
        }
    }
}




    public function avail_spec(Request $request)
    {

       
            $valid = Validator::make(
                $request->all(),
                [
                    'deviceDesc' => 'required',
                    'location.latitude' => 'required',
                    'location.longitude' => 'required',
                    'deviceDesc.manufacturerId' => 'required',
                    'deviceDesc.serialNumber' => 'required',
                ]
            );
            if ($valid->fails()) {
                return response()->json(
                    ['201' => $valid->errors()]
                );
            } else {
                try{
                    $y = json_encode($request['deviceDesc']['serialNumber']);
                    $x = json_encode($request['deviceDesc']['manufacturerId']);
                    
                   
                    $id = $x . $y;
                    $data = DeviceDescriptor::find($id);


                    if (empty($data)) {
                        
                        return response()->json(
                            ['error' => "not resgistered"]
                        );
                    } else {
                        $ruleset = RulesetInfo::find($request['deviceDesc']['rulesetIDs']);
                        
                        if (empty($ruleset)) {
                            return response()->json(
                                ['error' => "not supported"]
                            );
                        }
                       
    
    
                        $spectrum = Spectrums::all();
    
                       $now = new DateTime();
                        date_default_timezone_set('Africa/Accra');
    
                        $startTime = date('Y-m-d H:i:s');
    
                        $s = strtotime("+24 hours", strtotime($startTime));
                        $oneDayAgo = strtotime("-2 minutes", strtotime($startTime));
                       
                        $DatabaseSpec = DatabaseSpec::all();
                        $array = array();
                        $lat = json_encode($request['location']['latitude'], JSON_NUMERIC_CHECK);
                        $long = json_encode($request['location']['longitude'], JSON_NUMERIC_CHECK);
                        $distance = new DistanceCalculator;
                        foreach($spectrum as $r){
                            Log::info($r);
                            if ($r["created_at"] < date('Y-m-d H:i:s', $oneDayAgo)) {
                                Log::info('Date check');
                                Log::info($r["created_at"]);
                                Log::info($oneDayAgo);
                                $f = $distance->distance($r["Transmit_lat"], $r["Transmit_long"], $lat, $long);
                                Log::info($f);
                                if($f < $r["Transmit_distance"]){
                                    Log::info('distance check');
                                   array_push($array,$r);
                                }
                            } 
                            
                        }
                        if (empty($array)) {
                            return response()->json(
                                [
                                    'error' => 'no avaliable spectrums'
                                ]
                            );
                        } else {
                           
                            $stopTime = date('Y-m-d H:i:s', $s);
                           
                            return response()->json(
                                [
                                    'timestamp' =>  $startTime,
                                    'deviceDesc' => $request['deviceDesc'],
                                    'spectrumSpecs' => [
                                        'rulesetInfo' => $request['deviceDesc']['rulesetIDs'],
                                        'spectrumSchedules' => [
                                            "eventTime" => [
                                                "startTime" => $startTime,
                                                "stopTime" => $stopTime
                                            ],
                                            "Spectrum" => $array,
    
                                        ],
                                    ],
                                    'databaseChange'=> $DatabaseSpec,
                                ]
                            );
                           
                        }
                    }
                }catch (Exception $e) {
                    Log::alert($e->getMessage());
                   
                    return response()->json(
                        $e
                    );
                   
                }  
                
               
            
        }
    }
}
