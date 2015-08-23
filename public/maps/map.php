<!DOCTYPE html>
<?php require_once ('config.php'); ?>
<?php require_once ('coordinates.php'); ?>
<html>
<head>
<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
<style type="text/css">
#map-canvas {
	height: 650px;
	width: 650px;
}
</style>
<script type="text/javascript"
	src="https://maps.googleapis.com/maps/api/js?key=<?php echo GOOGLE_API; ?>&sensor=false">
    </script>
<script type="text/javascript">

    //create the map here;
    // might want to move lat, long, and zoom to config file
      function initialize() {
        var mapOptions = {
          center: new google.maps.LatLng(38.902372, -77.037994),
          zoom: 18,
          mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        var map = new google.maps.Map(document.getElementById("map-canvas"),
            mapOptions);

        	//after ever zoom or pan, new markers are adding (including inital load)
            google.maps.event.addListener(map, 'idle', function() {
            	getCurrentCoordinates(map);
             });

      }

      google.maps.event.addDomListener(window, 'load', initialize);


</script>

</head>

<body>

	<div id="map-canvas"></div>


</body>
</html>