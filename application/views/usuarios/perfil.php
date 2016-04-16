<?php template_set('title', 'Perfil de Usuario') ?>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDY6aARD3BZGp4LD2RhzefUdfSIy4mqvzU&callback=initMap"
async defer></script>
<div class="row">
    <div class="row large-6 columns menu-login">
        <?php if ( ! empty(error_array())): ?>
            <div data-alert class="alert-box alert radius alerta">
              <?= validation_errors() ?>
              <a href="#" class="close">&times;</a>
            </div>
        <?php endif ?>
    </div>
    <div class="large-4 columns">
        <?php if( ! empty($articulos_usuarios)): ?>
            <?php foreach ($articulos_usuarios as $v): ?>
                <h3>Articulos Disponibles</h3>
                <div class="">
                    <?= anchor('/articulos/buscar/' . $v['id'], img('/imagenes_articulos/' . $v['id'] . '.jpg')) ?>
                </div>
                <div class="">
                    <?= $v['precio'] ?>
                </div>
                <div class="">
                    <?= anchor('/articulos/buscar/' . $v['id'], $v['nombre']) ?>
                </div>
                <div class="">
                    <?= anchor('/frontend/portada/buscar_por_categoria/' . $v['nombre_categoria'], $v['nombre_categoria']) ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <h3>El usuario <?= $usuario['nick'] ?> no tiene productos a la venta actualmente >.<</h3>
        <?php endif; ?>
    </div>
    <div class="large-3 columns">
        <h3>Datos del usuario</h3>
        <div class="">
            <p>Ventas =></p>
            <div class="">
                <?php foreach ($articulos_vendidos as $v): ?>
                    <div class="">
                        <?= anchor('/articulos/buscar/' . $v['articulo_id'],
                                    img('/imagenes_articulos/' . $v['articulo_id'] . '.jpg')) ?>
                    </div>
                    <div class="">
                        <?= $v['precio'] ?>
                    </div>
                    <div class="">
                        <?= anchor('/articulos/buscar/' . $v['articulo_id'], $v['nombre']) ?>
                    </div>
                    <div class="">
                        <?= anchor('/frontend/portada/buscar_por_categoria/' . $v['nombre_categoria'], $v['nombre_categoria']) ?>
                    </div>
                    <div class="">
                        Vendido a =>
                            <?= anchor('/usuarios/perfil/' . $v['comprador_id'], $v['comprador_nick']) ?>
                    </div>
                    <div class="">
                        <p>Valoracion por parte del comprador => <?= $v['valoracion'] ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <div class="large-4 columns">
        <h3>Geolocalizacion</h3>
        <div class="" id="map"></div>
        <script>
          var map;
          function initMap() {
            map = new google.maps.Map(document.getElementById('map'), {
              zoom: 6,
              mapTypeId: google.maps.MapTypeId.SATELLITE,
              mapTypeControl: false,
      		  zoomControl: false,
      		  scaleControl: false,
      		  streetViewControl: false,
      		  fullscreenControl: false
            });

            if (navigator.geolocation){
				navigator.geolocation.getCurrentPosition(mostrarLocalizacion, manejadorDeError);
			}
			else{
				alert("Su navegador no soporta Geolocalizacion");
			}
          }

     	  function mostrarLocalizacion(posicion){
                var pos = new google.maps.LatLng(40.4155, -3.6968);
                map.setCenter(pos);

                // $.ajax({
                //     url: './tiempo/prevision.php',
                //     type: 'POST',
                //     async: true,
                //     success: respuesta,
                //     error: error,
                //     dataType: "json"
                // });
          }

          function manejadorDeError(error) {
			switch(error.code) {
                case error.PERMISSION_DENIED: alert("El usuario no permite compartir datos de geolocalizacion");
                break;

                case error.POSITION_UNAVAILABLE: alert("Imposible detectar la posicio actual");
                break;

                case error.TIMEOUT: alert("La posicion debe recuperar el tiempo de espera");
                break;

                default: alert("Error desconocido");
                break;
            }
    	  }

          function obtener_antipodas(latitud, longitud) {
              return {anti_latitud: -latitud, anti_longitud: (180 - Math.abs(longitud))};
          }

          function respuesta(respuesta) {
              for (var ciudad in respuesta.ciudades) {
                  var latitud = respuesta.ciudades[ciudad].latlon[0];
                  var longitud = respuesta.ciudades[ciudad].latlon[1];
                  var prediccion = respuesta.ciudades[ciudad].prediccion;
                  // alert("Lat: " + latitud + " y Lon: " + longitud + ". Prediccion: " + prediccion);
                  var imagen = new google.maps.MarkerImage(
                      "http://localhost/maps/tiempo/images/" + prediccion + ".png"
                  );
                  var pos = new google.maps.LatLng(latitud, longitud);
                  var marker = new google.maps.Marker({
      			      position: pos,
      			      map: map,
      			      title: prediccion,
                      icon: imagen
      			  });
              }
          }
          function error(error) {
              alert("Ha ocurrido el error => " + error.statusText);
          }
        </script>
    </div>
</div>
