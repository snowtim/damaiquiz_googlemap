<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use App\Models\Address;
use App\Models\City;
use App\Models\Area;
use App\Models\RoadAndLane;

class AddressController extends Controller
{
    //***** Direct to /googlemap *****//
    public function index() {
        $cities = City::all();

    	return view('googlemap', compact('cities'));
    }

    //***** Get city data from front-end and return areas data(json) *****//
    public function citylinkarea(Request $request) {
        if(!isset($request['cityid'])) {
            return $response_areas = response()->json([
                'error' => 'Has no value!'
            ]);
        }

        $areas = Area::where('city_id', '=', $request['cityid'])->get();
        $response_areas = response()->json($areas);

        return $response_areas;
    }

    //***** Get area data from front-end and return routes(roads) data(json) *****//
    public function arealinkroad(Request $request) {
        if(!isset($request['areafilename'])) {
            return $response_routes = response()->json([
                'error' => 'Has no value!'
            ]);
        }

        $roads = RoadAndLane::where('filename_id', '=', $request['areafilename'])->get();
        $response_roads = response()->json($roads);

        return $response_roads;
    }

    //***** Get address information and use Google Geocode API, and return data(json) *****//
    public function getaddress(Request $request) {
        /***** Get information of address *****/
        $getaddressinfo = AddressProcess($request);

        if(isset($getaddressinfo['type_error'])) {
            return response()->json([
                'type_error' => $getaddressinfo['type_error']
            ]);
        }

        $cityselect =  City::where('id', '=', $getaddressinfo['cityid'])->first();
        $city = $cityselect->city;

        $areaselect = Area::where('filename', '=', $getaddressinfo['areafilename'])->first();
        $area = $areaselect->area;

        $filenameselect = DB::table('areas')->join('cities', 'areas.city_id', '=', 'cities.id')->select('areas.filename')->where('cities.id', '=', $getaddressinfo['cityid'])->where('areas.area', '=', $area)->first();
        $filename = $filenameselect->filename;

        /***** Connect become an address use into Google Geocode API *****/
        $addressintoapi = trim($city.$area.$getaddressinfo['road'].$getaddressinfo['lane'].$getaddressinfo['alley'].$getaddressinfo['no'].$getaddressinfo['floor']);

        /***** Library about using Google Geocode API *****/
        $googleaddressinfo = GoogleGeocodeApiProcess($addressintoapi);

        /***** Return datatype by json *****/
        $response_json_address = response()->json([
            'zip' => $googleaddressinfo['zip'],
            'city' => $city,
            'area' => $area,
            'road' => $getaddressinfo['road'],
            'lane' => $getaddressinfo['lane_int'],
            'alley' => $getaddressinfo['alley_int'],
            'no' => $getaddressinfo['no_int'],
            'floor' => $getaddressinfo['floor_int'],
            'address' => $getaddressinfo['info'],
            'filename' => $filename,
            'latitude' => $googleaddressinfo['latitude'],
            'longitude' => $googleaddressinfo['longitude'],
            'full_address' => $googleaddressinfo['full_address'],
        ]);

    	return $response_json_address;
    }
}
