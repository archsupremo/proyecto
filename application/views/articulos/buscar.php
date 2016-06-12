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
    <div class="large-3 columns">
        <div class="wrapper">
            <!-- SLIDER PREVIEW - begin -->
            <style>
                .slider-nested {
                    width: 500px;
                    padding: 5px;
                    margin-left: -12em;
                    margin-top: 3em;
                    background-color: #fff;
                    border-radius: 3px;
                    box-shadow: 0 1px 1px hsla(0,0%,0%,.5);
                }
                .datos {
                    margin-right: -8em;
                }
                @media only screen and (max-width: 959px) {
                    .slider-nested {
                        margin-left: 0em;
                        width: 100%;
                    }
                    .datos {
                        margin-right: 0em;
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
                    .datos {
                        margin-right: 0em;
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
                    <!-- center - fill - fit - stretch - tile. -->
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
            <!-- SLIDER PREVIEW - end -->
        </div>
    </div>
    <section class="large-7 columns datos">
        <h3>Datos del articulo</h3>
        <article class="alert-box info radius">
            <p>Precio => <?= $articulo['precio'] ?></p>
            <p>Nombre => <?= $articulo['nombre'] ?></p>
            <p>Descripcion => <?= $articulo['descripcion'] ?></p>
            <?php if($articulo['etiquetas'] != ""): ?>
                <?php foreach (preg_split('/,/', $articulo['etiquetas']) as $etiqueta): ?>
                    <?php if($etiqueta === '') break; ?>
                    <?= anchor('/frontend/portada/index?tags='.$etiqueta, $etiqueta,
                               'class="button tiny radius"') ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </article>
        <article class="alert-box info radius">
            <?php if(is_file($_SERVER["DOCUMENT_ROOT"] .  '/imagenes_usuarios/' . $articulo['usuario_id'] . '_thumbnail.jpeg')): ?>
                <?php $url = '/imagenes_usuarios/' . $articulo['usuario_id'] . '_thumbnail.jpeg' ?>
            <?php else: ?>
                <?php $url = '/imagenes_usuarios/sin-imagen_thumbnail.jpeg' ?>
            <?php endif; ?>
            <?= anchor('/usuarios/perfil/' . $articulo['usuario_id'],
                        img(array(
                            'src' => $url,
                            'title' => $articulo['nick'],
                            'alt' => $articulo['nick'],
                            'class' => 'th',
                        ))) ?>
            <?= anchor('/usuarios/perfil/' . $usuario['id'], $usuario['nick']) ?>
        </article>
        <article class="alert-box info radius">
            <div class="rt01 tabs-preview ma-b-100"
                    data-tabs='{
                        "isAutoInit" : true,
                        "fx"         : "line",
                        "speed"      : 600,
                        "pag"        : { "align": "center", "isMark": false, "sizeMarkTo": "padding", "moreClass": "style-arrow" },

                        "width"      : 940,
                        "isKeyboard" : true,
                        "mobile"     : { "speed": 400 }
                    }'>
                    <div>
                        <?php if($usuario['latitud'] !== NULL && $usuario['longitud'] !== NULL): ?>
                            <div class="rt01pagitem">
                                <h4 id="modalTitle">Ubicación de <?= $usuario['nick'] ?></h4>
                            </div>
                            <div class="ruby-map">
                                <a class="rt01iframe"
                                   href="https://www.google.com/maps/embed/v1/place?key=AIzaSyDY6aARD3BZGp4LD2RhzefUdfSIy4mqvzU&amp;&amp;q=<?= $usuario['latitud'] ?>,<?= $usuario['longitud'] ?>&zoom=15&maptype=roadmap"
                                   width="100%" height="300" frameborder="0" style="border:0" allowfullscreen></a>
                            </div>
                        <?php else: ?>
                            El usuario <?= $usuario['nick'] ?> no ha aceptado dar su ubicacion.
                        <?php endif; ?>
                    </div>
            </div>
        </article>
        <article class="text-center alert-box secondary radius">
            <h4>Otros productos de <?= $usuario['nick'] ?></h4>
            <?php if(!empty($articulos_usuarios)): ?>
                <div class="slider lazy">
                    <?php foreach ($articulos_usuarios as $v): ?>
                        <div class="large-4 columns left">
                            <?php if(is_file($_SERVER["DOCUMENT_ROOT"] .  '/imagenes_articulos/' . $v['id'] . '_1' . '.jpg')): ?>
                                <?php $url = '/imagenes_articulos/' . $v['id'] . '_1' . '.jpg' ?>
                            <?php else: ?>
                                <?php $url = '/imagenes_articulos/sin-imagen.jpg' ?>
                            <?php endif; ?>
                            <?= anchor('/articulos/buscar/' . $v['id'],
                                       img(array(
                                           'alt' => $v['nombre'],
                                           'title' => $v['nombre'],
                                           'data-lazy' => $url,
                                           'class' => 'imagen_lazy'
                                       )),
                                       'title="' . $v['nombre'] . '"') ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <h6>El usuario <?= $usuario['nick'] ?> no tiene mas productos a la venta >.<</h6>
            <?php endif; ?>
        </article>
    </div>
</div>

<script type="text/javascript">
    $('.lazy').slick({
        lazyLoad: 'ondemand',
        infinite: false,
        slidesToShow: 3,
        slidesToScroll: 1
    });
</script>
