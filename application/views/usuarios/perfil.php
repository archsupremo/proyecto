<?php template_set('title', 'Perfil de Usuario') ?>

<div class="row large-6">
    <?php if (count(error_array()) > 0): ?>
        <div data-alert class="alert-box alert radius alerta">
          <?= validation_errors() ?>
          <a href="#" class="close">&times;</a>
        </div>
    <?php endif ?>
</div>
<div class="row large-4 alert-box secondary radius">
    <div class="row tarjeta_perfil" itemscope itemtype="http://schema.org/Person">
        <div class="large-6 columns" itemprop="image">
            <?php if(is_file($_SERVER["DOCUMENT_ROOT"] .  '/imagenes_usuarios/' . $usuario['id'] . '.jpg')): ?>
                <?php $url = 'imagenes_usuarios/' . $usuario['id'] . '.jpg' ?>
            <?php else: ?>
                <?php $url = 'imagenes_usuarios/sin-imagen.jpg' ?>
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
            <h5 itemprop="name">
                <?= anchor('/usuarios/perfil/' . $usuario['id'], $usuario['nick']) ?>
            </h5>
            <p>
                <span class="info">
                    Articulos Disponibles: <?= count($articulos_usuarios) ?>
                </span>
                <span class="info">
                    Articulos Vendidos: <?= count($articulos_vendidos) ?>
                </span>
                <?php $valoraciones = 0; ?>
                <?php foreach ($valoraciones_ventas as $v) {
                    if($v['valoracion'] === NULL) continue;
                    $valoraciones++;
                } ?>
                <?php foreach ($valoraciones_compras as $v) {
                    if($v['valoracion'] === NULL) continue;
                    $valoraciones++;
                } ?>
                <span class="info">
                    Valoraciones: <?= $valoraciones ?>
                </span>
            </p>
        </div>
    </div>
</div>
<div class="row large-6">
    <p class="text-center">
        <a href="#" data-reveal-id="usuario_<?= $usuario['id'] ?>">
            Mostrar la ubicacion de <?= $usuario['nick'] ?>
        </a>
    </p>
</div>
<div id="usuario_<?= $usuario['id'] ?>"
     class="reveal-modal" data-reveal
     aria-labelledby="modalTitle"
     aria-hidden="true" role="dialog">
  <div class="row">
      <a class="close-reveal-modal" aria-label="Close">&#215;</a>
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
              <div itemscope itemtype="http://schema.org/GeoShape">
                  <?php if($usuario['latitud'] !== NULL && $usuario['longitud'] !== NULL): ?>
                      <div class="rt01pagitem">
                          <h2 id="modalTitle">Ubicación de <?= $usuario['nick'] ?></h2>
                      </div>
                      <div class="ruby-map" itemprop="box">
                          <a class="rt01iframe"
                             href="https://www.google.com/maps/embed/v1/place?key=AIzaSyDY6aARD3BZGp4LD2RhzefUdfSIy4mqvzU&amp;&amp;q=<?= $usuario['latitud'] ?>,<?= $usuario['longitud'] ?>&zoom=15&maptype=roadmap"
                             width="100%" height="600" frameborder="0" style="border:0" allowfullscreen></a>
                      </div>
                  <?php else: ?>
                      El usuario <?= $usuario['nick'] ?> no ha aceptado dar su ubicacion.
                  <?php endif; ?>
              </div>
      </div>
  </div>
