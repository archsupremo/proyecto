<?php template_set('title', 'Portada') ?>
<div class="container">
    <div class="large-3 columns">
        <h4>¿Quien esta vendiendo a tu alrededor?</h4>
        <input id="pac-input" class="controls" type="text" placeholder="Buscar por ciudad, región, país...">
        <div class="mapa_index" id="map"></div>
    </div>
    <div class="large-6 columns" id="centro">
        <?php foreach ($articulos as $v): ?>
            <div class="large-4 columns left articulos"
                 id="<?= $v['articulo_id'] ?>">
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
                                ))) ?>
                </div>
                <div class="">
                    <?= $v['precio'] ?>
                </div>
                <div class="">
                    <?= anchor('/articulos/buscar/' . $v['articulo_id'], $v['nombre'].$v['articulo_id']) ?>
                </div>
                <div class="">
                    <?= $v['etiquetas'] ?>
                </div>
                <div class="">
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
    <div class="large-3 columns">
        <?= form_open('/frontend/portada/index', 'method="GET"') ?>
        <h3>Ordenar por distancia</h3>
            <div class="row">
                <?= form_radio('order_distancia', '1000', set_value('order_distancia')) ?>
                    A 1km de ti
                <br>
                <?= form_radio('order_distancia', '5000', set_value('order_distancia')) ?>
                    A 5km de ti
                <br>
                <?= form_radio('order_distancia', '10000', set_value('order_distancia')) ?>
                    A 10km de ti
                <br>
                <?= form_radio('order_distancia', '0', set_value('order_distancia')) ?>
                    Sin limite de distancia
            </div>
        <h3>Ordenar por precio</h3>
            <div class="row">
                <?= form_radio('order_precio', 'asc', FALSE) ?>
                    De menor a mayor precio
                <br>
                <?= form_radio('order_precio', 'desc', FALSE) ?>
                    De mayor a menor precio
            </div>
        <h3>Búsqueda personalizada</h3>
        <div class="row">
             <fieldset>
                 <legend>Búsqueda Personalizada</legend>
                 <div class="row">
                  <div class="large-12 columns ui-widget">
                      <?= form_label('Nombre articulo:', 'nombre') ?>
                      <?= form_input('nombre', set_value('nombre', '', FALSE),
                                     'id="nombre" class="" placeholder="Buscar por nombre..."') ?>

                      <?= form_label('Etiquetas:', 'tags') ?>
                      <?= form_input('tags', set_value('tags', '', FALSE),
                                    'id="tags" class="" placeholder="Buscar por etiquetas..."') ?>

                      <?= form_hidden('latitud', '40.4168') ?>
                      <?= form_hidden('longitud', '-3.7038') ?>

                      <?= form_submit('', 'Buscar', 'class="success button tiny radius"') ?>
                  </div>
                 </div>
             </fieldset>
        </div>
    </div>
    <?= form_close() ?>
</div>

<div class="row masArticulos">
    <div class="large-1 large-centered columns">
        <img src="/img/mas.jpg" class="th" id="mas" alt="Mas productos" />
    </div>
</div>

<style media="screen">
    #centro {
        position: relative;
    }
    #centro > div {
        width: 30%;
        height: auto;
        position: absolute;
        border: 1px solid black;
    }
    .masArticulos {
        margin-bottom: 2em;
    }
</style>
<script type="text/javascript" >
    $('#centro').shapeshift({
        gutterY: 40,
        enableDrag: false,
        enableResize: false
    });
    $( window ).resize(function() {
        $("#centro").trigger("ss-destroy");
        $('#centro').shapeshift({
            enableDrag: false,
            enableResize: false
        });
    });
</script>

