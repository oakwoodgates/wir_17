<?php /*template name: zDev2*/
/*
global $wpdb;
$b = 7;
$a = $wpdb->get_row( "
	SELECT *
	FROM wp_wir_location_data
	WHERE location_id = $b
	" );

if ( null !== $a ) {
	// row exists
	$wpdb->replace(
		'wp_wir_location_data',
		array(
			'id' => $a->id,
			'location_id' => $b,
			'latitude' => -35.9587463,
			'formatted_address' => '123 test ave'
		)
	);
} else {
	// no row

	$wpdb->replace(
		'wp_wir_location_data',
		array(
			'location_id' => $b,
			'latitude' => -35.9587463,
			'formatted_address' => '123 test ave00'
		)
	);
}

*/
function wir_get_google_location_data() {

}

?>
<!DOCTYPE html>
<html>
  <head>
    <title>Place Autocomplete Address Form</title>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
		<link type="text/css" rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500">
    <style>

		#map {
			height: 400px;
		}
		html, body {
			height: 100%;
			margin: 0;
			padding: 0;
		}
		#description {
			font-family: Roboto;
			font-size: 15px;
			font-weight: 300;
		}

		#infowindow-content .title {
			font-weight: bold;
		}

		#infowindow-content {
			display: none;
		}

		#map #infowindow-content {
			display: inline;
		}

		.pac-card {
			margin: 10px 10px 0 0;
			border-radius: 2px 0 0 2px;
			box-sizing: border-box;
			-moz-box-sizing: border-box;
			outline: none;
			box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
			background-color: #fff;
			font-family: Roboto;
		}

		#pac-container {
			padding-bottom: 12px;
			margin-right: 12px;
		}

		.pac-controls {
			display: inline-block;
			padding: 5px 11px;
		}

		.pac-controls label {
			font-family: Roboto;
			font-size: 13px;
			font-weight: 300;
		}

		#pac-input {
			background-color: #fff;
			font-family: Roboto;
			font-size: 15px;
			font-weight: 300;
			margin-left: 12px;
			padding: 0 11px 0 13px;
			text-overflow: ellipsis;
			width: 400px;
		}

		#pac-input:focus {
			border-color: #4d90fe;
		}

		#title {
			color: #fff;
			background-color: #4d90fe;
			font-size: 25px;
			font-weight: 500;
			padding: 6px 12px;
		}

    </style>
    <style>
      #locationField, #controls {
        position: relative;
        width: 480px;
      }
      #autocomplete {
        position: absolute;
        top: 0px;
        left: 0px;
        width: 99%;
      }
      .label {
        text-align: right;
        font-weight: bold;
        width: 100px;
        color: #303030;
      }
      #address {
        border: 1px solid #000090;
        background-color: #f0f0ff;
        width: 480px;
        padding-right: 2px;
      }
      #address td {
        font-size: 10pt;
      }
      .field {
        width: 99%;
      }
      .slimField {
        width: 80px;
      }
      .wideField {
        width: 200px;
      }
      #locationField {
        height: 20px;
        margin-bottom: 2px;
      }
    </style>
  </head>

  <body>
<div id="locationField">
	<input id="autocomplete" placeholder="Enter your address"
				 onFocus="geolocate()" type="text"></input>
</div>

<table id="address">
	<tr>
		<td class="label">Street address</td>
		<td class="slimField"><input class="field" id="street_number"
					disabled="true"></input></td>
		<td class="wideField" colspan="2"><input class="field" id="route"
					disabled="true"></input></td>
	</tr>
	<tr>
		<td class="label">City</td>
		<!-- Note: Selection of address components in this example is typical.
				 You may need to adjust it for the locations relevant to your app. See
				 https://developers.google.com/maps/documentation/javascript/examples/places-autocomplete-addressform
		-->
		<td class="wideField" colspan="3"><input class="field" id="locality"
					disabled="true"></input></td>
	</tr>
	<tr>
		<td class="label">State</td>
		<td class="slimField"><input class="field"
					id="administrative_area_level_1" disabled="true"></input></td>
		<td class="label">Zip code</td>
		<td class="wideField"><input class="field" id="postal_code"
					disabled="true"></input></td>
	</tr>
	<tr>
		<td class="label">Country</td>
		<td class="wideField" colspan="3"><input class="field"
					id="country" disabled="true"></input></td>
	</tr>
