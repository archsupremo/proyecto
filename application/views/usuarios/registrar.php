<?php template_set('title', 'Registro') ?>

<div class="row">
    <div class="large-6 large-centered columns menu-login">
          <?php if ( ! empty(error_array())): ?>
            <div data-alert class="alert-box alert radius alerta">
              <?= validation_errors() ?>
              <a href="#" class="close">&times;</a>
            </div>
          <?php endif ?>
          <?= form_open('/usuarios/registrar') ?>
            <div class="">
              <?= form_label('Nick:', 'nick') ?>
              <?= form_input('nick', set_value('nick', '', FALSE),
                             'id="nick" class=""') ?>
            </div>
            <div class="">
              <?= form_label('Email:', 'email') ?>
              <?= form_input(array(
                            'type' => 'email',
                            'name' => 'email',
                            'id' => 'email',
                            'value' => set_value('email', '', FALSE),
                            'class' => ''
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
            <div class="">
                <?= form_hidden('latitud', '') ?>
                <?= form_hidden('longitud', '') ?>
                <?= form_checkbox('geolocalizacion', "", TRUE); ?>
                <?= form_label('Desea usted dar su ubicación', 'geolocalizacion') ?>
            </div>
            <?= form_submit('registrar', 'Registrar', 'class="success button small radius"') ?>
            <?= anchor('/usuarios/login', 'Volver', 'class="alert button small radius" role="button"') ?>
          <?= form_close() ?>
    </div>
</div>
<script type="text/javascript">
    navigator.geolocation.getCurrentPosition(function (posicion) {
        var latitud = posicion.coords.latitude;
        var longitud = posicion.coords.longitude;

        $("input[name=latitud]").first().val(latitud);
        $("input[name=longitud]").first().val(longitud);

        $("input[name=geolocalizacion]").click(function () {
            if($(this).is(':checked')) {
                $("input[name=latitud]").first().val(latitud);
                $("input[name=longitud]").first().val(longitud);
            } else {
                $("input[name=latitud]").first().val("");
                $("input[name=longitud]").first().val("");
            }
        });
    }, function(error) {
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
    });
</script>
