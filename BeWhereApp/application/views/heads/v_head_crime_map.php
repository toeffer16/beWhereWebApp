<style type="text/css">
  html, body, #map-canvas { height: 100%; margin: 0; padding: 0;}
</style>
<script type="text/javascript"
  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDWUbQndksJLY1bIzZOvYrO6denngD61u4">
</script>

<script>
function initialize() {
  var mapProp = {
    center:new google.maps.LatLng(7.086457,125.617170),
    zoom:13,
    mapTypeId:google.maps.MapTypeId.ROADMAP
  };
  var map=new google.maps.Map(document.getElementById("googleMap"),mapProp);
}
google.maps.event.addDomListener(window, 'load', initialize);
</script>