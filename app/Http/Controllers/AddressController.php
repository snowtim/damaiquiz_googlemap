<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Address;
use App\Models\City;
use App\Models\Area;
use App\Models\RoadAndLane;

class AddressController extends Controller
{
    //***** Direct to /googlemap *****//
    public function index() {
        $cities = City::all();
        $areas = Area::all();

    	return view('googlemap', compact('cities', 'areas'));
    }

    //***** Get city data from front-end and return areas data(json) *****//
    public function citylinkarea(Request $request) {
        if(!isset($request->City)) {
            return $response_areas = response()->json([
                'error' => 'Has no value!'
            ]);
        }
        
        $cityselect = DB::table('cities')->select('id')->where('city', '=', $request['City'])->first();
        $areas = Area::where('city_id', '=', $cityselect->id)->get();

        /***** Test 1 *****/
        //$areas = Area::where('city_id', '=', 11)->get();

        $response_areas = response()->json($areas);

        /***** Test 2 *****/
        /*$response_areas = response()->json([
            'a' => 'a'
        ]);*/
        //return view('googlemap', compact($response_areas)); 

        return $response_areas;
    }

    //***** Get area data from front-end and return routes(roads) data(json) , not tested and completed  yet. *****//
    public function arealinkroute(Request $request) {
        $areaselect = DB::table('areas')->select('filename')->where('area', '=', $request['area'])->first();
        $routes = RoadAndLane::where('filename_id', '=', 300)->get();
        $response_routes = response()->json($routes);

        return $response_routes;
    }

    //***** Get address information and use Google Geocode API, and return data(json) , not compeleted yet. *****//
    public function GetAddress(Request $request) {
        /***** Get information from form by $request *****/
        $city = isset($request['city']) ? $request['city'] : "";
        $area = isset($request['area']) ? $request['area'] : "";
        $road = isset($request['road']) ? $request['road'] : "";
        $lane = isset($request['lane']) ? $request['lane'] : "";
        $alley = isset($request['alley']) ? $request['alley'] : "";
        $no = isset($request['no']) ? $request['no'] : "";
        $floor = isset($request['floor']) ? $request['floor'] : "";
        $info = isset($request['info']) ? $request['info'] : "";

        $cityidselect = DB::table('cities')->select('id')->where('city', '=', $city)->first();
        $cityid = $cityidselect->id;
        $filenameselect = DB::table('areas')->join('cities', 'areas.city_id', '=', 'cities.id')->select('areas.filename')->where('cities.id', '=', $cityid)->where('areas.area', '=', $area)->first();
        $filename = $filenameselect->filename;

        /***** Connect become an address use into Google Geocode API *****/
        $addressintoapi = trim($city.$area.$road.$lane.$alley.$no.$floor);

        /***** Library about using Google Geocode API *****/
        $googleaddressinfo = GoogleGeocodeApiProcess($addressintoapi);

        /***** Return datatype by json *****/
    	$response_json = response()->json([
            'zip' => $googleaddressinfo['zip'],
            'city' => $city,
            'area' => $area,
            'road' => $road,
            'lane' => $lane,
            'alley' => $alley,
            'no' => $no,
            'floor' => $floor,
            'address' => $info,
            'filename' => $filename,
    		'latitude' => $$googleaddressinfo['latitude'],
    		'longitude' => $googleaddressinfo['longitude'],
    		'full_address' => $googleaddressinfo['full_address'],
    	]);	

    	return $response_json;
    }
}
