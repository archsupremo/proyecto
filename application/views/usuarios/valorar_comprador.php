<?php template_set('title', 'Valorar Vendedor') ?>

<div class="row">
    <div class="large-6 large-centered columns menu-login">
        <?php if ( ! empty(error_array())): ?>
            <div data-alert class="alert-box alert radius alerta">
              <?= validation_errors() ?>
              <a href="#" class="close">&times;</a>
            </div>
        <?php endif ?>
    </div>
    <div class="large-6 columns">
        <div class="wrapper">
            <!-- SLIDER PREVIEW - begin -->
            <style>
                .slider-nested {
                    width: 700px;
                    padding: 5px;
                    margin-left: -10em;
                    background-color: #fff;
                    border-radius: 3px;
                    box-shadow: 0 1px 1px hsla(0,0%,0%,.5);
                }
                @media only screen and (max-width: 959px) {
                    .slider-nested {
                        margin-left: 0em;
                        width: 100%;
                    }
                }
                @media only screen and (max-width: 479px) {
                    .slider-nested {
                        position: static;
                        margin-left: 0em;
                        width: auto;
                        margin-top: 5px;
                        border: 1px solid #e5e5e5;
                        box-shadow: none;
                    }
                }
            </style>

            <div class="container">
                <div class="rt01 slider-nested rt01timer-arcTop"
                    data-tabs='{
                        "isAutoInit"  : true,
                        "optionsPlus" : "slider",
                        "fx"          : "line",
                        "speed"       : 600,
                        "width"       : 940,
                        "height"      : 500,
                        "widthSlide"  : [ 0.95, [748, 768, 959], [940, 960, 3000] ],
                        "margin"      : [ 2, [5, 480, 767], [10, 768, 959], [30, 960, 3000] ],
                        "pag"         : { "sizeMarkTo": "padding" },

                        "isLoop"      : true,
                        "isNav"       : false,
                        "isSlideshow" : true,
                        "slideshow"   : { "delay": 5000, "isAutoRun": false },
                        "timerArc"    : { "stroke" : "#cc0055" },

                        "mobile"      : { "speed": 400 }
                    }'>

                    <a class="rt01imgback"
                       href="<?= '/imagenes_articulos/' . $venta['articulo_id'] . '_1.jpg' ?>"></a>
                    <a class="rt01imgback"
                       href="<?= '/imagenes_articulos/' . $venta['articulo_id'] . '_1.jpg' ?>"></a>
                    <a class="rt01imgback"
                       href="<?= '/imagenes_articulos/' . $venta['articulo_id'] . '_1.jpg' ?>"></a>
                    <a class="rt01imgback"
                       href="<?= '/imagenes_articulos/' . $venta['articulo_id'] . '_1.jpg' ?>"></a>
                </div>
            </div>
            <!-- SLIDER PREVIEW - end -->
        </div>
    </div>
    <div class="large-4 columns">
        <h3>Valorar al Comprador</h3>
        <?= form_open('/usuarios/valorar_comprador/' . $venta['venta_id']) ?>
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
