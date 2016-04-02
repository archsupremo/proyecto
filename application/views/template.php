<!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>BuyAndSell</title>
    <link rel="stylesheet" href="/css/foundation.css" />
    <script src="/js/vendor/modernizr.js"></script>

    <style media="screen">
        .busqueda {
            padding: 0;
        }
        .menu-login {
            margin-top: 3.5em;
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
        <div class="large-1 columns">
            <a href="/usuarios/registro" class="button small round right" role="button">Registro</a>
        </div>
    </header>
    <?= mensajes() ?>
    <?= $contents ?>

    <footer>

    </footer>

    <script src="/js/vendor/jquery.js"></script>
    <script src="/js/foundation.min.js"></script>
    <!-- <script src="/js/foundation.alert.js"></script> -->
    <script>
      $(document).foundation();
    </script>
  </body>
</html>
