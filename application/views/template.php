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
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
    <link href="/tag/jquery.tag-editor.css" rel="stylesheet">
    <link href="/css/estilos_mapa.css" rel="stylesheet">
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

    <style media="screen">
        .menu-login {
            margin-top: 3.5em;
        }
        .flashdata {
            margin-top: 5em;
        }
        .imagen_nick {
            height: 40%;
            width: 40%;
            border-radius: 25px;
        }
        .input_validation {
            margin-bottom: 0em;
        }
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }
        .front_button {
            background-color: transparent !important;
            border: 0px;
            color: #000;
            padding: 0px;
            padding-top: 0.9375em;
        }
        .box {
			-moz-border-radius:3px;
  		    -khtml-border-radius: 3px;
			-webkit-border-radius:3px;
			border-radius:3px;

            -webkit-transition: all 300ms cubic-bezier(1.000, 0.000, 0.000, 1.000);
               -moz-transition: all 300ms cubic-bezier(1.000, 0.000, 0.000, 1.000);
                -ms-transition: all 300ms cubic-bezier(1.000, 0.000, 0.000, 1.000);
                 -o-transition: all 300ms cubic-bezier(1.000, 0.000, 0.000, 1.000);
                    transition: all 300ms cubic-bezier(1.000, 0.000, 0.000, 1.000); /* easeInOutExpo */

              -webkit-transition-property: left, right, top;
                 -moz-transition-property: left, right, top;
                  -ms-transition-property: left, right, top;
                   -o-transition-property: left, right, top;
                      transition-property: left, right, top;
        	}

    </style>
  </head>
  <body>
    <header class="">
        <nav class="top-bar" data-topbar role="navigation" data-options="is_hover: false">
          <ul class="title-area">
            <li class="name">
              <h1><a href="/frontend/portada">BuyAndSell</a></h1>
            </li>
             <!-- Remove the class "menu-icon" to get rid of menu icon.
                  Take out "Menu" to just have icon alone -->
            <li class="toggle-topbar menu-icon">
                <a href="#">
                    <span>Menu</span>
                </a>
            </li>
          </ul>
          <section class="top-bar-section">
            <!-- Right Nav Section -->
            <ul class="right">
                <?= login() ?>
                <li class="divider"></li>
                <?= registro() ?>
                <li class="divider"></li>
              <li class="has-dropdown">
                <a href="#"><?= nick() ?></a>
                <ul class="dropdown">
                  <?= usuario_logueado() ?>
                </ul>
              </li>
            </ul>
          </section>
        </nav>
    </header>
    <?= mensajes() ?>
    <?= $contents ?>
    <hr>
    <footer role="contentinfo">
        <section role="main">
            <h3>Desarrolladores <br> y <br> Analistas</h3>
            <article>
                <img src="imagenes/llave.png" class="llave" alt="llave">
                <p title="Webmaster">Guillermo López García</p>
            </article>
        </section>
        <section role="main">
            <h3>Terminos y <br> Condiciones <br> de uso</h3>
            <article>
                <img src="imagenes/llave.png" class="llave" alt="llave">
                <div class="condiciones">
                    <ul>
                        <li>
                            <a href=""
                               title="Terminos Y Condiciones de Uso del Sitio Web">
                               Sitio Web
                            </a>
                        </li>
                        <li>
                            <a href=""
                               title="Licencia de Uso de NCSOFT para nosotros">
                               Licencia de NCSoft
                            </a>
                        </li>
                        <li>
                            <a href=""
                               title="Autorizacion de Servidores para el Indexado">
                               Autorizacion de Servidores
                            </a>
                        </li>
                        <li>
                            <a href=""
                               title="Documento que especifica nuestras reglas para indexar Servidores">
                               Reglas de Indexado
                            </a>
                        </li>
                    </ul>
                </div>
            </article>
        </section>
        <section role="main">
            <h3>Tecnologias<br>Empleadas</h3>
            <article>
                <img src="imagenes/llave.png" class="llave" alt="llave">
                <img src="imagenes/html5ycss3.png" class="tecnologias" alt="html5 y css3" title="HTML 5 y CSS 3">
            </article>
        </section>
    </footer>
    <script>
      $(document).foundation();
    </script>
  </body>
</html>
