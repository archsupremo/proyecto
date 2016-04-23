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

            if (navigator.geolocation){
				navigator.geolocation.getCurrentPosition(mostrarLocalizacion, manejadorDeError);
			}
			else {
				alert("Su navegador no soporta Geolocalizacion");
			}
          }

     	  function mostrarLocalizacion(posicion){
                var latitud = posicion.coords.latitude;
                var longitud = posicion.coords.longitude;

                var pos = new google.maps.LatLng(latitud, longitud);
                map.setCenter(pos);

                var marker = new google.maps.Marker({
                    position: pos,
                    map: map,
                    title: "Tu estas aquí >.<",
                });
                $.ajax({
                    url: "<?= base_url() ?>usuarios/usuarios_cercanos/" + latitud + "/" + longitud,
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

                  var pos = new google.maps.LatLng(latitud, longitud);
                  var marker = new google.maps.Marker({
      			      position: pos,
      			      map: map,
                      title: usuario.nick + " está aquí >.<",
      			  });
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
        <?php foreach ($articulos as $k => $v): ?>
            <div class="large-4 columns left articulos" id="<?= $v['id'] ?>">
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
                    <?= form_open('/frontend/portada/') ?>
                        <?= form_hidden('categoria', $v['categoria_id'],
                                       'id="categoria" class=""') ?>
                        <?= form_hidden('nombre', '',
                                       'id="nombre" class=""') ?>
                        <?= form_submit('buscar', $v['nombre_categoria'], 'class=""') ?>
                    <?= form_close() ?>
                </div>
                <div class="clearing-thumbs" data-clearing>
                    <div class="favorito <?= ($v['favorito'] === "t") ? 'es_favorito' : 'no_favorito' ?>">
                    </div>
                    <?php if(file_exists('/imagenes_usuarios/' . $v['id'] . '.jpg')): ?>
                        <?php $url = '/imagenes_usuarios/' . $v['id'] . '.jpg' ?>
                    <?php else: ?>
                        <?php $url = '/imagenes_usuarios/gallifreyan.png' ?>
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
