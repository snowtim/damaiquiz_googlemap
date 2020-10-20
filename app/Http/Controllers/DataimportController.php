<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\City;
use App\Models\Area;
use App\Models\RoadAndLane;

class DataimportController extends Controller
{
    //***** import address data to database *****//
    public function import() {
    	$handle = fopen(public_path('address/address/0/0.json'), 'rb');
    	$read = fread($handle, filesize(public_path('address/address/0/0.json')));
    	$jarray = json_decode($read, true);

    	$city_id = 1;

    	foreach ($jarray as $citynum => $citydata) {
    		City::create([
    			'city' => $citydata['city']	
    		]);

            foreach ($citydata['data'] as $areanum => $areadata) {
            	Area::create([
            		'city_id' => $city_id,
            		'zip' => $areadata['zip'],
            		'filename' => $areadata['filename'],
            		'area' => $areadata['area']
            	]);
                
                if(isset($areadata['filename'])) {
                    $ad = $areadata['filename'];

                    $handle_r = fopen(public_path("address/address/0/$ad".".json"), 'rb');
                    $read_r = fread($handle_r, filesize(public_path("address/address/0/$ad".".json")));

                    $jarray_r = json_decode($read_r, true);

                    foreach ($jarray_r as $roadnum => $roaddata) {
                    	RoadAndLane::create([
                    		'zip_id' => $areadata['zip'],
                    		'filename_id' => $areadata['filename'],
                    		'name' => $roaddata['name'],
                    		'abc' => $roaddata['abc']
                    	]);
                    }
                }
            }

            $city_id++;
    	}
    }	
}
