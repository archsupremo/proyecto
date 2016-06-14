<!DOCTYPE html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>BuyAndSell</title>

    <!-- Estilos de plugins y framework css -->
    <link rel="stylesheet" href="/css/foundation.css" />
    <link rel="stylesheet" href="/rateyoJquery/jquery.rateyo.css"/>
    <link rel="stylesheet" href="/slick/slick.css">
    <link rel="stylesheet" href="/slick/slick-theme.css">
    <link rel="stylesheet" href="/css/basic.css">
    <link rel="stylesheet" href="/css/dropzone.css">
    <link rel="stylesheet" href="/toogle/css/toggles.css">
    <link rel="stylesheet" href="/toogle/css/toggles-full.css">
    <link rel="stylesheet" href="/toogle/css/themes/toggles-all.css">
    <link rel="stylesheet" href="/rubytabs/rubytabs.css">
    <link href="/tag/jquery.tag-editor.css" rel="stylesheet">

    <link href="http://cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />

    <!-- Estilos propios -->
    <link href="/css/estilos_mapa.css" rel="stylesheet">
    <link href="/css/alineacion_bloques.css" rel="stylesheet">
    <link href="/css/animacion.css" rel="stylesheet">
    <link href="/css/imagen_error.css" rel="stylesheet">
    <link href="/css/imagen_perfil.css" rel="stylesheet">
    <link href="/css/estilos_slider.css" rel="stylesheet">
    <link href="/css/estilos_dropzonejs_propios.css" rel="stylesheet">
    <link href="/css/footer.css" rel="stylesheet">

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="57x57" href="/favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="/favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="/favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon/favicon-16x16.png">
    <link rel="manifest" href="/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <!-- FIN FAVICON -->

    <!-- Foundation -->
    <script src="/js/vendor/modernizr.js"></script>
    <script src="/js/vendor/jquery.js"></script>
    <script src="/js/foundation.min.js"></script>
    <script src="/js/foundation/foundation.alert.js"></script>

    <!-- RateYo -->
    <script src="/rateyoJquery/jquery.rateyo.js"></script>
    <script src="/slick/slick.min.js"></script>
    <script src="/js/velocity.min.js"></script>

    <!-- DropZone -->
    <script src="/js/dropzone.js"></script>

    <!-- Toggle -->
    <script src="/toogle/toggles.js"></script>

    <!-- RubyTabs -->
    <script src="/rubytabs/rubytabs.js"></script>
    <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>

    <!-- Tags -->
    <script src="/tag/jquery.tag-editor.min.js"></script>
    <script src="/tag/jquery.caret.min.js"></script>

    <!-- Shapeshift -->
    <script src="/nested/core/jquery.shapeshift.js"></script>

    <!-- JQuery cookie -->
    <script src="/cookie/jquery.cookie.js"></script>
  </head>
  <body>
    <header class="">
        <nav class="top-bar" data-topbar role="navigation" data-options="is_hover: false">
          <ul class="title-area">
            <li class="name">
              <h1>
                  <a href="/frontend/portada">BuyAndSell</a>
              </h1>
            </li>
            <li class="toggle-topbar menu-icon">
                <a href="#">
                    <span>Menu</span>
                </a>
            </li>
          </ul>
          <section class="top-bar-section">
            <ul class="right">
                <?= login() ?>
                <li class="divider"></li>
                <?= registro() ?>
                <li class="divider"></li>
                <?php if(logueado()): ?>
                    <li class="has-dropdown">
                        <a href="#"><?= nick() ?></a>
                        <ul class="dropdown">
                            <?= usuario_logueado() ?>
                        </ul>
                    </li>
                <?php endif; ?>
            </ul>
          </section>
        </nav>
    </header>
    <section itemscope itemtype="http://schema.org/WebPage">
        <div itemprop="breadcrumb">
            <?= miga_pan() ?>
        </div>
    </section>

    <?= mensajes() ?>
    <?= $contents ?>

    <footer class="footer" role="contentinfo">
      <section class="row full-width" role="main">
        <article class="small-12 medium-3 large-4 columns">
          <i class="fi-laptop"></i>
          <p>Sitio web orientado a la compra-venta de articulos de segunda mano entre los distintos usuarios</p>
        </article>
        <article class="small-12 medium-3 large-4 columns">
          <i class="fi-html5"></i>
          <i class="fi-css3"></i>
          <p>Basado en las tecnologías HTML5 y CSS3 para asegurar la mayor accesibilidad y usabilidad posible para el usuario.</p>
        </article>
        <article class="small-6 medium-3 large-2 columns">
          <h4>Terminos y Condiciones de uso</h4>
          <ul class="footer-links">
              <li>
                  <a href="/info/uso_sitio"
                     title="Términos Y Condiciones de Uso del Sitio Web">
                     Uso del Sitio Web
                  </a>
              </li>
              <li>
                  <a href="/info/politica_datos"
                     title="Nuestra politica sobre sus datos">
                     Politica de Privacidad
                  </a>
              </li>
              <li>
                  <a href="/info/cookies"
                     title="Cookies Web">
                     Cookies
                  </a>
              </li>
              <li>
                  <a href="/info/reglas_convivencia"
                     title="Reglas para la convivencia de los distintos usuarios">
                     Reglas de Convivencia
                  </a>
              </li>
            <!-- <li><a href="#">FAQ's</a></li> -->
            </ul>
        </article>
        <article class="small-6 medium-3 large-2 columns">
          <h4>Sigueme!!!</h4>
          <ul class="footer-links">
            <li><a href="https://github.com/archsupremo">GitHub</a></li>
          </ul>
        </article>
      </section>
    </footer>
    <script>
      $(document).foundation();
    </script>
  </body>
</html>
