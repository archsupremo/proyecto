<?php template_set('title', 'Valorar Vendedor') ?>

<div class="row">
    <div class="row large-6 large-centered columns">
        <?php if (count(error_array()) > 0): ?>
            <div data-alert class="alert-box alert radius alerta">
              <?= validation_errors() ?>
              <a href="#" class="close">&times;</a>
            </div>
        <?php endif ?>
    </div>
    <div class="large-6 columns">
        <div class="wrapper">
            <div class="container">
                <div class="rt01 slider-nested-venta rt01timer-arcTop"
                    data-tabs='{
                        "isAutoInit"  : true,
                        "optionsPlus" : "slider",
                        "fx"          : "line",
                        "speed"       : 600,
                        "width"       : 940,
                        "height"      : 600,
                        "widthSlide"  : [ 0.95, [748, 768, 959], [940, 960, 3000] ],
                        "margin"      : [ 2, [5, 480, 767], [10, 768, 959], [30, 960, 3000] ],
                        "pag"         : { "sizeMarkTo": "padding" },
                        "imagePosition": "stretch",

                        "isLoop"      : true,
                        "isNav"       : false,
                        "isSlideshow" : true,
                        "slideshow"   : { "delay": 5000, "isAutoRun": false },
                        "timerArc"    : { "stroke" : "#cc0055" },

                        "mobile"      : { "speed": 400 }
                    }'>
                    <?php if(is_file($_SERVER["DOCUMENT_ROOT"] .  '/imagenes_articulos/' . $venta['articulo_id'] . '_1' . '.jpg')): ?>
                        <?php $url = '/imagenes_articulos/' . $venta['articulo_id'] . '_1' . '.jpg' ?>
                    <?php else: ?>
                        <?php $url = '/imagenes_articulos/sin-imagen.jpg' ?>
                    <?php endif; ?>
                    <?= anchor($url, $venta['nombre'], 'class="rt01imgback"') ?>

                    <?php for($i = 2; $i <= 4; $i++): ?>
                        <?php if(is_file($_SERVER["DOCUMENT_ROOT"] .  '/imagenes_articulos/' . $venta['articulo_id'] . '_' .$i. '.jpg')): ?>
                            <?php $url = '/imagenes_articulos/' . $venta['articulo_id'] . '_' .$i. '.jpg' ?>
                        <?php else: ?>
                            <?php $url = '/imagenes_articulos/sin-imagen.jpg' ?>
                        <?php endif; ?>
                        <?= anchor($url, $venta['nombre'], 'class="rt01imgback"') ?>
                    <?php endfor; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="large-4 columns">
        <h3>Valorar al Vendedor</h3>
        <?= form_open('/usuarios/valorar_vendedor/' . $venta['venta_id']) ?>
            <?= form_label('Valoracion:', 'valoracion') ?>
            <?= form_hidden('valoracion', set_value('valoracion', '', FALSE)) ?>
            <div class="valoracion"></div>
            <div class="">
                <?= form_label('Texto valoracion:', 'valoracion_text') ?>
                <?= form_input('valoracion_text', set_value('valoracion_text', '', FALSE),
                               'id="valoracion_text" class=""') ?>
            </div>
            <?= form_submit('valorar', 'Valorar', 'class="success button small radius" id="subir"') ?>
            <?= anchor('/frontend/portada', 'Volver a pagina principal', 'class="alert button small radius" role="button"') ?>
        <?= form_close() ?>
    </div>
</div>
<script type="text/javascript">
    $('input[type="hidden"]').first().val("0");
    var valores_defecto = {
        rating: 0,
        numStars: 5,
        maxValue: 5,
        fullStar: true,
        multiColor: {
          "startColor": "#FF0000", //RED
          "endColor"  : "#00FF00"  //GREEN
        }
    };
    $(function () {
        $(".valoracion").rateYo(valores_defecto);
        $('.valoracion').rateYo().on("rateyo.set", cambiar_datos);
    });
    function cambiar_datos(e, data) {
        $('input[type="hidden"]').first().val(data.rating);
    }
</script>
