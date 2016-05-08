<?php template_set('title', 'Perfil de Usuario') ?>

<div class="row large-6 columns menu-login">
    <?php if ( ! empty(error_array())): ?>
        <div data-alert class="alert-box alert radius alerta">
          <?= validation_errors() ?>
          <a href="#" class="close">&times;</a>
        </div>
    <?php endif ?>
</div>
<div class="row">
    <div class="tabdemo wrapper tabdemo--one large-8 columns">
        <ul class="tabdemo__menu menu">
            <li><a href="#">Articulos Disponibles</a></li>
            <li><a href="#">Ventas</a></li>
            <?php if($usuario_perfil !== TRUE): ?>
                <li><a href="#">Escribir PM</a></li>
            <?php endif; ?>
            <?php if($usuario_perfil === TRUE): ?>
                <li><a href="#">Favoritos</a></li>
                <li><a href="#">PM</a></li>
            <?php endif; ?>
        </ul>
        <div class="tabdemo__content content">
            <div class="tabdemo__content-item">
                <div class="row">
                    <?php if( ! empty($articulos_usuarios)): ?>
                        <?php foreach ($articulos_usuarios as $v): ?>
                            <div class="large-6 columns left">
                                <div class="">
                                    <?= anchor('/articulos/buscar/' . $v['id'],
                                        img('/imagenes_articulos/' . $v['id'] . '_1' . '.jpg')) ?>
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
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <h3>El usuario <?= $usuario['nick'] ?> no tiene productos a la venta actualmente >.<</h3>
                    <?php endif; ?>
                </div>
            </div>
            <div class="tabdemo__content-item">
                <div class="row">
                    <?php foreach ($articulos_vendidos as $v): ?>
                        <div class="large-6 columns left">
                            <div class="">
                                <?= anchor('/articulos/buscar/' . $v['articulo_id'],
                                            img('/imagenes_articulos/' . $v['articulo_id'] . '_1' . '.jpg')) ?>
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
                            <div class="valoracion" value="<?= $v['valoracion'] ?>">
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php if($usuario_perfil !== TRUE): ?>
                <div class="tabdemo__content-item">
                    <div class="row">
                        <?php if ( ! empty(error_array())): ?>
                            <div data-alert class="alert-box alert radius alerta">
                              <?= validation_errors() ?>
                              <a href="#" class="close">&times;</a>
                            </div>
                        <?php endif ?>
                        <?= form_open('/usuarios/insertar_pm/' . $usuario['id']) ?>
                            <?= form_hidden('emisor_id', $usuario_propio['id'], 'id="emisor_id" class=""') ?>
                            <div class="nick-field">
                              <?= form_label('Mensaje:', 'mensaje') ?>
                              <?= form_textarea('mensaje', set_value('mensaje', '', FALSE),
                                             'id="mensaje" class=""') ?>
                            </div>
                            <?= form_submit('enviar', 'Enviar', 'class="success button small radius"') ?>
                        <?= form_close() ?>
                    </div>
                </div>
            <?php endif; ?>
            <?php if($usuario_perfil === TRUE): ?>
                <div class="tabdemo__content-item">
                    <div class="row">
                        <?php foreach ($articulos_favoritos as $v): ?>
                            <div class="large-6 columns left">
                                <div class="">
                                    <?= anchor('/articulos/buscar/' . $v['id'],
                                                img('/imagenes_articulos/' . $v['id'] . '_1' . '.jpg')) ?>
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
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="tabdemo__content-item">
                    <?php foreach ($pm as $v): ?>
                        <div class="row">
                            <div class="">
                                <?php if(file_exists('/imagenes_usuarios/' . $v['emisor_id'] . '.jpg')): ?>
                                    <?php $url = '/imagenes_usuarios/' . $v['emisor_id'] . '.jpg' ?>
                                <?php else: ?>
                                    <?php $url = '/imagenes_usuarios/sin-imagen.jpg' ?>
                                <?php endif; ?>
                                <?= anchor('/usuarios/perfil/' . $v['emisor_id'],
                                            img(array(
                                                'src' => $url,
                                                'title' => $v['nick_emisor'],
                                                'alt' => $v['nick_emisor'],
                                                'class' => 'imagen_nick',
                                            ))) ?>
                                <?= anchor('/usuarios/perfil/' . $v['emisor_id'], $v['nick_emisor']) ?>
                            </div>
                            <div class="">
                                <?= $v['mensaje'] ?>
                            </div>
                            <div class="">
                                <?= $v['fecha_mensaje'] ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="large-4 columns">
        <div class="">
            <?php if($usuario['latitud'] !== NULL && $usuario['longitud'] !== NULL): ?>
                <div id="map" class="mapa_perfil"></div>
            <?php else: ?>
                El usuario <?= $usuario['nick'] ?> no ha aceptado dar su ubicacion.
            <?php endif; ?>
        </div>
    </div>
</div>
<script type="text/javascript">
    var valores_defecto = {
        numStars: 5,
        maxValue: 5,
        fullStar: true,
        readOnly: true,
    };
    $(function () {
        $(".valoracion").rateYo(valores_defecto);
        $('.valoracion').each(function(index) {
            $(this).rateYo("rating", parseInt($(this).attr("value")));
        });
    });
</script>
<script>
    $(window).load(function(){
        $(".tabs").tabtab({
            animateHeight: !1,
            fixedHeight: !1
        }), $(".tabdemo--one").tabtab({
            animateHeight: !0,
            fixedHeight: !1,
            scale: 1,
            rotateX: 0,
            rotateY: 90,
            speed: 500,
            transformOrigin: "center left",
            easing: "easeInOutCubic",
            translateX: 0,
            tabMenu: ".tabdemo__menu",
            tabContent: ".tabdemo__content",
            startSlide: 1
        }), $(".package-managers-toggle").toggle({
            "class": "open",
            target: !1
        })
    });
</script>
<script>
  var map;
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

    if (navigator.geolocation){
        navigator.geolocation.getCurrentPosition(mostrarLocalizacion, manejadorDeError);
    }
    else {
        alert("Su navegador no soporta Geolocalizacion");
    }
  }

  function mostrarLocalizacion(posicion){
        <?php if($usuario['latitud'] !== NULL): ?>
            var latitud = <?= $usuario['latitud'] ?>;
        <?php else: ?>
            var latitud = 40.4155;
        <?php endif; ?>

        <?php if($usuario['longitud'] !== NULL): ?>
            var longitud = <?= $usuario['longitud'] ?>;
        <?php else: ?>
            var longitud = -3.6968;
        <?php endif; ?>

        var pos = new google.maps.LatLng(latitud, longitud);
        map.setCenter(pos);

        var marker = new google.maps.Marker({
            position: pos,
            map: map,
            title: "<?= $usuario['nick'] ?> está aquí >.<",
        });
        // $.ajax({
        //     url: "usuarios/usuarios_cercanos/",
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
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDY6aARD3BZGp4LD2RhzefUdfSIy4mqvzU&callback=initMap"
async defer></script>
