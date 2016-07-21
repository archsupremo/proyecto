<!DOCTYPE html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>BuyAndSell</title>
    <link rel="stylesheet" href="/css/foundation.css" />
    <link rel="stylesheet" href="/rateyoJquery/jquery.rateyo.css"/>
    <link rel="stylesheet" href="/slick/slick.css">
    <link rel="stylesheet" href="/slick/slick-theme.css">
    <link rel="stylesheet" href="/css/basic.css">
    <link rel="stylesheet" href="/css/dropzone.css">
    <link rel="stylesheet" href="/toogle/css/toggles.css">
    <link rel="stylesheet" href="/toogle/css/toggles-full.css">
    <link rel="stylesheet" href="/toogle/css/themes/toggles-all.css">
    <link rel="stylesheet" href="/rubytabs/rubytabs.css">
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
    <link href="/tag/jquery.tag-editor.css" rel="stylesheet">
    <link href="/css/estilos_mapa.css" rel="stylesheet">
    <link href="/css/footer.css" rel="stylesheet">

    <!-- Foundation y rateYo -->
    <script src="/js/modernizr.js"></script>
    <script src="/js/jquery.js"></script>
    <script src="/js/foundation.min.js"></script>
    <script src="/js/foundation/foundation.alert.js"></script>
    <script src="/cookie/jquery.cookie.js"></script>
  </head>
  <body>
      <input id="pac-input" class="controls" type="text" placeholder="Buscar por ciudad, región, país...">
      <div class="mapa_index" id="map"></div>
      <br>
      <div class="row">
          <div class="large-6 large-centered columns">
              <a href="#" id="aceptar" class="success button small radius">Aceptar</a>
              <a href="#" id="cancelar" class="alert button small radius">Cancelar</a>
          </div>
      </div>
      <script type="text/javascript">
        $(document).foundation();
      </script>
      <script type="text/javascript">
        $(document).ready(function() {
            $("a#cancelar").click(function (evento) {
                evento.preventDefault();
                window.close();
            });
            $("a#aceptar").click(function (evento) {
                evento.preventDefault();

                window.opener.document.getElementsByName('latitud')[0].value = latitud;
                window.opener.document.getElementsByName('longitud')[0].value = longitud;
                window.opener.document.getElementById('localizacion').value = address;
                window.opener.cerrar_modal();
                window.close();
            });
        });
      </script>
      <script type="text/javascript">
        var map;
        var position_changed;
        var position_name;

        function initMap() {
          map = new google.maps.Map(document.getElementById('map'), {
            zoom: 14,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            mapTypeControl: false,
            zoomControl: true,
            scaleControl: true,
            streetViewControl: false,
            fullscreenControl: false
          });
          var input = document.getElementById('pac-input');
          var searchBox = new google.maps.places.SearchBox(input);
          map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

          map.addListener('bounds_changed', function() {
              searchBox.setBounds(map.getBounds());
          });

          var markers = [];
          searchBox.addListener('places_changed', function() {
              var places = searchBox.getPlaces();

              if (places.length == 0) {
                return;
              }

              // Clear out the old markers.
              markers.forEach(function(marker) {
                marker.setMap(null);
              });
              markers = [];

              var bounds = new google.maps.LatLngBounds();
              places.forEach(function(place) {
                  position_changed = place.geometry.location;
                  position_name = place.name;
                  if (place.geometry.viewport) {
                      bounds.union(place.geometry.viewport);
                  } else {
                      bounds.extend(place.geometry.location);
                  }
              });
              map.fitBounds(bounds);
          });
          zoom = map.getZoom();

          if (navigator.geolocation){
              navigator.geolocation.getCurrentPosition(mostrarLocalizacion, manejadorDeError);
          }
          else {
              alert("Su navegador no soporta Geolocalizacion");
          }

          map.addListener('bounds_changed', function () {
              if(position_changed != undefined) {
                  latitud = position_changed.lat();
                  longitud = position_changed.lng();

                  pos = new google.maps.LatLng(latitud, longitud);
                  map.setCenter(pos);
                  marker_yo.setPosition(pos);

                  var geocoder = new google.maps.Geocoder();
                  geocoder.geocode({'latLng': pos}, function(results, status) {
                      if (status == google.maps.GeocoderStatus.OK) {
                          address = results[0]['formatted_address'];
                      }
                  });

                  position_changed = undefined;
              }
          });
        }

        function mostrarLocalizacion(posicion){
              latitud = posicion.coords.latitude;
              longitud = posicion.coords.longitude;
              dibujarMarker(latitud, longitud);
        }

        function dibujarMarker(latitud, longitud) {
            pos = new google.maps.LatLng(latitud, longitud);
            map.setCenter(pos);

            marker_yo = new google.maps.Marker({
                position: pos,
                map: map,
                draggable: true,
                title: "Tu estas aquí >.<",
                icon: {
                    url: '<?= base_url() ?>img/marker.png',
                    size: new google.maps.Size(50, 60),
                    origin: new google.maps.Point(0, 0),
                    anchor: new google.maps.Point(30, 60),
                    scaledSize: new google.maps.Size(60, 60)
                }
            });
            var geocoder = new google.maps.Geocoder();
            marker_yo.addListener('dragend', function (e) {
                geocoder.geocode({'latLng': this.getPosition()}, function(results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        address = results[0]['formatted_address'];
                        latitud = results[0]['geometry']['location'].lat();
                        longitud = results[0]['geometry']['location'].lng();

                        pos = new google.maps.LatLng(latitud, longitud);
                        map.setCenter(pos);
                    }
                });
            });

            marker_yo.addListener('mouseover', function (e) {
                marker_yo.setAnimation(google.maps.Animation.BOUNCE);
            });
            marker_yo.addListener('mouseout', function (e) {
                marker_yo.setAnimation(null);
            });
        }

        function manejadorDeError(error) {
          latitud = 40.4168;
          longitud = -3.7038;
          var geocoder = new google.maps.Geocoder();
          geocoder.geocode({'latLng': new google.maps.LatLng(latitud, longitud)}, function(results, status) {
              if (status == google.maps.GeocoderStatus.OK) {
                  address = results[0]['formatted_address'];
              }
          });
          dibujarMarker(latitud, longitud);

          switch(error.code) {
              case error.PERMISSION_DENIED:
              case error.TIMEOUT:
              break;
              case error.POSITION_UNAVAILABLE:
                  alert("Imposible detectar la posicio actual");
              break;
              default:
                  alert("Error desconocido");
              break;
          }
        }
      </script>
      <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDY6aARD3BZGp4LD2RhzefUdfSIy4mqvzU&libraries=places&callback=initMap"
      async defer></script>
  </body>
</html>
