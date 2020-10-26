<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::get('/person', function () {
//     $person = [
//         'first'=> 'b',
//         'last' => 'vukania', 
//     ];
//     return $person;
// });

Route::post('init', 'init@initialise');

Route::post('insert_rs', 'init@insert');

Route::post('avail_spectrum', 'Avail_Spectrum_Query@avail_spec');

Route::post('spectrum_use', 'Spectrum_Use_Resp@spectrum_Use');

Route::post('device_valid', 'DeviceValidation@dev_valid');

Route::post('log_out', 'Spectrum_Use_Resp@Log_out');

Route::post('device_reg', 'DeviceRegistration@dev_reg');

Route::post('insert_spec', 'Avail_Spectrum_Query@index');

Route::get('spec_all', 'Avail_Spectrum_Query@all');

Route::get('init', 'init@index');