</table>

    <div class="pac-card" id="pac-card">
      <div>
        <div id="title">
          Autocomplete search
        </div>
        <div id="type-selector" class="pac-controls">
        </div>
      </div>
      <div id="pac-container">
        <input id="pac-input" type="text"
            placeholder="Enter a location">
      </div>
    </div>
    <div id="map"></div>
    <div id="infowindow-content">
      <img src="" width="16" height="16" id="place-icon">
      <span id="place-name"  class="title"></span><br>
      <span id="place-address"></span>
    </div>



		<script>
			// This example displays an address form, using the autocomplete feature
			// of the Google Places API to help users fill in the information.

			// This example requires the Places library. Include the libraries=places
			// parameter when you first load the API. For example:
			// <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">

			var placeSearch, autocomplete;
			var componentForm = {
				street_number: 'short_name',
				route: 'long_name',
				locality: 'long_name',
				administrative_area_level_1: 'short_name',
				country: 'long_name',
				postal_code: 'short_name'
			};

			function initAutocomplete() {
				// Create the autocomplete object, restricting the search to geographical
				// location types.
				autocomplete = new google.maps.places.Autocomplete(
					(document.getElementById('autocomplete')),
						{types: ['geocode']});

				// When the user selects an address from the dropdown, populate the address
				// fields in the form.
				autocomplete.addListener('place_changed', fillInAddress);
			}

			function fillInAddress() {
				// Get the place details from the autocomplete object.
				var place = autocomplete.getPlace();
				console.log(place);
				for (var component in componentForm) {
					document.getElementById(component).value = '';
					document.getElementById(component).disabled = false;
				}
				// Get each component of the address from the place details
				// and fill the corresponding field on the form.
				for (var i = 0; i < place.address_components.length; i++) {
					var addressType = place.address_components[i].types[0];
					if (componentForm[addressType]) {
						var val = place.address_components[i][componentForm[addressType]];
						document.getElementById(addressType).value = val;
					}
				}
			}

			// Bias the autocomplete object to the user's geographical location,
			// as supplied by the browser's 'navigator.geolocation' object.
			function geolocate() {
				if (navigator.geolocation) {
					navigator.geolocation.getCurrentPosition(function(position) {
						var geolocation = {
							lat: position.coords.latitude,
							lng: position.coords.longitude
						};
						var circle = new google.maps.Circle({
							center: geolocation,
							radius: position.coords.accuracy
						});
						autocomplete.setBounds(circle.getBounds());
					});
				}
			}
		</script>
    <script>
      // This example requires the Places library. Include the libraries=places
      // parameter when you first load the API. For example:
      // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">

      function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
          center: {lat: -33.8688, lng: 151.2195},
          zoom: 13
        });
        var card = document.getElementById('pac-card');
        var input = document.getElementById('pac-input');
        var types = document.getElementById('type-selector');
        var strictBounds = document.getElementById('strict-bounds-selector');

        map.controls[google.maps.ControlPosition.TOP_RIGHT].push(card);

        var autocomplete = new google.maps.places.Autocomplete(input);

        // Bind the map's bounds (viewport) property to the autocomplete object,
        // so that the autocomplete requests use the current map bounds for the
        // bounds option in the request.
        autocomplete.bindTo('bounds', map);

        // Set the data fields to return when the user selects a place.
  //      autocomplete.setFields(
    //        ['address_components', 'geometry', 'icon', 'name']);

        var infowindow = new google.maps.InfoWindow();
        var infowindowContent = document.getElementById('infowindow-content');
        infowindow.setContent(infowindowContent);
        var marker = new google.maps.Marker({
          map: map,
          anchorPoint: new google.maps.Point(0, -29)
        });

        autocomplete.addListener('place_changed', function() {
          infowindow.close();
          marker.setVisible(false);
          var place = autocomplete.getPlace();
					console.log(place);
          if (!place.geometry) {
            // User entered the name of a Place that was not suggested and
            // pressed the Enter key, or the Place Details request failed.
            window.alert("No details available for input: '" + place.name + "'");
            return;
          }

          // If the place has a geometry, then present it on a map.
          if (place.geometry.viewport) {
            map.fitBounds(place.geometry.viewport);
          } else {
            map.setCenter(place.geometry.location);
            map.setZoom(17);  // Why 17? Because it looks good.
          }
          marker.setPosition(place.geometry.location);
          marker.setVisible(true);

          var address = '';
          if (place.address_components) {
            address = [
              (place.address_components[0] && place.address_components[0].short_name || ''),
              (place.address_components[1] && place.address_components[1].short_name || ''),
              (place.address_components[2] && place.address_components[2].short_name || '')
            ].join(' ');
          }

          infowindowContent.children['place-icon'].src = place.icon;
          infowindowContent.children['place-name'].textContent = place.name;
          infowindowContent.children['place-address'].textContent = address;
          infowindow.open(map, marker);
        });
      }
    </script>



