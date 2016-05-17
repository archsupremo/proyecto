<?php template_set('title', 'Portada') ?>
<div class="row">
</div>
<div class="row medium-uncollapse large-collapse">
    <div class="large-3 columns mapa">
        <h4>¿Quien esta vendiendo a tu alrededor?</h4>
        <div class="mapa_index" id="map"></div>
        <script>
          var usuario_id = <?= logueado() ? dar_usuario()['id'] : 'undefined' ?>;
          var map;
          var zoom;
          var markers = [];

          function initMap() {
            map = new google.maps.Map(document.getElementById('map'), {
              zoom: 13,
              mapTypeId: google.maps.MapTypeId.ROADMAP,
              mapTypeControl: false,
      		  zoomControl: true,
      		  scaleControl: true,
      		  streetViewControl: false,
      		  fullscreenControl: false
            });

            zoom = map.getZoom();

            if (navigator.geolocation){
				navigator.geolocation.getCurrentPosition(mostrarLocalizacion, manejadorDeError);
			}
			else {
				alert("Su navegador no soporta Geolocalizacion");
			}

            map.addListener('zoom_changed', function() {
                if(zoom > map.getZoom()) {
                    draw_circle.setRadius(draw_circle.getRadius() * 2);
                } else {
                    draw_circle.setRadius(draw_circle.getRadius() / 2);
                }
                zoom = map.getZoom();
                for (var marker in markers) {
                    markers[marker].setMap(null);
                }
                $.ajax({
                    url: "<?= base_url() ?>usuarios/usuarios_cercanos/" +
                          latitud + "/" + longitud + "/" + draw_circle.getRadius(),
                    type: 'GET',
                    async: true,
                    success: respuesta,
                    error: error,
                    dataType: "json"
                });
            });
          }

     	  function mostrarLocalizacion(posicion){
                latitud = posicion.coords.latitude;
                longitud = posicion.coords.longitude;
                // var latitud = 36.8725774;
                // var longitud = -6.3529689;

                pos = new google.maps.LatLng(latitud, longitud);
                map.setCenter(pos);

                var marker = new google.maps.Marker({
                    position: pos,
                    map: map,
                    title: "Tu estas aquí >.<"
                });
                draw_circle = new google.maps.Circle({
                    center: pos,
                    radius: 2000,
                    strokeColor: "#FF0000",
                    strokeOpacity: 0.6,
                    strokeWeight: 1,
                    fillColor: "#FF0000",
                    fillOpacity: 0.35,
                    map: map
                });
                $.ajax({
                    url: "<?= base_url() ?>usuarios/usuarios_cercanos/" +
                          latitud + "/" + longitud + "/" + draw_circle.getRadius(),
                    type: 'GET',
                    async: true,
                    success: respuesta,
                    error: error,
                    dataType: "json"
                });
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
              for (var usuario in respuesta.usuarios) {
                  var usuario = respuesta.usuarios[usuario];
                  if (usuario_id == usuario.id) {
                      continue;
                  }

                  var latitud = usuario.latitud;
                  var longitud = usuario.longitud;
                  var distancia = usuario.distancia;

                  if(distancia > 1000) {
                      distancia = parseFloat(distancia / 1000).toFixed(1) + " kilometros";
                  } else {
                      distancia = parseInt(distancia) + " metros";
                  }

                  var pos = new google.maps.LatLng(latitud, longitud);
                  var marker = new google.maps.Marker({
      			      position: pos,
                      title: usuario.nick + " está aquí >.<, a " + distancia + " de distancia.",
      			  });
                  markers.push(marker);
                  marker.setMap(map);
              }
          }
          function error(error) {
              alert("Ha ocurrido el error => " + error.statusText);
          }
        </script>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDY6aARD3BZGp4LD2RhzefUdfSIy4mqvzU&callback=initMap"
        async defer></script>
    </div>
    <div class="large-9 columns">
        <?php foreach ($articulos as $v): ?>
            <div class="large-4 columns left articulos" id="<?= $v['articulo_id'] ?>">
                <div class="">
                    <?php if(is_file($_SERVER["DOCUMENT_ROOT"] .  '/imagenes_articulos/' . $v['articulo_id'] . '_1' . '.jpg')): ?>
                        <?php $url = '/imagenes_articulos/' . $v['articulo_id'] . '_1' . '.jpg' ?>
                    <?php else: ?>
                        <?php $url = '/imagenes_articulos/sin-imagen.jpg' ?>
                    <?php endif; ?>

                    <?= anchor('/articulos/buscar/' . $v['articulo_id'],
                                img(array(
                                    'src' => $url,
                                    'title' => $v['nombre'],
                                    'alt' => $v['nombre'],
                                    // 'class' => 'imagen_nick',
                                ))) ?>
                </div>
                <div class="">
                    <?= $v['precio'] ?>
                </div>
                <div class="">
                    <?= anchor('/articulos/buscar/' . $v['articulo_id'], $v['nombre']) ?>
                </div>
                <div class="">
                    <?= form_open('/frontend/portada/') ?>
                        <?= form_hidden('categoria', $v['categoria_id'],
                                       'id="categoria" class=""') ?>
                        <?= form_hidden('nombre', '',
                                       'id="nombre" class=""') ?>
                        <?= form_submit('buscar', $v['nombre_categoria'], 'class="front_button"') ?>
                    <?= form_close() ?>
                </div>
                <div class="clearing-thumbs" data-clearing>
                    <div class="favorito <?= ($v['favorito'] === "t") ? 'es_favorito' : 'no_favorito' ?>">
                    </div>
                    <?php if(is_file($_SERVER["DOCUMENT_ROOT"] .  '/imagenes_usuarios/' . $v['usuario_id'] . '.jpg')): ?>
                        <?php $url = '/imagenes_usuarios/' . $v['usuario_id'] . '.jpg' ?>
                    <?php else: ?>
                        <?php $url = '/imagenes_usuarios/sin-imagen.jpg' ?>
                    <?php endif; ?>
                    <?= anchor('/usuarios/perfil/' . $v['usuario_id'],
                                img(array(
                                    'src' => $url,
                                    'title' => $v['nick'],
                                    'alt' => $v['nick'],
                                    'class' => 'imagen_nick',
                                ))) ?>
                    <?= anchor('/usuarios/perfil/' . $v['usuario_id'], $v['nick']) ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script type="text/javascript">
    var valores_defecto = {
        numStars: 1,
        maxValue: 1,
        fullStar: true,
        <?php if(!logueado()): ?>
            readOnly: true,
        <?php endif; ?>
    };
    $(function () {
        $(".favorito").rateYo(valores_defecto);
        $('.es_favorito').rateYo("rating", 1);
        $('.favorito').rateYo().on("rateyo.set", function (e, data) {
            var articulo_id = $(this).parent().parent().attr("id");
            $.ajax({
                url: "<?= base_url() ?>articulos/favoritos/" + articulo_id,
                type: 'POST',
                async: true,
                success: function() {
                    //alert("Todo ha ido bien.");
                },
                error: function (error) {
                    //alert("Error" + error.status);
                },
            });
        });
    });
    $(".favorito").click(function () {
        var valor = parseInt($(this).rateYo("rating"));

        if (valores_defecto.readOnly == undefined) {
            if(valor != 0) {
                $(this).rateYo("destroy");
                $(this).rateYo(valores_defecto);
                $(this).rateYo("rating", 0);
            }
        }
    });
    if (valores_defecto.readOnly != undefined) {
        $(".favorito").css("cursor", "not-allowed");
        $(".favorito").attr("title", "Logueate para añadir a favoritos");
    }
</script>
