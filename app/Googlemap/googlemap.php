<?php

use Illuminate\Supports\Facades\Auth;

	//***** This function use for test *****/
	/*function user_email() {
		$user = 'Hello';

		return $user;
	}*/

	//***** Google Geocode API *****//
	function GoogleGeocodeApiProcess($addressintoapi) {
		$googleaddressinfo = array();

    	$set_address = urlencode($addressintoapi);
    	//$googlemapurl = "https://maps.googleapis.com/maps/api/geocode/json?address={$set_address}&key=Your_API_KEY&language=zh-TW";
    	$geocoderesponsedata = file_get_contents($googlemapurl);
    	$responsedata = json_decode($geocoderesponsedata, true);

    	if($responsedata['status'] == 'OK') {
    		$account = count($responsedata['results'][0]['address_components']);

            $zip = isset($responsedata['result'][0]['address_components'][$account-2]['long_name']) ? $responsedata['result'][0]['address_components'][$account-2]['long_name'] : "";

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

?>