<script type="text/javascript">
	function init() {
		initMap();
	//	initAutocomplete();
	}

</script>


<script type="text/javascript" src="
	<?php
	$google_api_key = get_option('gaaf_field_api_key');
	$google_api_key = 'AIzaSyBbAxOSu7oB_01O9L70H8ZNIEKks9dtMZ0'; // access
//	$google_api_key2 = 'AIzaSyB4yJ0yV4fVKIDLBLTMCaHWO5SMYKBEmZE'; // previous
	$google_api_key2 = 'AIzaSyB5X2n2seaXMr3wozD7B9ZWe14625ItY_o'; // new php
	//$google_api_key = 'AIzaSyB16sGmIekuGIvYOfNoW9T44377IU2d2Es'; // some other
	$google_api_key3 = 'AIzaSyDXz86DFZMlQFqImEhKlEl6KFoDIjQhQYE'; // mine
	echo 'https://maps.googleapis.com/maps/api/js?key='. $google_api_key .'&libraries=places&callback=init';
?>
"
async defer>
</script>


<?php

echo '<h4>main api key image test:</h4>';
$a = 'https://maps.googleapis.com/maps/api/place/details/json?placeid=ChIJK1fEcFfzWYgRLz_8iODN-TI&key='.$google_api_key2;
$b = wp_remote_get( $a );
// echo $b;
if ( is_array( $b ) ) {
  $c = $b['body']; // use the content
	$d = json_decode( $c, true );
	$e = $d['result'];
	$p = $e['photos'][0]['photo_reference'];
	$x = 'https://maps.googleapis.com/maps/api/place/photo?maxwidth=400&photoreference='.$p.'&key='.$google_api_key2;
//	echo $x;
	$y = wp_remote_get( $x );
	//echo $y;
	if ( is_array( $y ) ) {
		echo '<br/><img src="'.$x.'" />';
	//	unset($y['body']);
	//	$z = $y['http_response'];
	//	echo'<pre>';print_r($z);echo'</pre>';
		//echo'<pre>';print_r($y['body']);echo'</pre>';
	}
//	echo'<pre>';print_r($e);echo'</pre>';
}

echo '<h4>dev api key image test:</h4>';
$a = 'https://maps.googleapis.com/maps/api/place/details/json?placeid=ChIJK1fEcFfzWYgRLz_8iODN-TI&key='.$google_api_key3;
$b = wp_remote_get( $a );
// echo $b;
if ( is_array( $b ) ) {
  $c = $b['body']; // use the content
	$d = json_decode( $c, true );
	$e = $d['result'];
	$p = $e['photos'][0]['photo_reference'];
	$x = 'https://maps.googleapis.com/maps/api/place/photo?maxwidth=400&photoreference='.$p.'&key='.$google_api_key3;
//	echo $x;
	$y = wp_remote_get( $x );
	//echo $y;
	if ( is_array( $y ) ) {
		echo '</br/><img src="'.$x.'" />';
	//	unset($y['body']);
	//	$z = $y['http_response'];
	//	echo'<pre>';print_r($z);echo'</pre>';
		//echo'<pre>';print_r($y['body']);echo'</pre>';
	}
//	echo'<pre>';print_r($e);echo'</pre>';
}

