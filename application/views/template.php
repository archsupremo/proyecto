<!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>BuyAndSell</title>
    <link rel="stylesheet" href="/css/foundation.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/foundation/6.2.0/foundation.min.css">
    <script src="/js/vendor/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/foundation/6.2.0/foundation.min.js"></script>
  </head>
  <body>
    <header class="expanded row">
        <div class="large-2 columns">
            <a href="/frontend/portada" class="button" role="button">BuyAndSell</a>
        </div>
        <form action="/frontend/portada/index" class="large-6 columns" method="post">
            <div class="input-group row">
                <select class="large-4 columns" name="categoria">
                    <?= busqueda_select() ?>
                </select>
                <div class="large-8 columns">
                    <input class="input-group-field" name="nombre" type="text" value="<?= busqueda() ?>">
                    <div class="input-group-button">
                      <input type="submit" name="buscar" class="button" value="Buscar...">
                    </div>
                </div>
            </div>
        </form>
        <?= login() ?>
        <div class="large-2 columns">
            <a href="/usuarios/registro" class="button" role="button">Registro</a>
        </div>
    </header>
    <?= mensajes() ?>
    <?= $contents ?>

    <footer>

    </footer>
    <script src="/js/vendor/jquery.min.js"></script>
    <script src="/js/vendor/what-input.min.js"></script>
    <script src="/js/foundation.min.js"></script>
    <script>
      $(document).foundation();
    </script>
  </body>
</html>
