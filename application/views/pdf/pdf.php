<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Perfil personal de <?= $usuario_nick ?> en pdf</title>
    <style type="text/css">
        body {
         background-color: #fff;
         margin: 20px;
         font-family: Lucida Grande, Verdana, Sans-serif;
         font-size: 14px;
         color: #4F5155;
        }

        a {
         color: #003399;
         background-color: transparent;
         font-weight: normal;
        }

        h1, h2, h4 {
         color: #444;
         background-color: transparent;
         border-bottom: 1px solid #D0D0D0;
         font-size: 16px;
         font-weight: bold;
         margin: 24px 0 2px 0;
         padding: 5px 0 6px 0;
        }

        h2 {
         text-align: center;
        }

        h4 {
         font-size: 14px;
        }

        div.imagen img {
            width: 250px;
            height: 250px;
        }
        div.articulo > div {
            text-align: center;
        }
        div.articulo {
            border: 1px solid black;
        }

        /* estilos para el footer y el numero de pagina */
        @page { margin: 180px 50px; text-align: center;}
        header {
            position: fixed;
            left: 0px; top: -180px;
            right: 0px;
            height: 100px;
            background-color: #333;
            color: #fff;
            text-align: center;
        }
        footer {
            position: fixed;
            left: 0px;
            bottom: -180px;
            right: 0px;
            height: 100px;
            background-color: #333;
            color: #fff;
        }
        footer .page:after {
            content: counter(page, upper-roman);
        }
    </style>
