<?php template_set('title', 'Subir Articulo') ?>
<div class="row">
    <div class="large-6 large-centered columns menu-login" id="formulario_articulo">
          <div data-alert class="alert-box alert radius alerta" id="errores_formulario">
            <a href="#" class="close">&times;</a>
          </div>
        <?php if ( ! empty($error)): ?>
            <div class="alert-box alert radius alerta" role="alert">
              <?= $error ?>
            </div>
        <?php endif ?>
          <div class="alert-box success radius alerta" role="alert">
              <p>Formatos Admitidos: jpeg, jpg y jpe</p>
              <p>Tamaño Maximo: 500 Kbytes</p>
              <p>Alto Maximo: 5000 pixeles</p>
              <p>Ancho Maximo: 5000 pixeles</p>
          </div>
        <?= form_open_multipart('/articulos/subir') ?>
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
              <?= form_dropdown('categoria_id', $categorias) ?>
          </div>
          <div class="row">
              <div class="large-12 columns">
                <div class="row collapse">
                  <div class="small-1 columns">
                    <span class="prefix">€</span>
                  </div>
                  <div class="small-11 columns">
                    <input type="number" step="0.01"
                           placeholder="precio..."
                           name="precio" id="precio">
                  </div>
                </div>
              </div>
          </div>
          <div class="">
            <?= form_label('Foto:', 'foto') ?>
            <?= form_upload('foto', set_value('foto', '', FALSE),
                           'id="foto" accept="image/*" class=""') ?>
          </div>
          <?= form_submit('subir', 'Subir', 'class="success button small radius" id="subir"') ?>
          <?= anchor('/frontend/portada', 'Volver a pagina principal', 'class="alert button small radius" role="button"') ?>
        <?= form_close() ?>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
         var _URL = window.URL || window.webkitURL;
         $('#errores_formulario').hide();
         $('#foto').change(function () {
             var foto = $(this);
             var sizeByte = this.files[0].size;
             var siezekiloByte = parseInt(sizeByte / 1024);
             if(siezekiloByte > 500){
                 alert('El tamaño de la imagen supera el limite permitido');
                 foto.val('');
             }

             var file, img;
             if ((file = this.files[0])) {
                 img = new Image();
                 img.onload = function () {
                     if (this.width > 5 || this.height > 5000) {
                         alert('Las dimensiones de la imagen supera el limite permitido');
                         foto.val('');
                     }
                 };
                 img.src = _URL.createObjectURL(file);
             }
         });
         $('#subir').click(function (evento) {
             var mensajes = [];
             var nombre = $('#nombre');
             var foto = $('#foto');
             if (nombre.val() == "") {
                 mensajes.push("El nombre no puede estar vacio. Ponga uno.");
                 evento.preventDefault();
             }
             if (foto.val() == "") {
                 if(!confirm('¿Seguro que quieres subir el articulo sin imagen?')) {
                     evento.preventDefault();
                 }
             }
             if (mensajes.length > 0) {
                 $('#errores_formulario').children("p").remove();
                 $('#errores_formulario').show();
                 for (var i = 0; i < mensajes.length; i++) {
                     var p = "<p>" + mensajes[i] + "</p>";
                     $('#errores_formulario').prepend(p);
                 }
             }
         });
    });

</script>
