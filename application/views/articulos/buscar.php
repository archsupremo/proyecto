<?php template_set('title', 'Buscar Articulo') ?>

<div class="row">
    <div class="row large-6 columns menu-login">
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
</script>
