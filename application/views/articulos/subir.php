<?php template_set('title', 'Subir Articulo') ?>
<div class="row">
    <div class="large-5 columns menu-login" id="formulario_articulo">
          <div data-alert class="alert-box alert radius alerta" id="errores_formulario">
            <a href="#" class="close">&times;</a>
          </div>
        <?php if ( ! empty($error)): ?>
            <div class="alert-box alert radius alerta" role="alert">
              <?= $error ?>
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
              <?= form_dropdown('categoria_id', $categorias) ?>
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
                           name="precio" id="precio">
                  </div>
                </div>
              </div>
          </div>
          <?= form_submit('subir', 'Subir', 'class="success button small radius" id="subir"') ?>
          <?= anchor('/frontend/portada', 'Volver a pagina principal', 'class="alert button small radius" role="button"') ?>
        <?= form_close() ?>
    </div>
    <div class="large-6 columns menu-login" id="formulario_articulo">
        <div id="dropzone">
            <?= form_open_multipart('/articulos/subir', 'class="dropzone needsclick" id="demo"') ?>
                <div class="dz-message needsclick">
                  <div class="alert-box success radius alerta text-center" role="alert">
                      <p>Formatos Admitidos: jpeg, jpg y jpe</p>
                      <p>Tamaño Maximo: 500 Kbytes</p>
                      <p>Alto Maximo: 5000 pixeles</p>
                      <p>Ancho Maximo: 5000 pixeles</p>
                  </div>
                  <span class="note needsclick">Clicka aqui para
                      <strong>subir</strong> imagenes.
                  </span>
                </div>
            <?= form_close() ?>
        </div>
    </div>
</div>

<script type="text/javascript">
    Dropzone.options.demo = {
        addRemoveLinks: true,
        paramName: "file",
        maxFilesize: 0.5, // MB, maximo de archivos
        maxThumbnailFilesize: 1,// MB,  Cuando el peso del archivo excede este límite, no se generará la imagen en miniatura
        maxFiles: 4,
        acceptedFiles: ".jpeg,.jpg,.jpe,.JPEG,.JPG,.JPE",// Archivos permitidos
        autoProcessQueue: false,
        uploadMultiple: true,
        accept: function(file, done) {
            done();
        },
        init: function() {
            var drop = this;
            drop.on("maxfilesexceeded", function(file){
                alert("Solo se permiten un maximo de 4 fotos por articulo.");
            });
            $('#datos').submit(function (evento) {
                drop.processQueue();
            });
        },
        dictCancelUpload: true, //cancelar archivo al subir
        dictCancelUploadConfirmation: true, //confirma la cancelacion
        dictRemoveFile: 'Eliminar',
        dictMaxFilesExceeded: 'No se admiten mas ficheros.',
        dictFallbackMessage: 'Tu navegador web no permite el drag de subida de imagenes.',
        dictInvalidFileType: 'Archivo invalido.',
    };
</script>

<script type="text/javascript">
    $(document).ready(function() {
         $('#errores_formulario').hide();
         $('#subir').click(function (evento) {
             var mensajes = [];
             var nombre = $('#nombre');
             if (nombre.val() == "") {
                 mensajes.push("El nombre no puede estar vacio. Ponga uno.");
                 evento.preventDefault();
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
