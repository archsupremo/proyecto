<?php template_set('title', 'Portada') ?>

<div class="row medium-uncollapse large-collapse">
    <?php foreach ($articulos as $k => $v): ?>
        <div class="large-3 columns left articulos" id="<?= $v['id'] ?>">
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
                <div class="favorito <?= ($v['favorito'] === "t") ? 'es_favorito' : 'no_favorito' ?>">

                </div>
                <?= anchor('/usuarios/perfil/' . $v['usuario_id'], $v['nick']) ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<script type="text/javascript">
    var valores_defecto = {
        numStars: 1,
        maxValue: 1,
        fullStar: true,
        <?php if(!logueado()): ?>
            readOnly: true,
        <?php endif; ?>
    };
    $(function () {
        $(".favorito").rateYo(valores_defecto);
        $('.es_favorito').rateYo("rating", 1);
        $('.favorito').rateYo().on("rateyo.set", function (e, data) {
            var articulo_id = $(this).parent().parent().attr("id");
            $.ajax({
                url: "<?= base_url() ?>articulos/favoritos/" + articulo_id,
                type: 'POST',
                async: true,
                success: function() {
                    //alert("Todo ha ido bien.");
                },
                error: function (error) {
                    //alert("Error" + error.status);
                },
            });
        });
    });
    $(".favorito").click(function () {
        var valor = parseInt($(this).rateYo("rating"));

        if (valores_defecto.readOnly == undefined) {
            if(valor != 0) {
                $(this).rateYo("destroy");
                $(this).rateYo(valores_defecto);
                $(this).rateYo("rating", 0);
            }
        }
    });
    if (valores_defecto.readOnly != undefined) {
        $(".favorito").css("cursor", "not-allowed");
        $(".favorito").attr("title", "Logueate para añadir a favoritos");
    }
</script>
