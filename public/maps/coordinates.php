<?php


/**
 * This script is used to deal with adding markers when a user scrolls, zooms, or pans
 *
 */

?>

<script type="text/javascript">



/**
 * This should be called on 'idle', that is when user stops panning or zooming
 *
 * @param map Google map object
 *
*/

function getCurrentCoordinates(map) {

	//get current boundaries
	var bounds = map.getBounds();

	//and slip up to N,S,E,W
	var coordinates = bounds.toUrlValue();
	coordinate_array = coordinates.split(',');

	var south = coordinate_array[0];
	var west = coordinate_array[1];
	var north = coordinate_array[2];
	var east = coordinate_array[3];

	getNewMarkersAJAX(south,west,north,east,map);


}

/**
 * This is what makes the AJAX call to get the new markers
 * @param $south decimal southern coordinate
 * @param $west decimal western coordinate
 * @param $north decimal northern coordinate
 * @param $east decimal eastern coordinate
 * @param map Google map object
 *
*/
	function getNewMarkersAJAX(south,west,north,east,map) {
		 var xmlHttp = new XMLHttpRequest();
		 var url="coordinateQuery.php?rand="+Math.random();
		 var parameters = "south="+south+"&west="+west+"&north="+north+"&east="+east;
		 xmlHttp.open("POST", url, true);

		 xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		 xmlHttp.setRequestHeader("Content-length", parameters.length);
		 xmlHttp.setRequestHeader("Connection", "close");

		 xmlHttp.onreadystatechange = function() {
		  if(xmlHttp.readyState == 4 && xmlHttp.status == 200) {

			  var newPoints = eval(xmlHttp.responseText);

			 //DEBUG : outputs JSON from AJAX Call
			  //console.log (newPoints);


			  var point = '';
			  var marker = '';


			// Now let's make a marker one by one
			  for (var i=0;i<newPoints.length;i++)
			  {

				  point = new google.maps.LatLng(newPoints[i]['latitude'],newPoints[i]['longitude']);

				  //but only if it's not already there
				  if (document.getElementById(newPoints[i]['mappointid'])==null) {
					  var marker = new google.maps.Marker({
					      position: point,
					      map: map,
					      title:newPoints[i]['name']
					  });

					  marker.set("id", newPoints[i]['mappointid']);
					  marker.content = '<div id="content"><b>'+newPoints[i]['name']+'</b><br />'+newPoints[i]['address']+'</div>';

						//the pop-ups
					  var infoWindow = new google.maps.InfoWindow();
					  google.maps.event.addListener(marker, 'click', function () {
					     infoWindow.setContent(this.content);
					     infoWindow.open(this.getMap(), this);
					  });


				  }
			  }



		  }
		 };

		 xmlHttp.send(parameters);


	}

</script>