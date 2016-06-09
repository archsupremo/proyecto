<?php template_set('title', 'Perfil de Usuario') ?>

<style media="screen">
    div.font-blokk > h3 {
        border: 1px solid black;
        margin: 0.75em;
    }
</style>
<div class="row large-6">
    <?php if ( ! empty(error_array())): ?>
        <div data-alert class="alert-box alert radius alerta">
          <?= validation_errors() ?>
          <a href="#" class="close">&times;</a>
        </div>
    <?php endif ?>
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
              <div>
                  <?php if($usuario['latitud'] !== NULL && $usuario['longitud'] !== NULL): ?>
                      <div class="rt01pagitem">
                          <h2 id="modalTitle">Ubicación de <?= $usuario['nick'] ?></h2>
                      </div>
                      <div class="ruby-map">
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
                <div class="row font-blokk">
                    <?php if( ! empty($articulos_usuarios)): ?>
                        <?php foreach ($articulos_usuarios as $v): ?>
                            <div class="large-3 columns left">
                                <div class="">
                                    <?php if(is_file($_SERVER["DOCUMENT_ROOT"] .  '/imagenes_articulos/' . $v['articulo_id'] . '_1' . '.jpg')): ?>
                                        <?php $url = '/imagenes_articulos/' . $v['articulo_id'] . '_1' . '.jpg' ?>
                                    <?php else: ?>
                                        <?php $url = '/imagenes_articulos/sin-imagen.jpg' ?>
                                    <?php endif; ?>
                                    <?= anchor('/articulos/buscar/' . $v['id'],
                                        img($url)) ?>
                                </div>
                                <div class="">
                                    <?= $v['precio'] ?>
                                </div>
                                <div class="">
                                    <?= anchor('/articulos/buscar/' . $v['id'], $v['nombre']) ?>
                                </div>
                                <div class="">
                                    <?= $v['etiquetas'] ?>
                                    <?php if($usuario_perfil === TRUE): ?>
                                        <br>
                                        <a href="/articulos/vender/<?= $v['id'] ?>"
                                           class="success button tiny radius">
                                           Vender Articulo
                                        </a>
                                        <a href="#" data-reveal-id="articulo_disponible_<?= $v['id'] ?>"
                                           class="alert button tiny radius">
                                           Borrar Articulo
                                        </a>
                                        <!-- <a href=""
                                           class="small secondary radius button split">
                                           Opciones
                                           <span data-options="align: right"
                                                 data-dropdown="drop_articulo_disponible_<?= $v['id']?>">
                                           </span>
                                        </a>
                                        <ul id="drop_articulo_disponible_<?= $v['id'] ?>"
                                            class="f-dropdown" data-dropdown-content>
                                           <li></li>
                                           <li>
                                               <a href="#" data-reveal-id="articulo_disponible_<?= $v['id'] ?>">
                                                   Borrar Articulo
                                               </a>
                                           </li>
                                        </ul> -->
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
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <h3>El usuario <?= $usuario['nick'] ?> no tiene productos a la venta actualmente >.<</h3>
                    <?php endif; ?>
                </div>
                <div class="row large-centered columns">
                    <ul class="pagination right">
                      <li class="arrow unavailable">
                          <a href="">&laquo;</a>
                      </li>
                      <li class="current">
                          <a href="">1</a>
                      </li>
                      <li><a href="">2</a></li>
                      <li><a href="">3</a></li>
                      <li><a href="">4</a></li>
                      <li class="unavailable">
                          <a href="">&hellip;</a>
                      </li>
                      <li><a href="">12</a></li>
                      <li><a href="">13</a></li>
                      <li class="arrow">
                          <a href="">&raquo;</a>
                      </li>
                    </ul>
                </div>
                </div>
            </div>
            <div>
                <div class="rt01pagitem">Ventas</div>
                <div class="container">
                <div class="row font-blokk">
                <?php if( ! empty($articulos_vendidos)): ?>
                    <?php foreach ($articulos_vendidos as $v): ?>
                        <div class="large-3 columns left">
                            <div class="">
                                <?php if(is_file($_SERVER["DOCUMENT_ROOT"] .  '/imagenes_articulos/' . $v['articulo_id'] . '_1' . '.jpg')): ?>
                                    <?php $url = '/imagenes_articulos/' . $v['articulo_id'] . '_1' . '.jpg' ?>
                                <?php else: ?>
                                    <?php $url = '/imagenes_articulos/sin-imagen.jpg' ?>
                                <?php endif; ?>
                                <?= anchor('/articulos/buscar/' . $v['articulo_id'],
                                            img($url)) ?>
                            </div>
                            <div class="">
                                <?= $v['precio'] ?>
                            </div>
                            <div class="">
                                <?= anchor('/articulos/buscar/' . $v['articulo_id'], $v['nombre']) ?>
                            </div>
                            <div class="">
                                <?= $v['etiquetas'] ?>
                                <?php if($usuario_perfil === TRUE): ?>
                                    <br>
                                    <?php if($v['valoracion'] === NULL && $v['comprador_id'] !== NULL): ?>
                                        <a href="/usuarios/valorar_comprador/<?= $v['venta_id'] ?>"
                                           class="info button tiny radius">
                                            Valorar al comprador
                                        </a>
                                    <?php endif; ?>
                                    <a href="#" data-reveal-id="articulo_vendido_<?= $v['articulo_id'] ?>"
                                       class="alert button tiny radius">
                                        Borrar Articulo
                                    </a>
                                    <!-- <p>
                                        <a href=""
                                           class="small secondary radius button split">
                                           Opciones
                                           <span data-options="align: right"
                                                 data-dropdown="drop_articulo_vendido_<?= $v['articulo_id']?>">
                                           </span>
                                        </a>
                                        <br>
                                    </p>
                                    <ul id="drop_articulo_vendido_<?= $v['articulo_id'] ?>" class="f-dropdown" data-dropdown-content>
                                        <?php if($v['valoracion'] === NULL && $v['comprador_id'] !== NULL): ?>
                                            <li>
                                                <a href="/usuarios/valorar_comprador/<?= $v['venta_id'] ?>">
                                                    Valorar al comprador
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                        <li>
                                            <a href="#" data-reveal-id="articulo_vendido_<?= $v['articulo_id'] ?>">
                                                Borrar Articulo
                                            </a>
                                        </li>
                                    </ul> -->
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
                        </div>
                    <?php endforeach; ?>
                    <?php else: ?>
                        <h3>El usuario <?= $usuario['nick'] ?> no tiene ventas actualmente >.<</h3>
                    <?php endif; ?>
                    </div>
                </div>
            </div>
            <div>
                <div class="rt01pagitem">Valoraciones</div>
                <div class="container">
                <div class="row font-blokk">
                    <ul class="accordion" data-accordion>
                      <li class="accordion-navigation">
                        <a href="#panel1a">Ventas</a>
                        <div id="panel1a" class="content active">
                            <div class="row">
                                <?php foreach ($valoraciones_ventas as $v): ?>
                                    <?php if($v['valoracion'] === NULL) continue; ?>
                                    <div class="large-6 columns left">
                                        <div class="">
                                            <h5>Comprador =>
                                                <?= anchor('/usuarios/perfil/' .
                                                    $v['comprador_id'],
                                                    $v['comprador_nick']) ?>
                                            </h5>
                                        </div>
                                        <div class="">
                                            <p>Le vendió a <?= $v['comprador_nick'] ?>
                                                <?= $v['nombre'] ?></p>
                                        </div>
                                        <div class="valoracion" value="<?= $v['valoracion'] ?>">
                                        </div>
                                        <div class="">
                                            <p><?= $v['valoracion_text'] ?></p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                      </li>
                      <li class="accordion-navigation">
                        <a href="#panel2a">Compras</a>
                        <div id="panel2a" class="content">
                            <div class="row">
                                <?php foreach ($valoraciones_compras as $v): ?>
                                    <?php if($v['valoracion'] === NULL) continue; ?>
                                    <div class="large-6 columns left">
                                        <div class="">
                                            <h5>Vendedor =>
                                                <?= anchor('/usuarios/perfil/' .
                                                    $v['vendedor_id'],
                                                    $v['vendedor_nick']) ?>
                                            </h5>
                                        </div>
                                        <div class="">
                                            <p><?= $v['vendedor_nick'] ?> le
                                                vendio <?= $v['nombre'] ?></p>
                                        </div>
                                        <div class="valoracion" value="<?= $v['valoracion'] ?>">
                                        </div>
                                        <div class="">
                                            <p><?= $v['valoracion_text'] ?></p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                      </li>
                    </ul>
                </div>
                </div>
            </div>
            <?php if($usuario_perfil !== TRUE): ?>
                <div>
                    <div class="rt01pagitem">Escribir PM</div>
                    <div class="container">
                    <div class="row font-blokk">
                        <div class="row large-centered columns">
                            <?php if ( ! empty(error_array())): ?>
                                <div data-alert class="alert-box alert radius alerta">
                                  <?= validation_errors() ?>
                                  <a href="#" class="close">&times;</a>
                                </div>
                            <?php endif ?>
                            <?= form_open('/usuarios/insertar_pm/' . $usuario['id']) ?>
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
            <?php else: ?>
                <div>
                    <div class="rt01pagitem">Mis Compras</div>
                    <div class="container">
                    <div class="row font-blokk">
                    <?php if( ! empty($articulos_comprados)): ?>
                        <?php foreach ($articulos_comprados as $v): ?>
                            <div class="large-3 columns left">
                                <div class="">
                                    <?php if(is_file($_SERVER["DOCUMENT_ROOT"] .  '/imagenes_articulos/' . $v['articulo_id'] . '_1' . '.jpg')): ?>
                                        <?php $url = '/imagenes_articulos/' . $v['articulo_id'] . '_1' . '.jpg' ?>
                                    <?php else: ?>
                                        <?php $url = '/imagenes_articulos/sin-imagen.jpg' ?>
                                    <?php endif; ?>
                                    <?= anchor('/articulos/buscar/' . $v['articulo_id'],
                                                img($url)) ?>
                                </div>
                                <div class="">
                                    <?= $v['precio'] ?>
                                </div>
                                <div class="">
                                    <?= anchor('/articulos/buscar/' . $v['articulo_id'], $v['nombre']) ?>
                                </div>
                                <div class="">
                                    <?= $v['etiquetas'] ?>
                                    <br>
                                    <?php if($v['valoracion'] === NULL): ?>
                                        <a href="/usuarios/valorar_vendedor/<?= $v['venta_id'] ?>"
                                           class="info button tiny radius">
                                            Valorar al vendedor
                                        </a>
                                    <?php endif; ?>
                                    <a href="#" data-reveal-id="compra_<?= $v['articulo_id'] ?>"
                                       class="alert button tiny radius">
                                        Yo no he comprado esto!!!
                                    </a>
                                    <!-- <p>
                                        <a href=""
                                           class="small secondary radius button split">
                                           Opciones
                                           <span data-options="align: right"
                                                 data-dropdown="drop_articulo_comprados_<?= $v['articulo_id'] ?>">
                                           </span>
                                        </a>
                                        <br>
                                   </p>
                                   <ul id="drop_articulo_comprados_<?= $v['articulo_id'] ?>" class="f-dropdown" data-dropdown-content>
                                       <?php if($v['valoracion'] === NULL): ?>
                                           <li>
                                               <a href="/usuarios/valorar_vendedor/<?= $v['venta_id'] ?>">
                                                   Valorar al vendedor
                                               </a>
                                           </li>
                                       <?php endif; ?>
                                       <li>
                                           <a href="#" data-reveal-id="compra_<?= $v['articulo_id'] ?>">
                                               Yo no he comprado esto!!!
                                           </a>
                                       </li>
                                   </ul> -->
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
                            </div>
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
                    <div class="row font-blokk">
                    <?php if( ! empty($articulos_favoritos)): ?>
                        <?php foreach ($articulos_favoritos as $v): ?>
                            <div class="large-3 columns left">
                                <div class="">
                                    <?php if(is_file($_SERVER["DOCUMENT_ROOT"] .  '/imagenes_articulos/' . $v['articulo_id'] . '_1' . '.jpg')): ?>
                                        <?php $url = '/imagenes_articulos/' . $v['articulo_id'] . '_1' . '.jpg' ?>
                                    <?php else: ?>
                                        <?php $url = '/imagenes_articulos/sin-imagen.jpg' ?>
                                    <?php endif; ?>
                                    <?= anchor('/articulos/buscar/' . $v['articulo_id'],
                                                img($url)) ?>
                                </div>
                                <div class="">
                                    <?= $v['precio'] ?>
                                </div>
                                <div class="">
                                    <?= anchor('/articulos/buscar/' . $v['articulo_id'], $v['nombre']) ?>
                                </div>
                                <div class="">
                                    <?= $v['etiquetas'] ?>
                                    <br>
                                    <a href="#" data-reveal-id="favorito_<?= $v['articulo_id'] ?>"
                                       class="alert button tiny radius">
                                        Eliminar de Favoritos
                                    </a>
                                    <!-- <p>
                                        <a href=""
                                           class="small secondary radius button split">
                                           Opciones
                                           <span data-options="align: right"
                                                 data-dropdown="drop_articulo_favorito_<?= $v['articulo_id'] ?>">
                                           </span>
                                        </a>
                                        <br>
                                   </p>
                                   <ul id="drop_articulo_favorito_<?= $v['articulo_id'] ?>" class="f-dropdown" data-dropdown-content>
                                      <li>
                                          <a href="#" data-reveal-id="favorito_<?= $v['articulo_id'] ?>">
                                              Eliminar de Favoritos
                                          </a>
                                      </li>
                                   </ul> -->
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
                            </div>
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
                                        <div class="">
                                            <?php if(is_file($_SERVER["DOCUMENT_ROOT"] .  '/imagenes_usuarios/' . $v['emisor_id'] . '_thumbnail.jpeg')): ?>
                                                <?php $url = '/imagenes_usuarios/' . $v['emisor_id'] . '_thumbnail.jpeg' ?>
                                            <?php else: ?>
                                                <?php $url = '/imagenes_usuarios/sin-imagen_thumbnail.jpeg' ?>
                                            <?php endif; ?>
                                            <?= anchor('/usuarios/perfil/' . $v['emisor_id'],
                                                        img(array(
                                                            'src' => $url,
                                                            'title' => $v['nick_emisor'],
                                                            'alt' => $v['nick_emisor'],
                                                            'class' => 'th',
                                                        ))) ?>
                                            <?= anchor('/usuarios/perfil/' . $v['emisor_id'], $v['nick_emisor']) ?>
                                        </div>
                                        <div class="toggle toggle-light"
                                             data-toggle-on="false"
                                             id="<?= $v['id'] ?>"></div>
                                        <div class="">
                                            <?= $v['mensaje'] ?>
                                        </div>
                                        <div class="">
                                            <?= $v['fecha_mensaje'] ?>
                                        </div>
                                <?php endforeach; ?>
                            </div>
                          </li>
                          <li class="accordion-navigation">
                            <a href="#panel2a">PM's Vistos</a>
                            <div id="panel2a" class="content">
                                <?php foreach ($pm_vistos as $v): ?>
                                        <div class="">
                                            <?php if(is_file($_SERVER["DOCUMENT_ROOT"] .  '/imagenes_usuarios/' . $v['emisor_id'] . '_thumbnail.jpeg')): ?>
                                                <?php $url = '/imagenes_usuarios/' . $v['emisor_id'] . '_thumbnail.jpeg' ?>
                                            <?php else: ?>
                                                <?php $url = '/imagenes_usuarios/sin-imagen_thumbnail.jpeg' ?>
                                            <?php endif; ?>
                                            <?= anchor('/usuarios/perfil/' . $v['emisor_id'],
                                                        img(array(
                                                            'src' => $url,
                                                            'title' => $v['nick_emisor'],
                                                            'alt' => $v['nick_emisor'],
                                                            'class' => 'th',
                                                        ))) ?>
                                            <?= anchor('/usuarios/perfil/' . $v['emisor_id'], $v['nick_emisor']) ?>
                                        </div>
                                        <div class="toggle toggle-light"
                                             data-toggle-on="true"
                                             id="<?= $v['id'] ?>"></div>
                                        <div class="">
                                            <?= $v['mensaje'] ?>
                                        </div>
                                        <div class="">
                                            <?= $v['fecha_mensaje'] ?>
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
