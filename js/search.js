var actMap;

function initMap() {

	  var styles = [
	  {
		featureType: "all",
		elementType: "labels",
		stylers: [
		  { visibility: "on" }
		]
	  }
	];

	var styledMap = new google.maps.StyledMapType(styles,
    {name: "Styled Map"});

	var mapOptionsSK = {
			center: { lat: 48.78, lng: 19.62},
			zoom: 7,
			disableDefaultUI: false,
			panControl: false,
			zoomControl: false,
			scaleControl: false,
			overviewMapControl: false,
			draggable: true,
			keyboardShortcuts: false,
			//maxZoom: 12,
			minZoom: 6,
			scrollwheel: true
        };
	
	actMap = new google.maps.Map(document.getElementById("map"), mapOptionsSK);

	actMap.mapTypes.set('map_style', styledMap);
	actMap.setMapTypeId('map_style');
	
}

function showResult(str) {

	if (str.length == 0) {
		$('#results').html("");
		//$("#results").css("border", "0px");
		if ($('.dropdown').hasClass('open'))
			$('.dropdown-toggle').dropdown("toggle");
		
		return 0;
	}
	
	$.ajax({
		type: 'GET',
		datatype: 'json',
		url: 'search.php?q='+str,
		success: function(response) {
			$('#results').html(response["result"]);
			//$("#results").css("border", "1px solid #A5ACB2");
			
			if (!$('.dropdown').hasClass('open'))
				$('.dropdown-toggle').dropdown("toggle");
		}
	});
}

function showInfo(mesto_id) {
	$('#results').html("");
	//$("#results").css("border", "0px");
	/*if ($('.dropdown').hasClass('open'))
		$('.dropdown-toggle').dropdown("toggle");*/
	
	$('#output').show();
	
	$.ajax({
		type: 'GET',
		url: 'search.php?id='+mesto_id,
		datatype: 'json',
		success: function(response) {
			$('#results').html("");
			$("#results").css("border", "0px");
			
			//zobrazenie ziskanych udajov
			$('#city_nazov').html(response["result"]["nazov"]);
			$('#city_urad').html(response["result"]["urad"]);
			$('#city_starosta').html(response["result"]["starosta"]);
			$('#city_tel').html(response["result"]["tel"]);
			$('#city_web').html(response["result"]["web"]);
			$('#city_fax').html(response["result"]["fax"]);
			$('#city_email').html(response["result"]["email"]);
			$('#city_erb').attr("src", response["result"]["erb"]);
			
			//init google mapy
			initMap();
			
			//oznacenie obce na mape
			var coords = {lat: parseFloat(response["result"]["lat"]), lng: parseFloat(response["result"]["lng"])};
			
			var marker = new google.maps.Marker({
				position: coords,
				map: actMap,
				title: response["result"]["nazov"],
				/*label: {
					text: response["result"]["nazov"],
				  }*/
			  });
		}
	});

	
}

$(document).ready(function() {
	$('#output').hide();
	
    $("#input").keyup(function() {
		var str = $("#input").val();
		
		showResult(str);
	});
	
	 
});  