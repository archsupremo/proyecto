<!DOCTYPE html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>BuyAndSell</title>
    <link rel="stylesheet" href="/css/foundation.css" />
    <link rel="stylesheet" href="/rateyoJquery/jquery.rateyo.css"/>
    <!-- <link href="http://steam.local/css/star-rating.min.css" rel="stylesheet" type="text/css" /> -->
    <script src="/js/vendor/modernizr.js"></script>
    <script src="/js/vendor/jquery.js"></script>
    <script src="/js/foundation.min.js"></script>
    <script src="/js/foundation/foundation.alert.js"></script>
    <script src="/rateyoJquery/jquery.rateyo.js"></script>

    <!-- <script id="rating" src="http://steam.local/js/star-rating.min.js" type="text/javascript"></script> -->
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
    </style>
  </head>
  <body>
    <header class="">
        <div class="large-2 columns">
            <a href="/frontend/portada" class="button small" role="button">BuyAndSell</a>
        </div>
        <form action="/frontend/portada/index" class="large-6 columns" method="post">
            <div class="row">
                <select class="large-4 columns" name="categoria">
                    <?= busqueda_select() ?>
                </select>
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
