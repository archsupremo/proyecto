<?php template_set('title', 'Buscar Articulo') ?>

<div class="row fila_especia">
    <div class="row large-6 large-centered columns menu-login">
        <?php if ( ! empty(error_array())): ?>
            <div data-alert class="alert-box alert radius alerta">
              <?= validation_errors() ?>
              <a href="#" class="close">&times;</a>
            </div>
        <?php endif ?>
    </div>
    <div class="large-6 columns">
        <div class="">
            <?= anchor('/articulos/buscar/' . $articulo['id'], img('/imagenes_articulos/' . $articulo['id'] . '.jpg')) ?>
        </div>
        <form>
            <?php if (logueado()): ?>
                <label>Tu valoración</label>
                <input id="input-favorito" class="rating" data-min="0" data-max="1"
                data-step="1" value="<?= 0 ?>"
                data-show-clear="false" data-show-caption="false" data-size="xs">
            <?php endif; ?>
        </form>
    </div>
    <div class="large-4 columns">
        <h3>Datos del articulo</h3>
        <div class="alert-box info radius">
            <p>Precio => <?= $articulo['precio'] ?></p>
            <p>Nombre => <?= $articulo['nombre'] ?></p>
            <p>Descripcion => <?= $articulo['descripcion'] ?></p>
            <p>Categoria => <?= $articulo['nombre_categoria'] ?></p>
        </div>
        <div class="alert-box info radius">
            Geolocalizacion => >.<
        </div>
        <div class="alert-box info radius">
            Usuario => <?= anchor('/usuarios/perfil/' . $usuario['id'], $usuario['nick']) ?>
        </div>
        <div class="row text-center alert-box secondary radius">
            <h4>Otros productos de <?= $usuario['nick'] ?></h4>
            <?php if(!empty($articulos_usuarios)): ?>
                <?php foreach ($articulos_usuarios as $v): ?>
                    <div class="large-4 columns left">
                        <?= anchor('/articulos/buscar/' . $v['id'],
                                    img('/imagenes_articulos/' . $v['id'] . '.jpg'),
                                    'title="' . $v['nombre'] . '"') ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <h6>El usuario <?= $usuario['nick'] ?> no tiene mas productos a la venta >.<</h6>
            <?php endif; ?>
        </div>
    </div>
</div>
<script type="text/javascript">
    $("#input-favorito").change(favorito);

    function favorito() {
        var val = $(this).val();
        // $.getJSON("<?= base_url() ?>portal/juegos/valoracion/<?= usuario_id() ?>/<?= $juego['id'] ?>/" +
        //         val, enviar);
    }
    function enviar(r) {
        // $("#input-1").rating('update', r.total);
    }
</script>
