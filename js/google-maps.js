var map;
var infoWindow;
var service;

function initMap() {
  var sti_locations_center = {lat: 46.56, lng: 6.8 };

  map = new google.maps.Map(document.getElementById('googlemap'), {
    center: sti_locations_center,
    zoom: 8
  });

  // Find placeId here: https://developers.google.com/places/place-id
  var locations = [
    {placeId: 'ChIJP6Ls3f0wjEcRcrKTf6s0zLs'}, //  Swiss Federal Institute of Technology Lausanne
    {placeId: 'ChIJFzvjoR5ljEcRk75_LhNfOhY'}, //  Campus Biotech
    {placeId: 'ChIJ04YCiSMKjkcR82vHt2FplPE'}, //  Microcity
    {placeId: 'ChIJp1NwgyTcjkcRyy0R3xVAkIA'}, //  EPFL Valais Wallis
    {placeId: 'ChIJh5I7JtQvjEcRMH8V58ImG3E'}, //  EPFL Laboratoire de Machines hydrauliques
    {placeId: 'ChIJU3z_HDTIjkcR1zFyL-u2amg'}, //  Idiap Research Institute
  ]

  service = new google.maps.places.PlacesService(map);
  infoWindow = new google.maps.InfoWindow();

  console.log('before for');
  for (var i = 0; i < locations.length; i++) {
    service.getDetails(locations[i], callback);
  }
}

function callback(place, status)
{
  console.log('into callback');
  if (status == google.maps.places.PlacesServiceStatus.OK) {
    createMarker(place);
  }
}

function createMarker(place)
{
  var marker = new google.maps.Marker({
    map: map,
    place: {
      placeId: place.place_id,
      location: place.geometry.location
    }
  });

  google.maps.event.addListener(marker, 'click', function() {
    service.getDetails(place, function(result, status) {
      if (status !== google.maps.places.PlacesServiceStatus.OK) {
        console.error(status);
        return;
      }
      infoWindow.setContent(result.name);
      infoWindow.open(map, marker);
    });
  });
}
