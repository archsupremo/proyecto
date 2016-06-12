<!DOCTYPE html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>BuyAndSell</title>
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
    <link href="http://cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css"
          rel="stylesheet">
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
    <link href="/tag/jquery.tag-editor.css" rel="stylesheet">
    <link href="/css/estilos_mapa.css" rel="stylesheet">
    <link href="/css/alineacion_bloques.css" rel="stylesheet">
    <link href="/css/animacion.css" rel="stylesheet">
    <link href="/css/imagen_error.css" rel="stylesheet">
    <link href="/css/imagen_perfil.css" rel="stylesheet">
    <link href="/css/estilos_slider.css" rel="stylesheet">
    <link href="/css/footer.css" rel="stylesheet">

    <!-- Foundation y rateYo -->
    <script src="/js/vendor/modernizr.js"></script>
    <script src="/js/vendor/jquery.js"></script>
    <script src="/js/foundation.min.js"></script>
    <script src="/js/foundation/foundation.alert.js"></script>
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

    <script src="/tag/jquery.tag-editor.min.js"></script>
    <script src="/tag/jquery.caret.min.js"></script>
    <script src="/nested/core/jquery.shapeshift.js"></script>

    <script src="/cookie/jquery.cookie.js"></script>
  </head>
  <body>
    <header class="">
        <nav class="top-bar" data-topbar role="navigation" data-options="is_hover: false">
          <ul class="title-area">
            <li class="name">
              <h1><a href="/frontend/portada">BuyAndSell</a></h1>
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
        <span itemprop="breadcrumb">
            <?= miga_pan() ?>
        </span>
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
          <ul>
        </article>
        <article class="small-6 medium-3 large-2 columns">
          <h4>Sigueme!!!</h4>
          <ul class="footer-links">
            <li><a href="https://github.com/archsupremo">GitHub</a></li>
          <ul>
        </article>
      </section>
    </footer>
    <script>
      $(document).foundation();
    </script>
  </body>
</html>
