<form class="" action="/usuarios/login/" method="post">
    <div class="row">
        <div class="large-6 large-centered columns menu-login">
            <?php if ( ! empty(error_array())): ?>
                <div data-alert class="alert-box alert radius alerta">
                  <?= validation_errors() ?>
                  <a href="#" class="close">&times;</a>
                </div>
            <?php endif ?>
            <?= form_open('/usuarios/login') ?>
            <div class="nick-field">
              <?= form_label('Nick:', 'nick') ?>
              <?= form_input('nick', set_value('nick', '', FALSE),
                             'id="nick" class=""') ?>
            </div>
            <div class="">
              <?= form_label('Contraseña:', 'password') ?>
              <?= form_password('password', '',
                                'id="password" class=""') ?>
            </div>
            <?= form_submit('login', 'Login', 'class="success button"') ?>
            <?= anchor('/usuarios/recordar', 'Recordar Contraseña', 'class="button" role="button"') ?>
            <?= anchor('/usuarios/registrar', 'Registrame', 'class="button" role="button"') ?>
            <?= form_close() ?>
        </div>
    </div>
</form>
