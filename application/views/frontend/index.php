<?php template_set('title', 'Portada') ?>

<div class="row">
    <section class="large-3 columns">
        <?= form_open('/frontend/portada/index', 'method="GET"') ?>
        <section class="orden">
            <article class="row precio">
                <h3>Ordenar</h3>
                <?= form_radio('order', '', set_radio('order', $order_busqueda, $order_busqueda === '')) ?>
                    Por novedad
                <br>
                <?= form_radio('order', 'prox', set_radio('order', $order_busqueda, $order_busqueda === 'prox')) ?>
                    Por Proximidad
                <br>
                <?= form_radio('order', 'precio_asc', set_radio('order', $order_busqueda, $order_busqueda === 'precio_asc')) ?>
                    De menor a mayor precio
                <br>
                <?= form_radio('order', 'precio_desc', set_radio('order', $order_busqueda, $order_busqueda === 'precio_desc')) ?>
                    De mayor a menor precio
            </article>
            <article class="row distancia">
                <h3>Buscar por distancia</h3>
                <?= form_radio('order_distancia', '0', set_radio('order_distancia', $order_distancia, TRUE)) ?>
                    Sin limite de distancia
                <br>
                <?= form_radio('order_distancia', '1000', set_radio('order_distancia', $order_distancia, $order_distancia === '1000')) ?>
                    A 1km de ti
                <br>
                <?= form_radio('order_distancia', '5000', set_radio('order_distancia', $order_distancia, $order_distancia === '5000')) ?>
                    A 5km de ti
                <br>
                <?= form_radio('order_distancia', '10000', set_radio('order_distancia', $order_distancia, $order_distancia === '10000')) ?>
                    A 10km de ti
            </article>
        </section>
        <section>
            <article class="row">
                <h3>Búsqueda personalizada</h3>
                <fieldset>
                     <legend>Búsqueda Personalizada</legend>
                     <div class="row">
                      <div class="large-12 columns ui-widget">
                          <?= form_label('Nombre articulo:', 'nombre') ?>
                          <?= form_input(array(
                                        'type' => 'search',
                                        'name' => 'nombre',
                                        'id' => 'nombre',
                                        'value' => set_value('nombre', $nombre_busqueda, FALSE),
                                        'class' => '',
                                        'placeholder' => 'Buscar por nombre...'
                          )) ?>

                          <?= form_label('Etiquetas:', 'tags') ?>
                          <?= form_input(array(
                                        'type' => 'search',
                                        'name' => 'tags',
                                        'id' => 'tags',
                                        'value' => set_value('tags', $tags_busqueda, FALSE),
                                        'class' => '',
                                        'placeholder' => 'Buscar por etiquetas...'
                          )) ?>

                          <?= form_hidden('latitud', '40.4168') ?>
                          <?= form_hidden('longitud', '-3.7038') ?>
                          <br>
                          <?= form_submit('', 'Buscar', 'class="success button tiny radius"') ?>
                      </div>
                     </div>
                 </fieldset>
            </article>
        </section>
        <?= form_close() ?>
    </section>
    <section class="large-6 columns" id="centro">
        <?php if(empty($articulos)): ?>
            <section class="row">
                <article class="large-12 large-centered columns">
                    <img src="/img/nada.jpg" alt="" />
                </article>
                <article class="large-5 large-centered columns">
                    <p>
                        No hay nada por aqui >.<
                    </p>
                </article>
                <article class="large-4 large-centered columns">
                    <?= anchor('/frontend/portada', 'Ver productos',
                               'class="success button small radius"') ?>
                </article>
            </section>
        <?php endif; ?>
        <?php foreach ($articulos as $v): ?>
            <article class="large-4 columns left articulos" id="<?= $v['articulo_id'] ?>"
                     itemscope itemtype="http://schema.org/Product">
                <div class="" itemprop="logo">
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
                <div class="" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                    <span itemprop="priceCurrency"><?= $v['precio'] ?></span>
                    <span class="oculto" itemprop="price"><?= preg_replace('/,/', '.', substr($v['precio'], 0 , -4)) ?></span>
                </div>
                <h6 class="" itemprop="name">
                    <?= anchor('/articulos/buscar/' . $v['articulo_id'], $v['nombre']) ?>
                </h6>
                <div class="" itemprop="category">
                    <?php foreach (preg_split('/,/', $v['etiquetas']) as $etiqueta): ?>
                        <?php if($etiqueta === '') break; ?>
                        <?= anchor('/frontend/portada/index?tags='.$etiqueta, $etiqueta,
                                   'class="button tiny radius"') ?>
                    <?php endforeach; ?>
                </div>
                <div class="favorito <?= ($v['favorito'] === "t") ? 'es_favorito' : 'no_favorito' ?>">
                </div>
                <div class="" itemprop="brand">
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
                <br>
                <?php if(es_admin()): ?>
                    <?= anchor('#', 'Retirar articulo',
                               'class="alert button tiny radius"
                                data-reveal-id="articulo_'.$v['id'].'"') ?>
                    <div id="articulo_<?= $v['articulo_id'] ?>" class="reveal-modal"
                        data-reveal aria-labelledby="modalTitle"
                        aria-hidden="true" role="dialog">
                      <h2 id="modalTitle">¿Está totalmente seguro de retirar el articulo <?= $v['nombre'] ?>
                          del usuario <?= $v['nick'] ?>?</h2>
                      <p class="lead">El retirado sera definitivo y no se podrá recuperar el articulo.</p>
                      <?= anchor('/articulos/retirar_articulo/' . $v['articulo_id'],
                                 'Sí, retirar el articulo.',
                                 'class="success button small radius" role="button"') ?>
                      <a class="close-reveal-modal" aria-label="Close">&#215;</a>
                    </div>
                <?php endif; ?>
            </article>
        <?php endforeach; ?>
    </section>
    <section class="large-3 columns mapa">
        <h4>¿Quién esta vendiendo a mi alrededor?</h4>
        <input id="pac-input" class="controls" type="text" placeholder="Buscar por ciudad, región, país...">
        <div class="mapa_index" id="map"></div>
        <br>
        <div class="row">
            <?= form_radio('distancia_usuario', '1000', TRUE) ?>
                A 1km de ti
            <?= form_radio('distancia_usuario', '5000', FALSE) ?>
                A 5km de ti
            <?= form_radio('distancia_usuario', '10000', FALSE) ?>
                A 10km de ti
        </div>
    </section>
</div>

<div class="row masArticulos">
    <div class="large-1 large-centered columns">
        <?php if( ! empty($articulos)): ?>
            <img src="/img/mas.jpg" class="th" id="mas" alt="Mas productos" />
        <?php endif; ?>
    </div>
</div>

<style media="screen">
    .row { max-width: 95%; }
</style>

<script type="text/javascript" >
    $("#centro").find("img").load(function () {
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
            var articulo_id = $(this).parent().attr("id");
            $.ajax({
                url: "<?= base_url() ?>articulos/favoritos/" + articulo_id,
                type: 'POST',
                async: true,
                success: function(response) {},
                error: function (error) {},
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
    var limite = 10;
    var scrollInfinito = false;
    var articulos_viejos = [];

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

    function existeImagenArticulo(nombre) {
       var existe = false;
       $.ajax({
           url: "<?= base_url() ?>articulos/existe_imagen/" + nombre,
           type: 'POST',
           data: {},
           async: false,
           cache: false,
           success: function (response) {
               existe = response.existe;
           },
           error: function (error) {},
           dataType: 'json'
       });
       return existe;
    }

    function existeImagenUsuario(nombre) {
        var existe = false;
       $.ajax({
           url: "<?= base_url() ?>usuarios/existe_imagen/" + nombre,
           type: 'POST',
           async: false,
           cache: false,
           data: {},
           success: function (response) {
               existe = response.existe;
           },
           error: function (error) {},
           dataType: 'json'
       });
       return existe;
    }

    function masArticulos(limite) {
        var url_search = window.location.search;
        $.each($('#centro').children("article"), function(index, val) {
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
                    var div = '<article class="large-4 columns left articulos" id="'
                              + response[producto].articulo_id + '">';
                            div += '<div class="">';
                                div += '<a href="/articulos/buscar/' + response[producto].articulo_id + '">';

                                var nombre_imagen =
                                (existeImagenArticulo(response[producto].articulo_id+'_1.jpg')) ?
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
                                        +response[producto].nombre+'</a>';
                            div += '</div>';
                            if(response[producto].etiquetas != null) {
                                var etiquetas = response[producto].etiquetas.split(",");
                                for(var i = 0; i < etiquetas.length; i++) {
                                    if(etiquetas[i] == '') break;
                                    div += '<a href="/frontend/portada/index?tags='
                                            +etiquetas[i]+'"'
                                            +' class="button tiny radius">'
                                            +etiquetas[i]+'</a>';
                                }
                            }
                            div += '<div class="favorito"></div>';
                            div += '<div class="">';
                                div += '<a href="/usuarios/perfil/'+response[producto].usuario_id+'">';

                                var nombre_imagen =
                                (existeImagenUsuario(response[producto].usuario_id+'.jpg')) ?
                                (response[producto].usuario_id+'.jpg') : 'sin-imagen.jpg';

                                div += '<img class="imagen_nick" src="/imagenes_usuarios/'
                                        +nombre_imagen+
                                        '" alt="'+response[producto].nick+
                                        '" title="'+response[producto].nick+'" />';
                                div += '</a>';
                                div += '<a href="/usuarios/perfil/'+response[producto].usuario_id+'">'+response[producto].nick+'</a>';
                            div += '</div>';
                        div += '</article>';

                    $('#centro').append(div);
                    var valores_defecto = {
                        numStars: 1,
                        maxValue: 1,
                        fullStar: true,
                        <?php if(!logueado()): ?>
                            readOnly: true,
                        <?php endif; ?>
                    };
                    $(function () {
                        $(".favorito").last().rateYo(valores_defecto);
                        // $('.es_favorito').rateYo("rating", 1);
                        $('.favorito').last().rateYo().on("rateyo.set", function (e, data) {
                            var articulo_id = $(this).parent().attr("id");
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
                    $("#centro").find("img").load(function () {
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
  var usuario_id = <?= logueado() ? dar_usuario()['id'] : 'undefined' ?>;
  var map;
  var zoom;
  var markers_propios = [];
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
    map.controls[google.maps.ControlPosition.TOP].push(input);

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
            draw_circle.setCenter(pos);

              switch (draw_circle.getRadius()) {
                  case 1000:
                      map.setZoom(14);
                    break;
                  case 5000:
                      map.setZoom(12);
                    break;
                  case 10000:
                      map.setZoom(11);
                    break;
                  default:
                      map.setZoom(15);
                    break;
              }
            cookie(latitud, longitud);
            // $.cookie('latitud', latitud, { expires: 999, path:'/' });
            // $.cookie('longitud', longitud, { expires: 999, path:'/' });

            $('input[name="latitud"]').first().val(latitud);
            $('input[name="longitud"]').first().val(longitud);
            obtener_usuarios(latitud, longitud, draw_circle.getRadius());
            position_changed = undefined;
        }
    });
  }

  function mostrarLocalizacion(posicion){
        latitud = posicion.coords.latitude;
        longitud = posicion.coords.longitude;

        if($.cookie('latitud') != undefined && $.cookie('longitud') != undefined) {
            latitud = $.cookie('latitud');
            longitud = $.cookie('longitud');

            $('input[name="latitud"]').first().val(latitud);
            $('input[name="longitud"]').first().val(longitud);
        }
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
          //   animation: google.maps.Animation.BOUN1CE,
      });
      var geocoder = new google.maps.Geocoder();
      marker_yo.addListener('dragend', function (e) {
          geocoder.geocode({'latLng': this.getPosition()}, function(results, status) {
              if (status == google.maps.GeocoderStatus.OK) {
                  var address=results[0]['formatted_address'];
                  var latitud = results[0]['geometry']['location'].lat();
                  var longitud = results[0]['geometry']['location'].lng();

                  pos = new google.maps.LatLng(latitud, longitud);
                  map.setCenter(pos);
                  draw_circle.setCenter(pos);

                  cookie(latitud, longitud);
                //   $.cookie('latitud', latitud, { expires: 999, path:'/' });
                //   $.cookie('longitud', longitud, { expires: 999, path:'/' });

                  $('input[name="latitud"]').first().val(latitud);
                  $('input[name="longitud"]').first().val(longitud);
              }
          });
      });
      draw_circle = new google.maps.Circle({
          center: pos,
          radius: 1000,
          strokeColor: "#FF0000",
          strokeOpacity: 0.6,
          strokeWeight: 2,
          fillOpacity: 0.1,
          map: map,
      });
      draw_circle.addListener('center_changed', function (e) {
          var latitud = this.center.lat();
          var longitud = this.center.lng();
          obtener_usuarios(latitud, longitud, this.getRadius());
      });
      draw_circle.addListener('radius_changed', function (e) {
          var latitud = this.center.lat();
          var longitud = this.center.lng();
          obtener_usuarios(latitud, longitud, this.getRadius());
      });
      obtener_usuarios(latitud, longitud, draw_circle.getRadius());
  }

  function obtener_usuarios(latitud, longitud, radius) {
      for (var marker in markers_propios) {
          markers_propios[marker].setMap(null);
      }
      $.ajax({
          url: "<?= base_url() ?>usuarios/usuarios_cercanos/" +
                latitud + "/" + longitud + "/" + radius,
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

    if($.cookie('latitud') != undefined && $.cookie('longitud') != undefined) {
        latitud = $.cookie('latitud');
        longitud = $.cookie('longitud');

        $('input[name="latitud"]').first().val(latitud);
        $('input[name="longitud"]').first().val(longitud);
    }
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

  function respuesta(respuesta) {
      var infowindow = new google.maps.InfoWindow();

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
              clickable: true
          });
          markers_propios.push(marker);
          marker.setMap(map);

          var contentString = '<div id="content">'+
          '<div id="siteNotice">'+
          '</div>'+
          '<div id="bodyContent">'+
          '<p>Articulos disponibles: '+usuario.articulos_disponibles+'</p>'+
          '<p>Ventas: '+usuario.ventas+'</p>'+
          '<p>Valoraciones: '+usuario.valoraciones+'</p>'+
          '<p><a href="<?= base_url() ?>usuarios/perfil/'+usuario.id+'">'+
            'Ver perfil de '+usuario.nick+'</a></p>'+
          '</div>'+
          '</div>';

          (function(marker, contenido) {
            google.maps.event.addListener(marker, 'click', function() {
              infowindow.setContent(contenido);
              infowindow.open(map, marker);
            });
          })(marker, contentString);
      }
  }
  function error(error) {
      alert("Ha ocurrido el error => " + error.statusText);
  }
  function cookie(latitud, longitud) {
      $.cookie('latitud', latitud, { expires: 999, path:'/' });
      $.cookie('longitud', longitud, { expires: 999, path:'/' });
  }
  $('input[name="distancia_usuario"]').change(function () {
      draw_circle.setRadius(parseFloat($(this).val()));
  });
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDY6aARD3BZGp4LD2RhzefUdfSIy4mqvzU&libraries=places&callback=initMap"
async defer></script>