</div>
<div class="row">
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
                    <?php if($usuario_perfil !== TRUE): ?>
                        Articulos Disponibles
                    <?php else: ?>
                        Mis Articulos
                    <?php endif; ?>
                </div>
                <div class="container">
                <div class="row font-blokk articulos">
                    <?php if( ! empty($articulos_usuarios)): ?>
                        <?php foreach ($articulos_usuarios as $v): ?>
                            <article class="large-3 columns left"
                                     itemscope itemtype="http://schema.org/Product">
                                <div class="" itemprop="logo">
                                    <?php if(is_file($_SERVER["DOCUMENT_ROOT"] .  '/imagenes_articulos/' . $v['articulo_id'] . '_1' . '.jpg')): ?>
                                        <?php $url = 'imagenes_articulos/' . $v['articulo_id'] . '_1' . '.jpg' ?>
                                    <?php else: ?>
                                        <?php $url = 'imagenes_articulos/sin-imagen.jpg' ?>
                                    <?php endif; ?>
                                    <?= anchor('/articulos/buscar/' . $v['id'],
                                    img(array(
                                        'src' => $url,
                                        'title' => $v['nombre'],
                                        'alt' => $v['nombre'],
                                    ))) ?>
                                </div>
                                <div class="" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                                    <span itemprop="priceCurrency"><?= $v['precio'] ?></span>
                                    <span class="oculto" itemprop="price"><?= preg_replace('/,/', '.', substr($v['precio'], 0 , -4)) ?></span>
                                </div>
                                <div class="" itemprop="name">
                                    <?= anchor('/articulos/buscar/' . $v['id'], $v['nombre']) ?>
                                </div>
                                <div class="">
                                    <div class="" itemprop="category">
                                        <?php foreach (preg_split('/,/', $v['etiquetas']) as $etiqueta): ?>
                                            <?php if($etiqueta === '') break; ?>
                                            <?= anchor('/frontend/portada/index?tags='.$etiqueta, $etiqueta,
                                                       'class="button tiny radius"') ?>
                                        <?php endforeach; ?>
                                    </div>
                                    <?php if($usuario_perfil === TRUE): ?>
                                        <br>
                                        <a href="/articulos/vender/<?= $v['id'] ?>"
                                           class="success button tiny radius">
                                           Vender Articulo
                                        </a>
                                        <br>
                                        <a href="/articulos/editar_articulo/<?= $v['id'] ?>"
                                           class="info button tiny radius">
                                           Editar Articulo
                                        </a>
                                        <br>
                                        <a href="#" data-reveal-id="articulo_disponible_<?= $v['id'] ?>"
                                           class="alert button tiny radius">
                                           Borrar Articulo
                                        </a>
                                        <div id="articulo_disponible_<?= $v['id'] ?>" class="reveal-modal"
                                            data-reveal aria-labelledby="modalTitle"
                                            aria-hidden="true" role="dialog">
                                          <h2 id="modalTitle">¿Está totalmente seguro de borrar <?= $v['nombre'] ?>?</h2>
                                          <p class="lead">El borrado sera definitivo y no se podrá recuperar el articulo.</p>
                                          <?= anchor('/articulos/borrar/' . $v['id'],
                                                     'Sí, borrar el articulo.',
                                                     'class="success button small radius" role="button"') ?>
                                          <a class="close-reveal-modal" aria-label="Close">&#215;</a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <h3>El usuario <?= $usuario['nick'] ?> no tiene productos a la venta actualmente >.<</h3>
                    <?php endif; ?>
                </div>
                </div>
            </div>
            <div>
                <div class="rt01pagitem">Ventas</div>
                <div class="container">
                <div class="row font-blokk articulos">
                <?php if( ! empty($articulos_vendidos)): ?>
                    <?php foreach ($articulos_vendidos as $v): ?>
                        <article class="large-3 columns left"
                                 itemscope itemtype="http://schema.org/Product">
                            <div class="" itemprop="logo">
                                <?php if(is_file($_SERVER["DOCUMENT_ROOT"] .  '/imagenes_articulos/' . $v['articulo_id'] . '_1' . '.jpg')): ?>
                                    <?php $url = 'imagenes_articulos/' . $v['articulo_id'] . '_1' . '.jpg' ?>
                                <?php else: ?>
                                    <?php $url = 'imagenes_articulos/sin-imagen.jpg' ?>
                                <?php endif; ?>
                                <?= anchor('/articulos/buscar/' . $v['articulo_id'],
                                    img(array(
                                        'src' => $url,
                                        'title' => $v['nombre'],
                                        'alt' => $v['nombre'],
                                    ))) ?>
                            </div>
                            <div class="" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                                <span itemprop="priceCurrency"><?= $v['precio'] ?></span>
                                <span class="oculto" itemprop="price"><?= preg_replace('/,/', '.', substr($v['precio'], 0 , -4)) ?></span>
                            </div>
                            <div class="" itemprop="name">
                                <?= anchor('/articulos/buscar/' . $v['articulo_id'], $v['nombre']) ?>
                            </div>
                            <div class="">
                                <div class="" itemprop="category">
                                    <?php foreach (preg_split('/,/', $v['etiquetas']) as $etiqueta): ?>
                                        <?php if($etiqueta === '') break; ?>
                                        <?= anchor('/frontend/portada/index?tags='.$etiqueta, $etiqueta,
                                                   'class="button tiny radius"') ?>
                                    <?php endforeach; ?>
                                </div>
                                <?php if($usuario_perfil === TRUE): ?>
                                    <br>
                                    <?php if($v['valoracion'] === NULL && $v['comprador_id'] !== NULL): ?>
                                        <a href="/usuarios/valorar_comprador/<?= $v['venta_id'] ?>"
                                           class="info button tiny radius">
                                            Valorar a <?= $v['comprador_nick'] ?>
                                        </a>
                                    <?php endif; ?>
                                    <a href="#" data-reveal-id="articulo_vendido_<?= $v['articulo_id'] ?>"
                                       class="alert button tiny radius">
                                        Borrar Articulo
                                    </a>
                                    <div id="articulo_vendido_<?= $v['articulo_id'] ?>" class="reveal-modal"
                                        data-reveal aria-labelledby="modalTitle"
                                        aria-hidden="true" role="dialog">
                                      <h2 id="modalTitle">¿Está totalmente seguro de borrar <?= $v['nombre'] ?>?</h2>
                                      <p class="lead">El borrado sera definitivo y no se podrá recuperar el articulo.</p>
                                      <?= anchor('/articulos/borrar/' . $v['articulo_id'],
                                                 'Sí, borrar el articulo.',
                                                 'class="success button small radius" role="button"') ?>
                                      <a class="close-reveal-modal" aria-label="Close">&#215;</a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </article>
                    <?php endforeach; ?>
                    <?php else: ?>
                        <h3>El usuario <?= $usuario['nick'] ?> no tiene ventas actualmente >.<</h3>
                    <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php if($valoraciones > 0): ?>
            <div>
                <div class="rt01pagitem">Valoraciones</div>
                <div class="container">
                <div class="row font-blokk">
                    <?php foreach ($valoraciones_ventas as $v): ?>
                        <?php if($v['valoracion'] === NULL) continue; ?>
                        <div class="row valoraciones ventas" itemscope itemtype="http://schema.org/Review">
                            <div class="large-3 columns" itemscope itemtype="http://schema.org/Person">
                                <?php if(is_file($_SERVER["DOCUMENT_ROOT"] .  '/imagenes_usuarios/' . $v['comprador_id'] . '.jpg')): ?>
                                    <?php $url = 'imagenes_usuarios/' . $v['comprador_id'] . '.jpg' ?>
                                <?php else: ?>
                                    <?php $url = 'imagenes_usuarios/sin-imagen.jpg' ?>
                                <?php endif; ?>
                                <?= anchor('/usuarios/perfil/' . $v['comprador_id'],
                                            img(array(
                                                'src' => $url,
                                                'title' => $v['comprador_nick'],
                                                'alt' => $v['comprador_nick'],
                                                'class' => 'th circular_shadow zoomIt',
                                                'itemprop' => 'image'
                                            ))) ?>
                            </div>
                            <div class="large-5 columns">
                                <div class="">
                                    <h5 itemprop="author">
                                        <?= anchor('/usuarios/perfil/' .
                                            $v['comprador_id'],
                                            $v['comprador_nick']) ?>
                                    </h5>
                                </div>
                                <div class="frase">
                                    <p itemprop="name">Le vendió
                                        a <?= $v['comprador_nick'] ?>
                                        <span itemprop="itemReviewed">
                                            <?= $v['nombre'] ?>
                                        </span>
                                    </p>
                                </div>
                                <span class="oculto" itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">
                                    <span itemprop="bestRating">
                                        5
                                    </span>
                                    <span itemprop="ratingValue">
                                        <?= $v['valoracion'] ?>
                                    </span>
                                    <span itemprop="worstRating">
                                        0
                                    </span>
                                    <?= $v['valoracion'] ?>
                                </span>
                                <div class="valoracion" value="<?= $v['valoracion'] ?>">
                                </div>
                                <div class="" itemprop="reviewBody">
                                    <p><?= $v['valoracion_text'] ?></p>
                                </div>
                            </div>
                            <div class="large-2 columns">
                                <p itemprop="datePublished">
                                    <?= $v['fecha_venta'] ?>
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <?php foreach ($valoraciones_compras as $v): ?>
                        <?php if($v['valoracion'] === NULL) continue; ?>
                        <div class="row valoraciones compras" itemscope itemtype="http://schema.org/Review">
                            <div class="large-3 columns" itemscope itemtype="http://schema.org/Person">
                                <?php if(is_file($_SERVER["DOCUMENT_ROOT"] .  '/imagenes_usuarios/' . $v['vendedor_id'] . '.jpg')): ?>
                                    <?php $url = 'imagenes_usuarios/' . $v['vendedor_id'] . '.jpg' ?>
                                <?php else: ?>
                                    <?php $url = 'imagenes_usuarios/sin-imagen.jpg' ?>
                                <?php endif; ?>
                                <?= anchor('/usuarios/perfil/' . $v['vendedor_id'],
                                            img(array(
                                                'src' => $url,
                                                'title' => $v['vendedor_nick'],
                                                'alt' => $v['vendedor_nick'],
                                                'class' => 'th circular_shadow zoomIt',
                                            ))) ?>
                            </div>
                            <div class="large-7 columns">
                                <div class="">
                                    <h5 itemprop="author">
                                        <?= anchor('/usuarios/perfil/' .
                                            $v['vendedor_id'],
                                            $v['vendedor_nick']) ?>
                                    </h5>
                                </div>
                                <div class="frase">
                                    <p itemprop="name">
                                        <?= $v['vendedor_nick'] ?> le vendió
                                        <span itemprop="itemReviewed">
                                            <?= $v['nombre'] ?>
                                        </span>
                                    </p>
                                </div>
                                <div class="valoracion" value="<?= $v['valoracion'] ?>">
                                </div>
                                <span class="oculto" itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">
                                    <span itemprop="bestRating">
                                        5
                                    </span>
                                    <span itemprop="ratingValue">
                                        <?= $v['valoracion'] ?>
                                    </span>
                                    <span itemprop="worstRating">
                                        0
                                    </span>
                                    <?= $v['valoracion'] ?>
                                </span>
                                <div class="" itemprop="reviewBody">
                                    <p><?= $v['valoracion_text'] ?></p>
                                </div>
                            </div>
                            <div class="large-2 columns">
                                <p itemprop="datePublished">
                                    <?= $v['fecha_venta'] ?>
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    </div>
                    </div>
                </div>
            <?php endif; ?>
            <?php if($usuario_perfil !== TRUE): ?>
                <?php if($usuario['baneado'] !== 't' && logueado()): ?>
                    <?php if(!es_admin()): ?>
                    <div>
                        <div class="rt01pagitem">Escribir PM</div>
                        <div class="container">
                        <div class="font-blokk">
                            <div class="row large-centered columns">
                                <?php if ( ! empty(error_array())): ?>
                                    <div data-alert class="alert-box alert radius alerta">
                                      <?= validation_errors() ?>
                                      <a href="#" class="close">&times;</a>
                                    </div>
                                <?php endif ?>
                                <?= form_open('/usuarios/insertar_pm/' . $usuario['id'], 'class="escribir_pm"') ?>
                                    <div class="nick-field">
                                      <?= form_label('Mensaje:', 'mensaje') ?>
                                      <?= form_textarea('mensaje', set_value('mensaje', '', FALSE),
                                                     'id="mensaje" class=""') ?>
                                    </div>
                                    <?= form_submit('enviar', 'Enviar', 'class="success button small radius"') ?>
                                <?= form_close() ?>
                            </div>
                        </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
            <?php else: ?>
                <div>
                    <div class="rt01pagitem">Mis Compras</div>
                    <div class="container">
                    <div class="row font-blokk articulos">
                    <?php if( ! empty($articulos_comprados)): ?>
                        <?php foreach ($articulos_comprados as $v): ?>
                            <article class="large-3 columns left"
                                     itemscope itemtype="http://schema.org/Product">
                                <div class="" itemprop="logo">
                                    <?php if(is_file($_SERVER["DOCUMENT_ROOT"] .  '/imagenes_articulos/' . $v['articulo_id'] . '_1' . '.jpg')): ?>
                                        <?php $url = 'imagenes_articulos/' . $v['articulo_id'] . '_1' . '.jpg' ?>
                                    <?php else: ?>
                                        <?php $url = 'imagenes_articulos/sin-imagen.jpg' ?>
                                    <?php endif; ?>
                                    <?= anchor('/articulos/buscar/' . $v['articulo_id'],
                                        img(array(
                                            'src' => $url,
                                            'title' => $v['nombre'],
                                            'alt' => $v['nombre'],
                                        ))) ?>
                                </div>
                                <div class="" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                                    <span itemprop="priceCurrency"><?= $v['precio'] ?></span>
                                    <span class="oculto" itemprop="price"><?= preg_replace('/,/', '.', substr($v['precio'], 0 , -4)) ?></span>
                                </div>
                                <div class="" itemprop="name">
                                    <?= anchor('/articulos/buscar/' . $v['articulo_id'], $v['nombre']) ?>
                                </div>
                                <div class="">
                                    <div class="" itemprop="category">
                                        <?php foreach (preg_split('/,/', $v['etiquetas']) as $etiqueta): ?>
                                            <?php if($etiqueta === '') break; ?>
                                            <?= anchor('/frontend/portada/index?tags='.$etiqueta, $etiqueta,
                                                       'class="button tiny radius"') ?>
                                        <?php endforeach; ?>
                                    </div>
                                    <?php if($v['valoracion'] === NULL): ?>
                                        <a href="/usuarios/valorar_vendedor/<?= $v['venta_id'] ?>"
                                           class="info button tiny radius">
                                            Valorar a <?= $v['vendedor_nick'] ?>
                                        </a>
                                    <?php endif; ?>
                                    <a href="#" data-reveal-id="compra_<?= $v['articulo_id'] ?>"
                                       class="alert button tiny radius">
                                        Yo no he comprado esto!!!
                                    </a>
                                   <div id="compra_<?= $v['articulo_id'] ?>" class="reveal-modal"
                                       data-reveal aria-labelledby="modalTitle"
                                       aria-hidden="true" role="dialog">
                                     <h2 id="modalTitle">¿Está totalmente seguro de que no ha comprado <?= $v['nombre'] ?>?</h2>
                                     <p class="lead">
                                         El borrado de su lista de compras sera
                                         definitivo y no se podrá recuperar el
                                         articulo.
                                     </p>
                                     <?= anchor('/usuarios/borrar_compra/' . $v['venta_id'],
                                                'Sí, borrar de la lista de compras.',
                                                'class="success button small radius" role="button"') ?>
                                     <a class="close-reveal-modal" aria-label="Close">&#215;</a>
                                   </div>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <h3>No tienes compras actualmente >.<</h3>
                    <?php endif; ?>
                    </div>
                    </div>
                </div>
                <div>
                    <div class="rt01pagitem">Mis Favoritos</div>
                    <div class="container">
                    <div class="row font-blokk articulos">
                    <?php if( ! empty($articulos_favoritos)): ?>
                        <?php foreach ($articulos_favoritos as $v): ?>
                            <article class="large-3 columns left"
                                     itemscope itemtype="http://schema.org/Product">
                                <div class="">
                                    <?php if(is_file($_SERVER["DOCUMENT_ROOT"] .  '/imagenes_articulos/' . $v['articulo_id'] . '_1' . '.jpg')): ?>
                                        <?php $url = 'imagenes_articulos/' . $v['articulo_id'] . '_1' . '.jpg' ?>
                                    <?php else: ?>
                                        <?php $url = 'imagenes_articulos/sin-imagen.jpg' ?>
                                    <?php endif; ?>
                                    <?= anchor('/articulos/buscar/' . $v['articulo_id'],
                                        img(array(
                                            'src' => $url,
                                            'title' => $v['nombre'],
                                            'alt' => $v['nombre'],
                                        ))) ?>
                                </div>
                                <div class="" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                                    <span itemprop="priceCurrency"><?= $v['precio'] ?></span>
                                    <span class="oculto" itemprop="price"><?= preg_replace('/,/', '.', substr($v['precio'], 0 , -4)) ?></span>
                                </div>
                                <div class="" itemprop="name">
                                    <?= anchor('/articulos/buscar/' . $v['articulo_id'], $v['nombre']) ?>
                                </div>
                                <div class="">
                                    <div class="" itemprop="category">
                                        <?php foreach (preg_split('/,/', $v['etiquetas']) as $etiqueta): ?>
                                            <?php if($etiqueta === '') break; ?>
                                            <?= anchor('/frontend/portada/index?tags='.$etiqueta, $etiqueta,
                                                       'class="button tiny radius"') ?>
                                        <?php endforeach; ?>
                                    </div>
                                    <a href="#" data-reveal-id="favorito_<?= $v['articulo_id'] ?>"
                                       class="alert button tiny radius">
                                        Eliminar de Favoritos
                                    </a>
                                   <div id="favorito_<?= $v['articulo_id'] ?>" class="reveal-modal"
                                       data-reveal aria-labelledby="modalTitle"
                                       aria-hidden="true" role="dialog">
                                     <h2 id="modalTitle">
                                         ¿Está totalmente seguro de borrar
                                         <?= $v['nombre'] ?> de su
                                         lista de favoritos?
                                     </h2>
                                     <p class="lead">
                                         El borrado de su lista de favoritos sera
                                         definitivo y no se podrá recuperar el
                                         articulo.
                                     </p>
                                     <?= anchor('/articulos/eliminar_favorito/' . $v['articulo_id'],
                                                'Sí, borrar de la lista de favoritos.',
                                                'class="success button small radius" role="button"') ?>
                                     <a class="close-reveal-modal" aria-label="Close">&#215;</a>
                                   </div>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <h3>No tienes articulos en la seccion de favoritos actualmente >.<</h3>
                    <?php endif; ?>
                    </div>
                    </div>
                </div>
                <div>
                    <div class="rt01pagitem">Mis PM</div>
                    <div class="container">
                    <div class="row font-blokk">
                        <ul class="accordion" data-accordion>
                          <li class="accordion-navigation">
                            <a href="#panel1a">PM's No Vistos</a>
                            <div id="panel1a" class="content active">
                                <?php foreach ($pm_no_vistos as $v): ?>
                                    <div class="row pm" itemscope itemtype="http://schema.org/Message">
                                        <div class="large-3 columns">
                                            <?php if(is_file($_SERVER["DOCUMENT_ROOT"] .  '/imagenes_usuarios/' . $v['emisor_id'] . '.jpg')): ?>
                                                <?php $url = 'imagenes_usuarios/' . $v['emisor_id'] . '.jpg' ?>
                                            <?php else: ?>
                                                <?php $url = 'imagenes_usuarios/sin-imagen.jpg' ?>
                                            <?php endif; ?>
                                            <?= anchor('/usuarios/perfil/' . $v['emisor_id'],
                                                        img(array(
                                                            'src' => $url,
                                                            'title' => $v['nick_emisor'],
                                                            'alt' => $v['nick_emisor'],
                                                            'class' => 'th circular_shadow zoomIt',
                                                        ))) ?>
                                        </div>
                                        <div class="large-6 columns">
                                            <span itemprop="sender">
                                                <?= anchor('/usuarios/perfil/' . $v['emisor_id'], $v['nick_emisor']) ?>
                                            </span>
                                            <div class="" itemprop="messageAttachment">
                                                <?= $v['mensaje'] ?>
                                            </div>
                                        </div>
                                        <div class="large-3 columns">
                                            <div class="fecha_pm" itemprop="dateSent">
                                                <span>
                                                    <?= $v['fecha_mensaje'] ?>
                                                </span>
                                            </div>
                                            <br>
                                            <br>
                                            <br>
                                            <div class="toggle toggle-light"
                                                 data-toggle-on="false"
                                                 id="<?= $v['id'] ?>">
                                             </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                          </li>
                          <li class="accordion-navigation">
                            <a href="#panel2a">PM's Vistos</a>
                            <div id="panel2a" class="content">
                                <?php foreach ($pm_vistos as $v): ?>
                                    <div class="row pm" itemscope itemtype="http://schema.org/Message">
                                        <div class="large-3 columns">
                                            <?php if(is_file($_SERVER["DOCUMENT_ROOT"] .  '/imagenes_usuarios/' . $v['emisor_id'] . '.jpg')): ?>
                                                <?php $url = 'imagenes_usuarios/' . $v['emisor_id'] . '.jpg' ?>
                                            <?php else: ?>
                                                <?php $url = 'imagenes_usuarios/sin-imagen.jpg' ?>
                                            <?php endif; ?>
                                            <?= anchor('/usuarios/perfil/' . $v['emisor_id'],
                                                        img(array(
                                                            'src' => $url,
                                                            'title' => $v['nick_emisor'],
                                                            'alt' => $v['nick_emisor'],
                                                            'class' => 'th circular_shadow zoomIt',
                                                        ))) ?>
                                        </div>
                                        <div class="large-6 columns">
                                            <span itemprop="sender">
                                                <?= anchor('/usuarios/perfil/' . $v['emisor_id'], $v['nick_emisor']) ?>
                                            </span>
                                            <div class="" itemprop="messageAttachment">
                                                <?= $v['mensaje'] ?>
                                            </div>
                                        </div>
                                        <div class="large-3 columns">
                                            <div class="fecha_pm" itemprop="dateSent">
                                                <span>
                                                    <?= $v['fecha_mensaje'] ?>
                                                </span>
                                            </div>
                                            <br>
                                            <br>
                                            <br>
                                            <div class="toggle toggle-light"
                                                 data-toggle-on="true"
                                                 id="<?= $v['id'] ?>">
                                             </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                          </li>
                        </ul>
                    </div>
                    </div>
                </div>
            <?php endif; ?>
