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

    <style media="screen">
        .busqueda {
            padding: 0;
        }
        .menu-login {
            margin-top: 3.5em;
        }
        .flashdata {
            margin-top: 5em;
        }
        .articulos {
            margin: 0.5em;
        }
        .mapa_perfil {
            height: 600px;
        }
        .mapa_index {
            height: 450px;
            width: 100%;
        }
        .mapa {
            margin-left: -8em;
            margin-right: 8em;
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

        /* Formulario de busqueda mapa */
        .controls {
          margin-top: 10px;
          border: 1px solid transparent;
          border-radius: 2px 0 0 2px;
          box-sizing: border-box;
          -moz-box-sizing: border-box;
          height: 32px;
          outline: none;
          box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
        }

        #pac-input {
          background-color: #fff;
          font-family: Roboto;
          font-size: 15px;
          font-weight: 300;
          margin-left: 12px;
          padding: 0 11px 0 13px;
          text-overflow: ellipsis;
          width: 300px;
        }

        #pac-input:focus {
          border-color: #4d90fe;
        }

        .pac-container {
          font-family: Roboto;
        }

        #type-selector {
          color: #fff;
          background-color: #4d90fe;
          padding: 5px 11px 0px 11px;
        }

        #type-selector label {
          font-family: Roboto;
          font-size: 13px;
          font-weight: 300;
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

    <footer>
    </footer>
    <script>
      $(document).foundation();
    </script>
  </body>
</html>
