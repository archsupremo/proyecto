<?php template_set('title', 'Buscar Articulo') ?>

<div class="row">
    <div class="row large-6 large-centered columns">
        <?php if (count(error_array()) > 0): ?>
            <div data-alert class="alert-box alert radius alerta">
              <?= validation_errors() ?>
              <a href="#" class="close">&times;</a>
            </div>
        <?php endif ?>
    </div>
    <div class="large-3 columns">
        <div class="wrapper">
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
    <section class="large-7 columns datos tarjeta_perfil">
        <h3>Datos del articulo</h3>
        <article class="alert-box info radius">
            <h5 class="precio_detalle"><?= $articulo['precio'] ?></h5>
            <p><?= $articulo['nombre'] ?></p>
            <p><?= $articulo['descripcion'] ?></p>
            <?php if($articulo['etiquetas'] != ""): ?>
                <?php foreach (preg_split('/,/', $articulo['etiquetas']) as $etiqueta): ?>
                    <?php if($etiqueta === '') break; ?>
                    <?= anchor('/frontend/portada/index?tags='.$etiqueta, $etiqueta,
                               'class="button tiny radius"') ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </article>
        <article class="alert-box info radius">
            <div class="row tarjeta_perfil">
                <div class="large-6 columns">
                    <?php if(is_file($_SERVER["DOCUMENT_ROOT"] .  '/imagenes_usuarios/' . $usuario['id'] . '.jpg')): ?>
                        <?php $url = '/imagenes_usuarios/' . $usuario['id'] . '.jpg' ?>
                    <?php else: ?>
                        <?php $url = '/imagenes_usuarios/sin-imagen.jpg' ?>
                    <?php endif; ?>
                    <?= anchor('/usuarios/perfil/' . $usuario['id'],
                                img(array(
                                    'src' => $url,
                                    'title' => $usuario['nick'],
                                    'alt' => $usuario['nick'],
                                    'class' => 'th circular_shadow zoomIt',
                                ))) ?>
                </div>
                <div class="large-6 columns">
                    <h5>
                        <?= anchor('/usuarios/perfil/' . $usuario['id'], $usuario['nick']) ?>
                    </h5>
                    <p>
                        Articulos Disponibles: <?= count($articulos_usuarios) + 1 ?>
                    </p>
                </div>
            </div>
        </article>
        <?php if($usuario['latitud'] !== NULL && $usuario['longitud'] !== NULL): ?>
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
                            <div class="rt01pagitem">
                                <h4 id="modalTitle">Ubicación de <?= $usuario['nick'] ?></h4>
                            </div>
                            <div class="ruby-map">
                                <a class="rt01iframe"
                                   href="https://www.google.com/maps/embed/v1/place?key=AIzaSyDY6aARD3BZGp4LD2RhzefUdfSIy4mqvzU&amp;&amp;q=<?= $usuario['latitud'] ?>,<?= $usuario['longitud'] ?>&zoom=15&maptype=roadmap"
                                   width="100%" height="300" frameborder="0" style="border:0" allowfullscreen></a>
                            </div>
                        </div>
                </div>
            </article>
        <?php endif; ?>
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
