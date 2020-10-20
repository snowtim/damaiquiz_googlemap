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
        /***** Join two tables and select data(right use, for test) *****/
        /*$cityidselect = DB::table('cities')->select('id')->where('city', '=', '基隆市')->first();
        $cityid = $cityidselect->id;
        $filenameselect = DB::table('areas')->join('cities', 'areas.city_id', '=', 'cities.id')->select('areas.filename')->where('cities.id', '=', 1)->where('areas.area', '=', '中山區')->first();
        $filename = $filenameselect->filename;
        dd($filename);*/

        /***** Catch city id use in table areas (Using for test) *****/
        //$cityselect = DB::table('cities')->select('id')->where('city', '=', '桃園市')->first();
        //dd($cityselect);
        //$areas = Area::where('city_id', '=', $cityselect->id)->get();
        //dd($areas);
        //$response_areas = response()->json($areas);
        /*$response_areas = response()->json([
            'a' => 'a'
        ]);*/
        //dd($response_areas);

        $cities = City::all();
        $areas = Area::all();

    	return view('googlemap', compact('cities', 'areas'));
    }

    //***** Get city data from front-end and return areas data(json) *****//
    public function citylinkarea(Request $request) {
        $cityselect = DB::table('cities')->select('id')->where('city', '=', $request['city'])->first();
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

        /***** Google Map API *****/
    	$set_address = urlencode($addressintoapi);
    	//$googlemapurl = "https://maps.googleapis.com/maps/api/geocode/json?address={$set_address}&key=Your_API_KEY&language=zh-TW";
    	$geocoderesponsedata = file_get_contents($googlemapurl);
    	$responsedata = json_decode($geocoderesponsedata, true);

    	if($responsedata['status'] == 'OK') {
    		$account = count($responsedata['results'][0]['address_components']);

            $zip = isset($responsedata['result'][0]['address_components'][$account-2]['long_name']) ? 
            $responsedata['result'][0]['address_components'][$account-2]['long_name'] : "";

    		$latitude = isset($responsedata['results'][0]['geometry']['location']['lat']) ? 
    		$responsedata['results'][0]['geometry']['location']['lat'] : "";

    		$longitude = isset($responsedata['results'][0]['geometry']['location']['lng']) ? 
    		$responsedata['results'][0]['geometry']['location']['lng'] : "";

    		$full_address = isset($responsedata['results'][0]['formatted_address']) ? 
    		$responsedata['results'][0]['formatted_address'] : "";
    	}

        /***** Return datatype by json *****/
    	$response_json = response()->json([
            'zip' => $zip,
            'city' => $city,
            'area' => $area,
            'road' => $road,
            'lane' => $lane,
            'alley' => $alley,
            'no' => $no,
            'floor' => $floor,
            'address' => $info,
            'filename' => $filename,
    		'latitude' => $latitude,
    		'longitude' => $longitude,
    		'address' => $full_address,
    	]);	

    	//dd($response_json);	

    	return view('googlemap', compact('response_json'));
    }
}