/*
//$p = 'CmRaAAAAraRaTj_oylZJOIUe67RcrdQb6biusa7aAOETyqz0SQoLIYYynY_XkfLWtsI-_rPaGctJIQ72Pxr-cik4lJdQpLBzE_CtBqGSGARzDSxc8eZedP7DP8az4JDzue-XPE4aEhAsvMaYDrl5VWkoit0SN47yGhQQywhcpR8HN001PA0lQvqc4CjyMQ';
//$p = 'CnRtAAAATLZNl354RwP_9UKbQ_5Psy40texXePv4oAlgP4qNEkdIrkyse7rPXYGd9D_Uj1rVsQdWT4oRz4QrYAJNpFX7rzqqMlZw2h2E2y5IKMUZ7ouD_SlcHxYq1yL4KbKUv3qtWgTK0A6QbGh87GB3sscrHRIQiG2RrmU_jF4tENr9wGS_YxoUSSDrYjWmrNfeEHSGSc3FyhNLlBU';
$p = 'CmRaAAAAK6WBmx6yhgV95BrOnEhdUEXDPZbxYU6AmeFcFAWc1f6k3f_BmDRifPpQSHuJUOzLwlw8wqIgNrCqmJKum3PZBVqM_iaExcUNEkM5eMaKliw5iDf7_1eexcDkYWH5MR8xEhDIvAIO3yPtddMOj4OLqMz7GhQXZn4NWhtARN-wBvri8fUc21mfBg';
$x = 'https://maps.googleapis.com/maps/api/place/photo?maxwidth=400&photoreference='.$p.'&key='.$google_api_key2;
echo $x;
$y = wp_remote_get( $x );
//echo $y;
if ( is_array( $y ) ) {
	echo '<img src="'.$x.'" />';
	unset($y['body']);
	$z = $y['http_response'];
//	echo'<pre>';print_r($z);echo'</pre>';
	//echo'<pre>';print_r($y['body']);echo'</pre>';
}



$q = 'https://app.wheninroamtravelapp.com/wp-content/uploads/2018/06/orange-peel-outside_657ae5c3-5056-a36a-0a9a60a84e9cb6f5.jpg';
$r = wp_remote_get( $q );
//echo $y;
if ( is_array( $r ) ) {
//	echo '<img src="'.$x.'" />';
//	unset($y['body']);
//	$z = $y['http_response'];
	echo'<pre>';print_r($r);echo'</pre>';
	//echo'<pre>';print_r($y['body']);echo'</pre>';
}

$t = 'https://maps.googleapis.com/maps/api/place/js/PhotoService.GetPhoto?1sCmRaAAAANpc2pG5MQ94azMQx2hVJVb2bPscJDdsvE-VS1ztO03_kbym02aYZAsZuKc5d0ivwz0kJSuAXwtMvfTPm1SruxSbd54RgY52fVjmgDih_UWLD4eulnpXohZ19o0zEfmhbEhA2X-bDRKpgb1DIXLH0Kh0iGhQhRB6qUjHR1rdyQPj_hKQhZSZn5g&3u400&4u400&5m1&2e1&callback=none&key=AIzaSyB4yJ0yV4fVKIDLBLTMCaHWO5SMYKBEmZE&token=114760';
$u = wp_remote_get( $t );
//echo $y;
echo 'HERE';
if ( is_array( $u ) ) {
//	echo '<img src="'.$x.'" />';
//	unset($y['body']);
//	$z = $y['http_response'];
	echo'<pre>';print_r($u);echo'</pre>';
	//echo'<pre>';print_r($y['body']);echo'</pre>';
}
*/



?>


</body>
</html>
