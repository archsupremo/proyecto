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
                <?= anchor('/frontend/portada/buscar_por_categoria/' . $v['nombre_categoria'], $v['nombre_categoria']) ?>
            </div>
            <div class="">
                <?= anchor('/usuarios/perfil/' . $v['usuario_id'], $v['nick']) ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>
