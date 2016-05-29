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
        <h3>Datos del articulo</h3>
        <div class="alert-box info radius">
            <p>Precio => <?= $articulo['precio'] ?></p>
            <p>Nombre => <?= $articulo['nombre'] ?></p>
            <p>Descripcion => <?= $articulo['descripcion'] ?></p>
            <p>Etiquetas => <?= $articulo['etiquetas'] ?></p>
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
                <div class="slider lazy">
                    <?php foreach ($articulos_usuarios as $v): ?>
                        <div class="large-4 columns left">
                            <?= anchor('/articulos/buscar/' . $v['id'],
                                       img(array(
                                           'alt' => $v['nombre'],
                                           'title' => $v['nombre'],
                                           'data-lazy' => '/imagenes_articulos/' . $v['id'] . '_1.jpg'
                                       )),
                                       'title="' . $v['nombre'] . '"') ?>

                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <h6>El usuario <?= $usuario['nick'] ?> no tiene mas productos a la venta >.<</h6>
            <?php endif; ?>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('.lazy').slick({
        lazyLoad: 'ondemand',
        slidesToShow: 3,
        slidesToScroll: 1
    });
</script>
