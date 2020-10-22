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
	<!--script src="https://code.jquery.com/jquery-3.5.1.js"
  integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc="
  crossorigin="anonymous"></script-->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>

<body>
	<h1>Google Map Demo</h1>

	<form class="box" method="GET" action="/getaddress">
		<div class="address">
			<span style="font-size: 0.7em">請選擇縣巿:</span>
			<select id="city" name="city">	
					<option>Select</option>
				@foreach($cities as $city)
					<option>{{ $city->city }}</option>
				@endforeach
			</select>

			<span style="font-size: 0.7em">請選擇鄉鎮巿區:</span>
			<select id="area" name="area">
					<option>Select</option>
				@foreach($areas as $area)
					<option>{{ $area->area }}</option>
				@endforeach	
			</select>

			<span style="font-size: 0.7em">請選擇路(街):</span>
			<select id="route" name="route">
					<option>Select</option>
			</select>
		</div>

		<div class="address_la">
			<input type="text" name="lane" style="width: 50px">
			<label style="font-size: 0.7em">巷</label>
			<input type="text" name="alley" style="width: 50px">
			<label style="font-size: 0.7em">弄</label>
			<input type="text" name="no" style="width: 50px">
			<label style="font-size: 0.7em">號</label>
			<input type="text" name="floor" style="width: 50px">
			<label style="font-size: 0.7em">樓</label>

		<!-- Using for test Google Map API -->
		<!--input type="text" class="address" name="address"-->
		</div>

		<div class="other_info">
			<label style="font-size: 0.7em">其他資訊:</label>
			<input type="text" name="info">
		</div>

			<button class="submit">search</button>
	</form>	

	<div id="map"></div>

	<!-- Google Map API -->
	<!--script>
		var map, geocoder;

		function initMap() {

			geocoder = new google.maps.Geocoder();
			map = new google.maps.Map(document.getElementById('map'), {
				//center: {lat: 24.001, lng: 120.885},
				zoom: 10
			});

			$.ajax({
				method: "GET",
				url: "/getaddress",
				dataType: "JSON",
				success: function(address) {
					geocoder.geocode({'address': address.full_address}, function(result,status)) {
						if(status == 'OK') {
							map.setCenter(result[0].geometry.location);
							var marker = new google.maps.Marker({
								map: map,
								position: result[0].geometry.location
							});
						} else {
							console.log(status);
						}
					}
				}
			});

			/*var address = '台北車站';

			geocoder.geocode({'address': address}, function(result, status) {
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
	</script-->

	<!-- Ajax dropdown list -->
	<script>
		$('#city').change(function() {
			var city = $('#city').val();

			$.ajax({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				method: "POST",
				url: "/citylinkarea",
				data: JSON.stringify({city: city}),
				dataType: "JSON",
				success: function(response_areas) {
					//window.location.href="/citylinkarea";
					var numofdata = response_areas.length;
					
					if(numofdata == undefined) {
						console.log(response_areas);

						$('#area').empty().append($('<option></option>').val('').text('-----'));
						$('#area').append($('<option></option>').val('').text(response_areas.error));
					} else {					
						console.log(response_areas);
						$('#area').empty().append($('<option></option>').val('').text('-----'));

						for(var i=0; i<numofdata; i++) {
							$('#area').append($('<option></option>').val('').text(response_areas[i].area));
						}
					}
				},
				error: function() {
					//window.location.href="/citylinkarea";
					console.log('error');
				}
			});
		});

		/*$('#area').change(function() {
			var area = $('#area').val();

			$.ajax({
				type: "GET",
				traditional: true,
				url: "/arealinkroute",
				data: {Area: area},
				dataType: "JSON",
				success: function(response_routes) {
					var numofdata = response_routes.length;

					$('#route').empty().append($('<option></option>').val('').text('-----'));

					for(var i=0; i<numofdata; i++) {
						$('#route').append($('<option></option>').val('').text(response_routes[i].name));
					}
				},
				error: function() {
					window.location.href="/arealinkroute";
					console.log('error');
				}
			});
		});*/
	</script>

	<!--script async defer src="https://maps.googleapis.com/maps/api/js?key=Your_API_Key&callback=initMap"></script-->
</body>

</html>