</head>
<body>
    <!--header para cada pagina-->
    <header>
    </header>
    <!--footer para cada pagina-->
    <footer>
        <!--aqui se muestra el numero de la pagina en numeros romanos-->
        <p class="page"></p>
    </footer>
    <h2>Mis Articulos</h2>
    <div class="">
        <?php if( ! empty($articulos_usuarios)): ?>
            <?php foreach ($articulos_usuarios as $v): ?>
                <div class="articulo">
                    <div class="imagen">
                        <?php if(is_file($_SERVER["DOCUMENT_ROOT"] .  '/imagenes_articulos/' . $v['articulo_id'] . '_1' . '.jpg')): ?>
                            <?php $url = '/imagenes_articulos/' . $v['articulo_id'] . '_1' . '.jpg' ?>
                        <?php else: ?>
                            <?php $url = '/imagenes_articulos/sin-imagen.jpg' ?>
                        <?php endif; ?>
                        <?= anchor('/articulos/buscar/' . $v['id'],
                            img($url)) ?>
                    </div>
                    <div class="precio">
                        <?= $v['precio'] ?>
                    </div>
                    <div class="nombre">
                        <?= anchor('/articulos/buscar/' . $v['id'], $v['nombre']) ?>
                    </div>
                    <div class="etiquetas">
                        <?= $v['etiquetas'] ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <h3>No tienes productos a la venta actualmente >.<</h3>
        <?php endif; ?>
    </div>
    <h2>Mis Ventas</h2>
    <div class="">
        <?php if( ! empty($articulos_vendidos)): ?>
            <?php foreach ($articulos_vendidos as $v): ?>
                <div class="articulo">
                    <div class="imagen">
                        <?php if(is_file($_SERVER["DOCUMENT_ROOT"] .  '/imagenes_articulos/' . $v['articulo_id'] . '_1' . '.jpg')): ?>
                            <?php $url = '/imagenes_articulos/' . $v['articulo_id'] . '_1' . '.jpg' ?>
                        <?php else: ?>
                            <?php $url = '/imagenes_articulos/sin-imagen.jpg' ?>
                        <?php endif; ?>
                        <?= anchor('/articulos/buscar/' . $v['articulo_id'],
                                    img($url)) ?>
                    </div>
                    <div class="precio">
                        <?= $v['precio'] ?>
                    </div>
                    <div class="nombre">
                        <?= anchor('/articulos/buscar/' . $v['articulo_id'], $v['nombre']) ?>
                    </div>
                    <div class="etiquetas">
                        <?= $v['etiquetas'] ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <h3>No tienes ventas actualmente >.<</h3>
        <?php endif; ?>
    </div>
    <h2>Mis Compras</h2>
    <div class="">
        <?php if( ! empty($articulos_comprados)): ?>
            <?php foreach ($articulos_comprados as $v): ?>
                <div class="articulo">
                    <div class="imagen">
                        <?php if(is_file($_SERVER["DOCUMENT_ROOT"] .  '/imagenes_articulos/' . $v['articulo_id'] . '_1' . '.jpg')): ?>
                            <?php $url = '/imagenes_articulos/' . $v['articulo_id'] . '_1' . '.jpg' ?>
                        <?php else: ?>
                            <?php $url = '/imagenes_articulos/sin-imagen.jpg' ?>
                        <?php endif; ?>
                        <?= anchor('/articulos/buscar/' . $v['articulo_id'],
                                    img($url)) ?>
                    </div>
                    <div class="precio">
                        <?= $v['precio'] ?>
                    </div>
                    <div class="nombre">
                        <?= anchor('/articulos/buscar/' . $v['articulo_id'], $v['nombre']) ?>
                    </div>
                    <div class="etiquetas">
                        <?= $v['etiquetas'] ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <h3>No tienes compras actualmente >.<</h3>
        <?php endif; ?>
    </div>
    <h2>Mis Articulos Favoritos</h2>
    <div class="">
        <?php if( ! empty($articulos_favoritos)): ?>
            <?php foreach ($articulos_favoritos as $v): ?>
                <div class="articulo">
                    <div class="imagen">
                        <?php if(is_file($_SERVER["DOCUMENT_ROOT"] .  '/imagenes_articulos/' . $v['articulo_id'] . '_1' . '.jpg')): ?>
                            <?php $url = '/imagenes_articulos/' . $v['articulo_id'] . '_1' . '.jpg' ?>
                        <?php else: ?>
                            <?php $url = '/imagenes_articulos/sin-imagen.jpg' ?>
                        <?php endif; ?>
                        <?= anchor('/articulos/buscar/' . $v['articulo_id'],
                                    img($url)) ?>
                    </div>
                    <div class="precio">
                        <?= $v['precio'] ?>
                    </div>
                    <div class="nombre">
                        <?= anchor('/articulos/buscar/' . $v['articulo_id'], $v['nombre']) ?>
                    </div>
                    <div class="etiquetas">
                        <?= $v['etiquetas'] ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <h3>No tienes articulos en la seccion de favoritos actualmente >.<</h3>
        <?php endif; ?>
    </div>
    <h2>Mis Valoraciones</h2>
    <h4>Valoraciones en Ventas</h4>
    <div class="">
        <?php foreach ($valoraciones_ventas as $v): ?>
            <?php if($v['valoracion'] === NULL) continue; ?>
            <div class="">
                <div class="">
                    <h5>Comprador =>
                        <?= anchor('/usuarios/perfil/' .
                            $v['comprador_id'],
                            $v['comprador_nick']) ?>
                    </h5>
                </div>
                <div class="">
                    <p>Le vendisté a <?= $v['comprador_nick'] ?>
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
    <h4>Valoraciones en Compras</h4>
    <div class="">
        <?php foreach ($valoraciones_compras as $v): ?>
            <?php if($v['valoracion'] === NULL) continue; ?>
            <div class="">
                <div class="">
                    <h5>Vendedor =>
                        <?= anchor('/usuarios/perfil/' .
                            $v['vendedor_id'],
                            $v['vendedor_nick']) ?>
                    </h5>
                </div>
                <div class="">
                    <p><?= $v['vendedor_nick'] ?> te
                        vendió <?= $v['nombre'] ?></p>
                </div>
                <div class="valoracion" value="<?= $v['valoracion'] ?>">
                </div>
                <div class="">
                    <p><?= $v['valoracion_text'] ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <h2>Mis PM's</h2>
    <h4>PM's no vistos</h4>
    <div class="">
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
    <h4>PM's vistos</h4>
    <?php foreach ($pm_vistos as $v): ?>
        <div class="">
            <?php if(is_file($_SERVER["DOCUMENT_ROOT"] .  '/imagenes_usuarios/' . $v['emisor_id'] . '_thumbnail.jpeg')): ?>
                <?php $url = '/imagenes_usuarios/' . $v['emisor_id'] . '_thumbnail.jpeg' ?>
            <?php else: ?>
                <?php $url = '/imagenes_usuarios/sin-imagen.jpg' ?>
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
</body>
</html>
