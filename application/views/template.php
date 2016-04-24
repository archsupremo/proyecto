<!DOCTYPE html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>BuyAndSell - <?= nick() ?></title>
    <link rel="stylesheet" href="/css/main.css" />
    <link rel="stylesheet" href="/css/foundation.css" />
    <link rel="stylesheet" href="/rateyoJquery/jquery.rateyo.css"/>
    <link rel="stylesheet" href="/slick/slick.css">
    <link rel="stylesheet" href="/slick/slick-theme.css">

    <!-- Foundation y rateYo -->
    <script src="/js/vendor/modernizr.js"></script>
    <script src="/js/vendor/jquery.js"></script>
    <script src="/js/foundation.min.js"></script>
    <script src="/js/foundation/foundation.alert.js"></script>
    <script src="/rateyoJquery/jquery.rateyo.js"></script>
    <script src="/slick/slick.min.js"></script>

    <!-- TabTab -->
    <script src="/js/velocity.min.js"></script>
    <script src="/js/tabtab.min.js"></script>

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
            width: 140%;
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
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }
    </style>
  </head>
  <body>
    <header class="">
        <div class="large-2 columns">
            <a href="/frontend/portada" class="button small" role="button">BuyAndSell</a>
        </div>
        <form action="/frontend/portada/index" class="large-6 columns" method="post">
            <div class="row">
                <?= form_dropdown('categoria', busqueda_select(), '', 'class="large-4 columns"') ?>
                <div class="large-4 columns busqueda">
                    <input class="" name="nombre" type="text" value="<?= busqueda() ?>">
                </div>
                <div class="large-4 columns">
                  <input type="submit" name="buscar" class="button radius tiny" value="Buscar...">
                </div>
                </div>
            </div>
        </form>
        <?= login() ?>
        <?= registro() ?>
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
