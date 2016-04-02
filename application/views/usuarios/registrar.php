<?php template_set('title', 'Registro') ?>

<div class="row">
    <div class="large-6 large-centered columns menu-login">
        <?php if ( ! empty(error_array())): ?>
            <div data-alert class="alert-box alert radius alerta">
              <?= validation_errors() ?>
              <a href="#" class="close">&times;</a>
            </div>
          <?php endif ?>
          <?= form_open('usuarios/registrar') ?>
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
            <?= form_submit('registrar', 'Registrar', 'class="success button small radius"') ?>
            <?= anchor('/usuarios/login', 'Volver', 'class="alert button small radius" role="button"') ?>
          <?= form_close() ?>
    </div>
</div>
