<?php template_set('title', 'Editar Perfil de Usuario') ?>

<div class="row">
    <div class="large-6 large-centered columns menu-login">
        <?php if ( ! empty(error_array())): ?>
          <div data-alert class="alert-box alert radius alerta">
            <?= validation_errors() ?>
            <a href="#" class="close">&times;</a>
          </div>
        <?php endif ?>
        <?= form_open('/usuarios/editar_perfil/' . $usuario['id']) ?>
          <div class="">
            <?= form_label('Nick:', 'nick') ?>
            <?= form_input('nick', set_value('nick', $usuario['nick'], FALSE),
                           'id="nick" class="input_validation"') ?>
            <!-- <small class="error">Invalid entry</small> -->
          </div>
          <div class="">
            <?= form_label('Email:', 'email') ?>
            <?= form_input(array(
                          'type' => 'email',
                          'name' => 'email',
                          'id' => 'email',
                          'value' => set_value('email', $usuario['email'], FALSE),
                          'class' => ''
            )) ?>
            <!-- <small class="error">Invalid entry</small> -->
          </div>
          <div class="">
            <?= form_label('Contraseña Antigua:', 'password_old') ?>
            <?= form_password('password_old', '',
                              'id="password_old" class=""') ?>
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
          <div class="">
              <?= form_checkbox('confirm_venta_favorito', "", FALSE); ?>
              <?= form_label('Desea usted que se envie un mensaje de correo<br> '.
                             'electronico cuando un articulo favorito se venda',
                             'confirm_venta_favorito') ?>
          </div>
          <?= form_submit('editar', 'Editar', 'class="success button small radius"') ?>
          <?= anchor('/usuarios/login', 'Volver', 'class="alert button small radius" role="button"') ?>
        <?= form_close() ?>
    </div>
</div>

<script type="text/javascript">
    var usuario_id = <?= logueado() ? dar_usuario()['id'] : 'undefined' ?>;
    $('#nick').change(function () {
        $.ajax({
            url: "<?= base_url() ?>usuarios/usuarios_nick/" +
                 $(this).val() + "/" + usuario_id,
            type: 'GET',
            async: true,
            success: respuesta,
            error: error,
            dataType: "json"
        });
    });
    $('#email').focusout(function () {
        $.ajax({
            url: "<?= base_url() ?>usuarios/usuarios_email/" +
                 encodeURIComponent($(this).val()) + "/" + usuario_id,
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
