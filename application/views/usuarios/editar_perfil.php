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
                           'id="nick" class=""') ?>
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
          <?= form_submit('registrar', 'Registrar', 'class="success button small radius"') ?>
          <?= anchor('/usuarios/login', 'Volver', 'class="alert button small radius" role="button"') ?>
        <?= form_close() ?>
    </div>
</div>