</div>
</div>
<br>
<script type="text/javascript" >
    $(document).ready(function () {
        $('.articulos').shapeshift({
            gutterY: 40,
            enableDrag: false,
            enableResize: false
        });
        $( window ).resize(function() {
            $(".articulos").trigger("ss-destroy");
            $('.articulos').shapeshift({
                enableDrag: false,
                enableResize: false
            });
        });
    });
</script>

<script type="text/javascript">
    $('.toggle').toggles({
        drag: true,
        click: true,
        text: {
            on: 'Visto',
            off: 'No Visto'
        },
        animate: 250, // animation time (ms)
        easing: 'swing', // animation transition easing function
        checkbox: null, // the checkbox to toggle (for use in forms)
        clicker: null,
        width: 100, // width used if not set in css
        height: 25, // height if not set in css
        type: 'compact'
    });
    $('.toggle').on('toggle', function(e, active) {
        var pm_id = $(this).prop('id');
        $.ajax({
            url: "<?= base_url() ?>usuarios/update_pm/" + pm_id,
            type: 'GET',
            async: true,
            success: function (response) {
            },
            error: function (error) {
            },
        });
    });
</script>
<script type="text/javascript">
    var valores_defecto = {
        numStars: 5,
        maxValue: 5,
        fullStar: true,
        readOnly: true,
    };
    $(function () {
        $(".valoracion").rateYo(valores_defecto);
        $('.valoracion').each(function(index) {
            $(this).rateYo("rating", parseInt($(this).attr("value")));
        });
    });
</script>
