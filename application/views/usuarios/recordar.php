<?php template_set('title', 'Recordar') ?>

<div class="row">
    <div class="large-6 large-centered columns menu-login">
        <?php if ( ! empty(error_array())): ?>
            <div data-alert class="alert-box alert radius alerta">
              <?= validation_errors() ?>
              <a href="#" class="close">&times;</a>
            </div>
        <?php endif ?>
        <?= form_open('/usuarios/recordar/') ?>
          <div class="form-group">
            <?= form_label('Nick:', 'nick') ?>
            <?= form_input('nick', set_value('nick', '', FALSE),
                           'id="nick" class="form-control"') ?>
          </div>
          <?= form_submit('recordar', 'Recordar Contraseña', 'class="success button small radius"') ?>
          <?= anchor('/usuarios/login/', 'Volver', 'class="alert button small radius" role="button"') ?>
        <?= form_close() ?>
    </div>
</div>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
