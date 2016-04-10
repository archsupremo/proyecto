<?php template_set('title', 'Portada') ?>

<div class="row">
    <?php foreach ($articulos as $k => $v): ?>
        <div class="large-4 columns">
            <div class="">
                <?= anchor('/articulos/buscar/' . $v['id'], img('/imagenes_articulos/' . $v['id'] . '.jpg')) ?>
            </div>
            <div class="">
                <?= $v['precio'] ?>
            </div>
            <div class="">
                <?= anchor('/articulos/buscar/' . $v['id'], $v['nombre']) ?>
            </div>
            <div class="">
                <?= form_open('/frontend/portada/') ?>
                    <?= form_hidden('categoria', $v['categoria_id'],
                                   'id="categoria" class=""') ?>
                    <?= form_hidden('nombre', '',
                                   'id="nombre" class=""') ?>
                    <?= form_submit('buscar', $v['nombre_categoria'], 'class=""') ?>
                <?= form_close() ?>
            </div>
            <div class="">
                <?= anchor('/usuarios/perfil/' . $v['usuario_id'], $v['nick']) ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>
