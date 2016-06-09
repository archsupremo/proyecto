<?php template_set('title', 'Subir Articulo') ?>
<div class="row">
    <div class="large-8 large-centered columns menu-login" id="formulario_articulo">
          <?php if ( ! empty(error_array())): ?>
              <div data-alert class="alert-box alert radius alerta">
                <?= validation_errors() ?>
                <a href="#" class="close">&times;</a>
              </div>
          <?php endif ?>
        <?= form_open_multipart('/articulos/subir', 'id="datos"') ?>
          <div class="">
            <?= form_label('Nombre:', 'nombre') ?>
            <?= form_input('nombre', set_value('nombre', '', FALSE),
                           'id="nombre" class=""') ?>
          </div>
          <div class="">
            <?= form_label('Descripcion:', 'descripcion') ?>
            <?= form_input('descripcion', set_value('descripcion', '', FALSE),
                           'id="descripcion" class=""') ?>
          </div>
          <div class="">
              <?= form_label('Etiquetas:', 'tags') ?>
              <?= form_input('tags', set_value('tags', '', FALSE),
                             'id="tags" class="" placeholder="Escriba las etiquetas del producto..."') ?>
          </div>
          <div class="row">
              <div class="large-12 columns">
                <div class="row collapse">
                  <div class="small-1 columns">
                    <span class="prefix">€</span>
                  </div>
                  <div class="small-11 columns">
                    <input type="number" min="0" step="0.01"
                           placeholder="precio..."
                           value="<?= set_value('precio', '', FALSE) ?>"
                           name="precio" id="precio">
                  </div>
                </div>
              </div>
          </div>
          <?= form_submit('subir', 'Subir', 'class="success button small radius" id="subir"') ?>
          <?= anchor('/frontend/portada', 'Volver a pagina principal', 'class="alert button small radius" role="button"') ?>
        <?= form_close() ?>
    </div>
</div>
<script>
    $('#tags').tagEditor({
        placeholder: "Escriba las etiquetas del producto...",
        autocomplete: {
            position: { collision: 'flip' },
            source: "<?= base_url() ?>etiquetas/buscar/"
        },
        forceLowercase: false
    });
</script>
<script type="text/javascript">
    // $(document).ready(function() {
    //      $('#subir').click(function (evento) {
    //          var mensajes = [];
    //          var nombre = $('#nombre');
    //          if (nombre.val() == "") {
    //              mensajes.push("El nombre no puede estar vacio. Ponga uno.");
    //              evento.preventDefault();
    //          }
    //          if (mensajes.length > 0) {
    //              var div = '<div data-alert class="alert-box alert radius alerta">';
    //              for (var i = 0; i < mensajes.length; i++) {
    //                  div += '<p>' + mensajes[i] + '</p>';
    //              }
    //              div += '<a href="#" class="close">&times;</a>';
    //              div += '</div>';
    //              $('#formulario_articulo').prepend(div);
    //          }
    //      });
    // });
</script>
