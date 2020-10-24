<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<title>damaiquiz Google Map</title>

	<!-- STYLE -->
	<style>
		#map {
			height: 400px;
			width: 600px;
		}
	</style>

	<!-- SCRIPT -->
  	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>

<body>
	<h1>Google Map Demo</h1>

	<form class="box" method="POST">
		<div class="address">
			<span style="font-size: 0.7em">請選擇縣巿:</span>
			<select id="city" name="cityid">	
					<option>Select</option>
				@foreach($cities as $city)
					<option value="{{ $city->id }}">{{ $city->city }}</option>
				@endforeach
			</select>

			<span style="font-size: 0.7em">請選擇鄉鎮巿區:</span>
			<select id="area" name="areafilename">
					<option>Select</option>
			</select>

			<span style="font-size: 0.7em">請選擇路(街):</span>
			<select id="road" name="road">
					<option>Select</option>
			</select>
		</div>

		<div class="address_la">
			<input id="lane" type="text" name="lane" style="width: 50px">
			<label style="font-size: 0.7em">巷</label>
			<input id="alley" type="text" name="alley" style="width: 50px">
			<label style="font-size: 0.7em">弄</label>
			<input id="no" type="text" name="no" style="width: 50px">
			<label style="font-size: 0.7em">號</label>
			<input id="floor" type="text" name="floor" style="width: 50px">
			<label style="font-size: 0.7em">樓</label>

			<!-- Using for test Google Map API -->
			<!--input type="text" class="address" name="address"-->
		</div>

		<div class="other_info">
			<label style="font-size: 0.7em">其他資訊:</label>
			<input id="info" type="text" name="info">
		</div>

			<!--button id="submit">search</button-->
			<input id="submit" type="button" value="search"> 
	</form>	

	<div id="map"></div>

	<!-- Google Map API -->
	<script>
		var map, geocoder;

		function initMap() {

			geocoder = new google.maps.Geocoder();
			map = new google.maps.Map(document.getElementById('map'), {
				center: {lat: 25.047702, lng: 121.5173735},
				zoom: 15
			});

			$('#submit').click(function() {
				var cityid = $('#city').val();
				var areafilename = $('#area').val();
				var road = $('#road').val();
				var lane = $('#lane').val();
				var alley = $('#alley').val();
				var no = $('#no').val();
				var floor = $('#floor').val();
				var info = $('#info').val();

				$.ajax({
					headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					method: "POST",
					url: "/getaddress",
					data: JSON.stringify({
						cityid: cityid,
						areafilename: areafilename,
						road: road,
						lane: lane,
						alley: alley,
						no: no,
						floor: floor,
						info: info	
					}),
					contentType: "application/json",
					success: function(response_json_address) {
						var full_address = response_json_address.full_address;
						//console.log(full_address);

						if(full_address == undefined) {
							console.log(response_json_address.type_error);
						} else {					
							geocoder.geocode({'address': full_address}, function(result, status) {
								if(status == 'OK') {
									map.setCenter(result[0].geometry.location);

									var marker = new google.maps.Marker({
										map: map,
										position: result[0].geometry.location 
									});
								} else {
									console.log(status);
								}
							});
						}
					}
				});
			});
			
			//var address = '台北車站';

			/*geocoder.geocode({'address': address}, function(result, status) {
				if(status == 'OK') {
					map.setCenter(result[0].geometry.location);
					var marker = new google.maps.Marker({
						map: map,
						position: result[0].geometry.location
					});
				} else {
					console.log(status);
				}
			});*/
		}
	</script>

	<!-- Ajax dropdown list -->
	<script>
		$('#city').change(function() {
			var cityid = $('#city').val();

			$.ajax({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				method: "POST",
				url: "/citylinkarea",
				data: JSON.stringify({cityid: cityid}),
				contentType: "application/json",
				success: function(response_areas) {
					var numofdata = response_areas.length;

					if(numofdata == undefined) {
						$('#area').empty().append($('<option></option>').val('').text('-----'));
						$('#area').append($('<option></option>').val(response_areas.error).text(response_areas.error));
					} else {					
						$('#area').empty().append($('<option></option>').val('').text('-----'));

						for(var i=0; i<numofdata; i++) {
							$('#area').append($('<option></option>').val(response_areas[i].filename).text(response_areas[i].area));
						}

						$('#road').empty().append($('<option></option>').val('').text('-----'));
					}
				},
				error: function() {
					console.log('error');
				}
			});
		});

		$('#area').change(function() {
			var areafilename = $('#area').val();

			$.ajax({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				method: "POST",
				url: "/arealinkroad",
				data: JSON.stringify({areafilename: areafilename}),
				contentType: "application/json",
				success: function(response_roads) {
					var numofdata_r = response_roads.length;

					$('#road').empty().append($('<option></option>').val('').text('-----'));

					for(var i=0; i<numofdata_r; i++) {
						$('#road').append($('<option></option>').val(response_roads[i].name).text(response_roads[i].name));
					}
				},
				error: function() {
					console.log('error');
				}
			});
		});
	</script>

	<script async defer src="https://maps.googleapis.com/maps/api/js?key=Your_API_KEY&callback=initMap"></script>
</body>

</html>