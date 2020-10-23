<?php

use Illuminate\Supports\Facades\Auth;

//use App\Models\City;
//use App\Models\Area;
//use App\Models\RoadAndLane;

	//***** This function use for test *****/
	/*function user_email() {
		$user = 'Hello';

		return $user;
	}*/

    //***** Address process *****//
    function AddressProcess($request) {
        /***** Information type validator *****/
        $validator = Validator::make($request->all(), [
            'lane' => ['int', 'nullable'],
            'alley' => ['int', 'nullable'],
            'no' => ['int', 'nullable'],
            'floor' => ['int', 'nullable'],
            'info' => ['string', 'nullable', 'max:255']
        ]);

        if($validator->fails()) {
            $error = array(
                'type_error' => 'In lane, alley, no, floor, please enter integer. Other information enter string'
            );

            return $error;
        }

        /***** Get information from form by $request *****/
        $addressinfo = array();

        $cityid = isset($request['cityid']) ? $request['cityid'] : "";
        $areafilename = isset($request['areafilename']) ? $request['areafilename'] : "";
        $road = isset($request['road']) ? $request['road'] : "";
        $lane = (isset($request['lane']) && !is_null($request['lane'])) ? $request['lane']."巷" : "";
        $alley = (isset($request['alley']) && !is_null($request['alley'])) ? $request['alley']."弄" : "";
        $no = (isset($request['no']) && !is_null($request['no'])) ? $request['no']."號" : "";
        $floor = (isset($request['floor']) && !is_null($request['floor'])) ? $request['floor']."樓" : "";
        $info = (isset($request['info']) && !is_null($request['info'])) ? $request['info'] : "";

        $addressinfo = array(
            'cityid' => $cityid,
            'areafilename' => $areafilename,
            'road' => $road,
            'lane' => $lane,
            'lane_int' => $request['lane'],
            'alley' => $alley,
            'alley_int' => $request['alley'],
            'no' => $no,
            'no_int' => $request['no'],
            'floor' => $floor,
            'floor_int' => $request['floor'],
            'info' => $info
        );

        return $addressinfo;
    }

	//***** Google Geocode API *****//
	function GoogleGeocodeApiProcess($addressintoapi) {
		$googleaddressinfo = array();

    	$set_address = urlencode($addressintoapi);
    	$googlemapurl = "https://maps.googleapis.com/maps/api/geocode/json?address={$set_address}&key=Your_API_KEY&language=zh-TW";
    	$geocoderesponsedata = file_get_contents($googlemapurl);
    	$responsedata = json_decode($geocoderesponsedata, true);

    	if($responsedata['status'] == 'OK') {
    		$account = count($responsedata['results'][0]['address_components']);

            $zip = isset($responsedata['results'][0]['address_components'][$account-1]['long_name']) ? $responsedata['results'][0]['address_components'][$account-1]['long_name'] : "";

    		$latitude = isset($responsedata['results'][0]['geometry']['location']['lat']) ? $responsedata['results'][0]['geometry']['location']['lat'] : "";

    		$longitude = isset($responsedata['results'][0]['geometry']['location']['lng']) ? $responsedata['results'][0]['geometry']['location']['lng'] : "";

    		$full_address = isset($responsedata['results'][0]['formatted_address']) ? $responsedata['results'][0]['formatted_address'] : "";
    	}

    	$googleaddressinfo = array(
    		'zip' => $zip,
    		'latitude' => $latitude,
    		'longitude' => $longitude,
    		'full_address' => $full_address	
    	);

    	return $googleaddressinfo;
	}

    /*function GetAddress($request) {
        /***** Type validator *****
        $validator = Validator::make($request->all(), [
            'lane' => ['int', 'nullable'],
            'alley' => ['int', 'nullable'],
            'no' => ['int', 'nullable'],
            'floor' => ['int', 'nullable'],
            'info' => ['string', 'nullable', 'max:255']
        ]);

        if($validator->fails()) {
            $error = array(
                'type_error' => 'In lane, alley, no, floor, please enter integer. Other information enter string'
            );

            return response()->json([
                'type_error' => $getaddressinfo['type_error']
            ]);
        }

        /***** Get information from form by $request *****
        $cityid = isset($request['cityid']) ? $request['cityid'] : "";
        $areafilename = isset($request['areafilename']) ? $request['areafilename'] : "";
        $road = isset($request['road']) ? $request['road'] : "";
        $lane = (isset($request['lane']) && !is_null($request['lane'])) ? $request['lane']."巷" : "";
        $alley = (isset($request['alley']) && !is_null($request['alley'])) ? $request['alley']."弄" : "";
        $no = (isset($request['no']) && !is_null($request['no'])) ? $request['no']."號" : "";
        $floor = (isset($request['floor']) && !is_null($request['floor'])) ? $request['floor']."樓" : "";
        $info = (isset($request['info']) && !is_null($request['info'])) ? $request['info'] : "";

        $cityselect =  City::where('id', '=', $cityid)->first();
        $city = $cityselect->city;

        $areaselect = Area::where('filename', '=', $areafilename)->first();
        $area = $areaselect->area;

        $filenameselect = DB::table('areas')->join('cities', 'areas.city_id', '=', 'cities.id')->select('areas.filename')->where('cities.id', '=', $cityid)->where('areas.area', '=', $area)->first();
        $filename = $filenameselect->filename;

        /***** Connect become an address use into Google Geocode API *****
        $addressintoapi = trim($city.$area.$road.$lane.$alley.$no.$floor);

        $set_address = urlencode($addressintoapi);
        $googlemapurl = "https://maps.googleapis.com/maps/api/geocode/json?address={$set_address}&key=Your_API_KEY&language=zh-TW";
        $geocoderesponsedata = file_get_contents($googlemapurl);
        $responsedata = json_decode($geocoderesponsedata, true);

        if($responsedata['status'] == 'OK') {
            $account = count($responsedata['results'][0]['address_components']);

            $zip = isset($responsedata['results'][0]['address_components'][$account-1]['long_name']) ? $responsedata['results'][0]['address_components'][$account-1]['long_name'] : "";

            $latitude = isset($responsedata['results'][0]['geometry']['location']['lat']) ? $responsedata['results'][0]['geometry']['location']['lat'] : "";

            $longitude = isset($responsedata['results'][0]['geometry']['location']['lng']) ? $responsedata['results'][0]['geometry']['location']['lng'] : "";

            $full_address = isset($responsedata['results'][0]['formatted_address']) ? $responsedata['results'][0]['formatted_address'] : "";
        }

        $response_json_address = response()->json([
            'zip' => $zip,
            'city' => $city,
            'area' => $area,
            'road' => $road,
            'lane' => $request['lane'],
            'alley' => $request['alley'],
            'no' => $request['no'],
            'floor' => $request['floor'],
            'address' => $info,
            'filename' => $filename,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'full_address' => $full_address,
        ]);

        return $response_json_address;
    }*/

?>