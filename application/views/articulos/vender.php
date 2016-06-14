<?php template_set('title', 'Vender Articulo') ?>

<div class="row">
    <div class="row large-6 large-centered columns">
        <?php if ( ! empty(error_array())): ?>
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
                    <?php if(is_file($_SERVER["DOCUMENT_ROOT"] .  '/imagenes_articulos/' . $articulo['id'] . '_1' . '.jpg')): ?>
                        <?php $url = '/imagenes_articulos/' . $articulo['id'] . '_1' . '.jpg' ?>
                    <?php else: ?>
                        <?php $url = '/imagenes_articulos/sin-imagen.jpg' ?>
                    <?php endif; ?>
                    <?= anchor($url, $articulo['nombre'], 'class="rt01imgback"') ?>

                    <?php for($i = 2; $i <= 4; $i++): ?>
                        <?php if(is_file($_SERVER["DOCUMENT_ROOT"] .  '/imagenes_articulos/' . $articulo['id'] . '_' .$i. '.jpg')): ?>
                            <?php $url = '/imagenes_articulos/' . $articulo['id'] . '_' .$i. '.jpg' ?>
                        <?php else: ?>
                            <?php $url = '/imagenes_articulos/sin-imagen.jpg' ?>
                        <?php endif; ?>
                        <?= anchor($url, $articulo['nombre'], 'class="rt01imgback"') ?>
                    <?php endfor; ?>
                </div>
            </div>
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
    $("#nick_comprador").autocomplete();
    $("#nick_comprador").bind('input', function() {
        var nick = $(this).val();
        $.ajax({
            url: "<?= base_url() ?>usuarios/sugerencias_nick/" + nick,
            type: 'POST',
            async: true,
            success: function(response) {
                var suges = [];
                for(var usuario in response.sugerencias) {
                    suges.push(response.sugerencias[usuario].nick);
                }
                $("#nick_comprador").autocomplete({
                    source: suges
                });
            },
            error: function (error) {},
            dataType: 'json'
        });
    });
</script>
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
