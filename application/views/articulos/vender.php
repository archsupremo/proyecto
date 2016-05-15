<?php template_set('title', 'Buscar Articulo') ?>

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

                    <a class="rt01imgback" href="<?= '/imagenes_articulos/' . $articulo['id'] . '_1.jpg' ?>">vietnam 1</a>
                    <a class="rt01imgback" href="<?= '/imagenes_articulos/' . $articulo['id'] . '_1.jpg' ?>">vietnam 1</a>
                    <a class="rt01imgback" href="<?= '/imagenes_articulos/' . $articulo['id'] . '_1.jpg' ?>">vietnam 1</a>
                    <a class="rt01imgback" href="<?= '/imagenes_articulos/' . $articulo['id'] . '_1.jpg' ?>">vietnam 1</a>
                </div>
            </div>
            <!-- SLIDER PREVIEW - end -->
        </div>
    </div>
    <div class="large-4 columns">
        <h3>Datos de la venta</h3>
        <?= form_open('/articulos/vender/' . $articulo['id']) ?>
            <div class="venta">
                <?= form_label('Forma de Venta:', 'forma_venta') ?>
                <?= form_dropdown('forma_venta', $opciones_venta,
                    set_value('forma_venta', '', FALSE), 'id="forma_venta"') ?>
            </div>
            <div class="comprador">
                <?= form_label('Nick comprador:', 'nick_comprador') ?>
                <?= form_input('nick_comprador', set_value('nick_comprador', '', FALSE),
                               'id="nick_comprador" class=""') ?>
            </div>
            <div class="valoracion_comprador">
                <?= form_label('Valoracion:', 'valoracion') ?>
                <?= form_hidden('valoracion', set_value('valoracion', '', FALSE)) ?>
                <div class="valoracion"></div>
                <div class="">
                    <?= form_label('Texto valoracion:', 'valoracion_text') ?>
                    <?= form_input('valoracion_text', set_value('valoracion_text', '', FALSE),
                                   'id="valoracion_text" class=""') ?>
                </div>
            </div>
            <?= form_submit('vender', 'Vender', 'class="success button small radius" id="subir"') ?>
            <?= anchor('/frontend/portada', 'Volver a pagina principal', 'class="alert button small radius" role="button"') ?>
        <?= form_close() ?>
    </div>
</div>
<script type="text/javascript">
    $('input[type="hidden"]').first().val("0");
    var rating = 0;
    var valores_defecto = {
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
        rating = data.rating;
        $('input[type="hidden"]').first().val(data.rating);
    }
    var valoracion_comprador = $('.valoracion_comprador');
    var comprador = $('.comprador');
    valoracion_comprador.remove();

    function tomar_valores(elemento) {
        if(elemento.val() == 2) {
            comprador.remove();
            valoracion_comprador.remove();
        }
        else if(elemento.val() == 1) {
            $(".valoracion").rateYo("destroy");
            $('.venta').after(comprador);
            $('.comprador').after(valoracion_comprador);

            $(".valoracion").rateYo(valores_defecto);
            $(".valoracion").rateYo("rating", rating);
            $('.valoracion').rateYo().on("rateyo.set", cambiar_datos);
        } else {
            $('.venta').after(comprador);
            valoracion_comprador.remove();
        }
    }
    $('#forma_venta').change(function () {
        tomar_valores($(this));
    });

    tomar_valores($('#forma_venta'));
</script>
