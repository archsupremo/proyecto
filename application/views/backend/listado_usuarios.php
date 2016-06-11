<?php template_set('title', 'Listado Usuarios') ?>

<div class="row">
    <div class="large-9 large-centered columns menu-login">
        <?php if ( ! empty(error_array())): ?>
            <div data-alert class="alert-box alert radius alerta">
              <?= validation_errors() ?>
              <a href="#" class="close">&times;</a>
            </div>
        <?php endif ?>
        <table class="hover">
            <thead>
                <th>Nick</th>
                <th>Email</th>
                <th>Registro Verificado</th>
                <th>Baneado</th>
                <th>Ip</th>
                <th colspan="2">Acciones</th>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $usuario): ?>
                    <tr>
                        <td><?= $usuario['nick'] ?></td>
                        <td><?= $usuario['email'] ?></td>
                        <td><?= ($usuario['registro_verificado'] === 't') ? 'Sí': 'No' ?></td>
                        <td><?= ($usuario['baneado'] === 't') ? 'Sí': 'No' ?></td>
                        <td><?= $usuario['ip'] ?></td>
                        <td>
                            <?= anchor('/backend/usuarios/listado_usuarios', 'Banear Usuario',
                                       'class="alert button tiny radius banear_usuario"
                                        role="button" id="'.$usuario['id'].'"')
                            ?>
                        </td>
                        <td>
                            <?= anchor('/backend/usuarios/listado_usuarios', 'Banear Ip',
                                       'class="alert button tiny radius banear_ip"
                                        role="button" id="'.$usuario['id'].'"')
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script type="text/javascript">
    $(".banear_usuario").click(function (evento) {
        evento.preventDefault();
        var td = $(this).parent();
        var usuario_id = $(this).prop('id');

        $.ajax({
            url: "<?= base_url() ?>backend/usuarios/banear_usuario/" + usuario_id,
            type: 'POST',
            async: true,
            success: function(response) {
                td.prev().prev().text("Sí");
            },
            error: function (error) {
            },
        });
    });
    $(".banear_ip").click(function (evento) {
        evento.preventDefault();
        var td = $(this).parent();
        var usuario_id = $(this).prop('id');

        $.ajax({
            url: "<?= base_url() ?>backend/usuarios/banear_ip/" + usuario_id,
            type: 'POST',
            async: true,
            success: function(response) {
            },
            error: function (error) {
            },
        });
    });
</script>
