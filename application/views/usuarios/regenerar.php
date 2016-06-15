<?php template_set('title', 'Regenerar') ?>

<div class="row">
    <div class="large-6 large-centered columns menu-login">
        <?php if ( ! empty(error_array())): ?>
            <div data-alert class="alert-box alert radius alerta">
              <?= validation_errors() ?>
              <a href="#" class="close">&times;</a>
            </div>
        <?php endif ?>
        <?= form_open("/usuarios/regenerar/$usuario_id/$token") ?>
            <div class="">
              <?= form_label('Contraseña:', 'password') ?>
              <?= form_password('password', set_value('password', '', FALSE),
                             'id="password" class="form-control"') ?>
            </div>
            <div class="">
              <?= form_label('Confirmar Contraseña:', 'password_confirm') ?>
              <?= form_password('password_confirm', set_value('password_confirm', '', FALSE),
                             'id="password_confirm" class="form-control"') ?>
            </div>
            <?= form_submit('regenerar', 'Regenerar Contraseña', 'class="success button small radius"') ?>
            <?= anchor('/usuarios/login', 'Volver', 'class="button small radius" role="button"') ?>
        <?= form_close() ?>
    </div>
</div>
<br>
<br>
<br>
<br>
<br>
<br>