<script type="text/javascript">
    var limite = 10;
    var scrollInfinito = false;
    var articulos_viejos = [];
    // alert(window.location.search);

    $(window).scroll(function() {
        if(($(window).scrollTop() >= ($(document).height() - $("footer").first().height()) - $(window).height())
            && scrollInfinito) {
            masArticulos(limite);
        }
    });

    $('#mas').click(function () {
        masArticulos(limite);
        scrollInfinito = true;
        $(this).prop("src", "/img/loading.gif");
    });

    function existeUrl(url) {
       var http = new XMLHttpRequest();
       http.open('HEAD', url, false);
       http.send();
       return http.status!=404;
    }

    function masArticulos(limite) {
        var url_search = window.location.search;
        $.each($('#centro').children("div"), function(index, val) {
            if(articulos_viejos.indexOf($(this).prop("id")) == -1) {
                articulos_viejos.push($(this).prop("id"));
            }
        });
        scrollInfinito = false;
        $.ajax({
            url: "<?= base_url() ?>articulos/masArticulos/" + limite + url_search,
            type: 'POST',
            data: {
                articulos_viejos: articulos_viejos
            },
            async: true,
            success: function(response) {
                if(response.length == 0) {
                    $("#mas").remove();
                    return;
                }
                for(var producto in response) {
                    var div = '<div class="large-4 columns left articulos" id="'
                              + response[producto].articulo_id + '">';
                            div += '<div class="">';
                                div += '<a href="/articulos/buscar/' + response[producto].articulo_id + '">';

                                var nombre_imagen =
                                (existeUrl('/imagenes_articulos/'+response[producto].articulo_id+'_1.jpg')) ?
                                (response[producto].articulo_id+'_1.jpg') : 'sin-imagen.jpg';

                                div += '<img src="/imagenes_articulos/'
                                        +nombre_imagen+
                                        '" alt="'+response[producto].nombre+
                                        '" title="'+response[producto].nombre+'" />';
                                div += '</a>';
                            div += '</div>';
                            div += '<div class="">';
                                div += response[producto].precio;
                            div += '</div>';
                            div += '<div class="">';
                                div += '<a href="/articulos/buscar/'+response[producto].articulo_id+'">'
                                        +response[producto].nombre+response[producto].articulo_id+'</a>';
                            div += '</div>';
                            div += '<div class="">';
                                div += response[producto].etiquetas;
                            div += '</div>';
                            div += '<div class="">';
                                div += '<a href="/usuarios/perfil/'+response[producto].usuario_id+'">';

                                var nombre_imagen =
                                (existeUrl('/imagenes_usuarios/'+response[producto].usuario_id+'.jpg')) ?
                                (response[producto].usuario_id+'.jpg') : 'sin-imagen.jpg';

                                div += '<img class="imagen_nick" src="/imagenes_usuarios/'
                                        +nombre_imagen+
                                        '" alt="'+response[producto].nick+
                                        '" title="'+response[producto].nick+'" />';
                                div += '</a>';
                                div += '<a href="/usuarios/perfil/'+response[producto].usuario_id+'">'+response[producto].nick+'</a>';
                            div += '</div>';
                        div += '</div>';
                    $('#centro').append(div);
                    $("#centro").find("img").last().load(function () {
                        $(function() {
                            $("#centro").trigger("ss-destroy");
                            $('#centro').shapeshift({
                                enableDrag: false,
                                enableResize: false
                            });
                        });
                    });
                }
                scrollInfinito = true;
            },
            error: function (error) {
                alert(error.statusText);
            },
            dataType: "json"
        });
    }
</script>

<script type="text/javascript">
    $('#tags').tagEditor({
        placeholder: "Buscar por etiquetas...",
        autocomplete: {
            position: { collision: 'flip' },
            source: "<?= base_url() ?>etiquetas/buscar/"
        },
        forceLowercase: false
    });
</script>

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
                success: function(response) {
                },
                error: function (error) {
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

<script type="text/javascript">
  var usuario_id = <?= logueado() ? dar_usuario()['id'] : 'undefined' ?>;
  var map;
  var zoom;
  var markers_propios = [];
  var position_changed;
  var position_name;

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
            //var latitud = position_changed.lat();
            //var longitud = position_changed.lng();
            latitud = position_changed.lat();
            longitud = position_changed.lng();

            pos = new google.maps.LatLng(latitud, longitud);
            map.setCenter(pos);
            marker_yo.setMap(null);
            draw_circle.setCenter(pos);

            zoom = map.getZoom();
            for (var marker in markers_propios) {
                markers_propios[marker].setMap(null);
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
            position_changed = undefined;
        }
    });

    map.addListener('zoom_changed', function() {
        var radius = draw_circle.getRadius();
        for(i = 0; i < Math.abs(zoom - map.getZoom()); i++) {
            if(zoom > map.getZoom()) {
                radius *= 2;
            } else {
                radius /= 2;
            }
        }
        draw_circle.setRadius(radius);
        zoom = map.getZoom();
        for (var marker in markers_propios) {
            markers_propios[marker].setMap(null);
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
        dibujarMarker(latitud, longitud);
  }

  function dibujarMarker(latitud, longitud) {
      pos = new google.maps.LatLng(latitud, longitud);
      map.setCenter(pos);

      marker_yo = new google.maps.Marker({
          position: pos,
          map: map,
          draggable: true,
          title: "Tu estas aquí >.<"
      });
      var geocoder = new google.maps.Geocoder();
      marker_yo.addListener('dragend', function (e) {
          geocoder.geocode({'latLng': this.getPosition()}, function(results, status) {
              if (status == google.maps.GeocoderStatus.OK) {
                  var address=results[0]['formatted_address'];
                  // alert(address);

                  var latitud = results[0]['geometry']['location'].lat();
                  var longitud = results[0]['geometry']['location'].lng();

                  $('input[name="latitud"]').first().val(latitud);
                  $('input[name="longitud"]').first().val(longitud);
              }
          });
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
    latitud = 40.4168;
    longitud = -3.7038;
    dibujarMarker(latitud, longitud);
    switch(error.code) {
        case error.PERMISSION_DENIED:
            // alert("El usuario no permite compartir datos de geolocalizacion");
        break;

        case error.POSITION_UNAVAILABLE:
            alert("Imposible detectar la posicio actual");
        break;

        case error.TIMEOUT:
            // alert("La posicion debe recuperar el tiempo de espera");
        break;

        default:
            alert("Error desconocido");
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
          markers_propios.push(marker);
          marker.setMap(map);
      }
  }
  function error(error) {
      alert("Ha ocurrido el error => " + error.statusText);
  }
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDY6aARD3BZGp4LD2RhzefUdfSIy4mqvzU&libraries=places&callback=initMap"
async defer></script>
