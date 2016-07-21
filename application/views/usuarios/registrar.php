<?php template_set('title', 'Registro') ?>

<div class="row">
    <div class="large-6 large-centered columns">
          <?php if (count(error_array()) > 0): ?>
            <div data-alert class="alert-box alert radius alerta">
              <?= validation_errors() ?>
              <a href="#" class="close">&times;</a>
            </div>
          <?php endif ?>
          <?= form_open('/usuarios/registrar', 'itemscope itemtype="http://schema.org/Person"') ?>
            <div class="">
              <?= form_label('Nick:', 'nick') ?>
              <?= form_input('nick', set_value('nick', '', FALSE),
                             'id="nick" class="" itemprop="additionalName"') ?>
            </div>
            <div class="">
              <?= form_label('Email:', 'email') ?>
              <?= form_input(array(
                            'type' => 'email',
                            'name' => 'email',
                            'id' => 'email',
                            'value' => set_value('email', '', FALSE),
                            'class' => '',
                            'itemprop' => 'email'
              )) ?>
            </div>
            <div class="">
              <?= form_label('Contraseña:', 'password') ?>
              <?= form_password('password', '',
                                'id="password" class=""') ?>
            </div>
            <div class="">
              <?= form_label('Confirmar Contraseña:', 'password_confirm') ?>
              <?= form_password('password_confirm', '',
                                'id="password_confirm" class=""') ?>
            </div>
            <div class="" itemscope itemtype="http://schema.org/GeoCoordinates">
                <div class="">
                  <?= form_label('Localización:', 'localizacion') ?>
                  <?= form_input('localizacion', '',
                                 'id="localizacion" disabled="disabled" class=""
                                  itemprop="address"') ?>
                </div>
                <div class="">
                    <input type="hidden" name="latitud" value="null" itemprop="latitude">
                    <input type="hidden" name="longitud" value="null" itemprop="longitude">
                    <?= form_checkbox('geolocalizacion', ""); ?>
                    <?= form_label('Desea usted dar su ubicación', 'geolocalizacion') ?>
                </div>
            </div>
            <?= form_submit('registrar', 'Registrar', 'class="success button small radius"') ?>
            <?= anchor('/usuarios/login', 'Volver', 'class="alert button small radius" role="button"') ?>
          <?= form_close() ?>
    </div>
</div>

<div id="error_geolocalizacion" class="reveal-modal"
     data-reveal aria-labelledby="Error"
     aria-hidden="true" role="dialog">
  <h2 id="firstModalTitle">Error de Geolocalización</h2>
  <p>
     Perdone, pero hemos detectado que en su navegador, usted tiene prohibida la
     geolocalización. Por ello, si quiere usted establecer la gelolocalizacion
     de forma personal, clicke <a href="#" rel='pop-up'>aquí</a>.
  </p>
  <a class="close-reveal-modal" aria-label="Close">&#215;</a>
</div>

<script type="text/javascript">
    $('#nick').change(function () {
        $.ajax({
            url: "<?= base_url() ?>usuarios/usuarios_nick/" + $(this).val(),
            type: 'GET',
            async: true,
            success: respuesta,
            error: error,
            dataType: "json"
        });
    });
    $('#email').focusout(function () {
        $.ajax({
            url: "<?= base_url() ?>usuarios/usuarios_email/" + encodeURIComponent($(this).val()),
            type: 'GET',
            async: true,
            success: respuesta_email,
            error: error,
            dataType: "json"
        });
    });

    function respuesta(respuesta) {
        $("#nick").siblings().remove(".error");
        if(respuesta.nick_ocupado == true) {
            var small = '<small class="error">';
            small += 'Nick inválido, por favor, escoja otro.';
            small += '<br>Sugerencias:<br>';
            for (var sugerencia in respuesta.sugerencias_nick) {
                var sugerencia = respuesta.sugerencias_nick[sugerencia];
                small += '<br>'+sugerencia+'</br>';
            }
            small += '</small>';
            $("#nick").after(small);
        }
    }
    function respuesta_email(respuesta) {
        $("#email").siblings().remove(".error");
        if(respuesta.email_ocupado == true) {
            var small = '<small class="error">';
            small += 'Email inválido, por favor, escoja otro.';
            small += '</small>';
            $("#email").after(small);
        }
    }
    function error(error) {
        alert("Ha ocurrido el error => " + error.statusText);
    }
</script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDY6aARD3BZGp4LD2RhzefUdfSIy4mqvzU&libraries=places"
async defer></script>
<script type="text/javascript">
    $("input[name=geolocalizacion]").click(function () {
        if($(this).is(':checked')) {
            navigator.geolocation.getCurrentPosition(function (posicion) {
                var latitud = posicion.coords.latitude;
                var longitud = posicion.coords.longitude;

                var geocoder = new google.maps.Geocoder();
                geocoder.geocode({'latLng': new google.maps.LatLng(latitud, longitud)}, function(results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        var address = results[0]['formatted_address'];
                        $("#localizacion").val(address);
                    }
                });
                $("input[name=latitud]").first().val(latitud);
                $("input[name=longitud]").first().val(longitud);
            }, function (error) {
                $('#error_geolocalizacion').foundation('reveal', 'open');
            });

        } else {
            $("input[name=latitud]").first().val("null");
            $("input[name=longitud]").first().val("null");
            $("#localizacion").val("");
        }
    });
</script>

<script type="text/javascript">
    $("a[rel='pop-up']").click(function (evento) {
        evento.preventDefault();

      	var caracteristicas = "height=700,width=800,scrollTo,resizable=1,scrollbars=1,location=0";
      	nueva = window.open('<?= base_url() ?>usuarios/ubicacion_manual',
                            'Ubicación Manual', caracteristicas);
    });
    function cerrar_modal() {
        $('#error_geolocalizacion').foundation('reveal', 'close');
    }
</script>
