<?php template_set('title', 'Editar Articulo') ?>
<div class="row">
    <div class="large-7 columns menu-login articulos_subir">
          <div class="dropzone needsclick articulos_subir" id="dropzone">
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
          </div>
    </div>
    <div class="large-5 columns menu-login" id="formulario_articulo">
          <?php if (count(error_array()) > 0): ?>
              <div data-alert class="alert-box alert radius alerta">
                <?= validation_errors() ?>
                <a href="#" class="close">&times;</a>
              </div>
          <?php endif ?>
        <?= form_open_multipart('/articulos/editar_articulo/' . $articulo['id']) ?>
          <div class="">
            <?= form_label('Nombre:', 'nombre') ?>
            <?= form_input('nombre', set_value('nombre', $articulo['nombre'], FALSE),
                           'id="nombre" class=""') ?>
          </div>
          <div class="">
            <?= form_label('Descripcion:', 'descripcion') ?>
            <?= form_input('descripcion', set_value('descripcion', $articulo['descripcion'], FALSE),
                           'id="descripcion" class=""') ?>
          </div>
          <div class="">
              <?= form_label('Etiquetas:', 'tags') ?>
              <?= form_input('tags', set_value('tags', $articulo['etiquetas'], FALSE),
                             'id="tags" class="" placeholder="Escriba las etiquetas del producto..."') ?>
          </div>
          <br>
          <div class="row">
              <div class="large-12 columns">
                <div class="row collapse">
                  <div class="small-1 columns">
                    <span class="prefix">€</span>
                  </div>
                  <div class="small-11 columns">
                    <input type="number" min="0" step="0.01"
                           placeholder="precio..."
                           value="<?= set_value('precio', $articulo['precio'], FALSE) ?>"
                           name="precio" id="precio">
                  </div>
                </div>
              </div>
          </div>
          <?= form_submit('editar', 'Editar', 'class="success button small radius" id="subir"') ?>
          <?= anchor('/frontend/portada', 'Volver a pagina principal', 'class="alert button small radius" role="button"') ?>
        <?= form_close() ?>
    </div>
</div>

<script type="text/javascript">
    var archivos = [1, 2, 3, 4];
    Dropzone.options.dropzone = {
        addRemoveLinks: true,
        paramName: "foto",
        maxFilesize: 0.5, // MB, maximo de archivos
        maxThumbnailFilesize: 1,// MB,  Cuando el peso del archivo excede este límite, no se generará la imagen en miniatura
        maxFiles: 4,
        method: "post",
        thumbnailWidth: "200",
        thumbnailHeight: "200",
        acceptedFiles: ".jpeg,.jpg,.jpe,.JPEG,.JPG,.JPE",// Archivos permitidos
        url: '/articulos/subir_imagenes/<?= $articulo['id'] ?>',
        accept: function(file, done) {
            done();
        },
        init: function() {
            var drop = this;
            drop.on("maxfilesexceeded", function(file){
                drop.removeFile(file);
                alert("Solo se permiten un maximo de 4 fotos por articulo.");
            });
            drop.on("addedfile", function (file) {
                if(drop.files.length > 4) {
                    drop.removeFile(file);
                    alert("Solo se permiten un maximo de 4 fotos por articulo.");
                    return;
                }
                file.num = archivos.shift();
            });
            drop.on("removedfile", function (file) {
                $.ajax({
                    url: "<?= base_url() ?>articulos/borrar_imagen/<?= $articulo['id'] ?>/" + file.num,
                    type: 'GET',
                    async: true,
                    success: function() { },
                    error: function (error) { },
                }).done(function () {
                    archivos.unshift(file.num);
                });
            });
            $.ajax({
                url: "<?= base_url() ?>articulos/obtener_imagen/<?= $articulo['id'] ?>",
                type: 'GET',
                async: true,
                success: function(respuesta) {
                    for(var imagen in respuesta.imagenes) {
                        var mockFile = {
                            name: respuesta.imagenes[imagen].name,
                            size: respuesta.imagenes[imagen].size,
                            num: respuesta.imagenes[imagen].numero
                        };
                        var new_array = [];
                        for(var a in archivos) {
                            if(archivos[a] != mockFile.num) {
                                new_array.push(archivos[a]);
                            }
                        }
                        archivos = new_array;

                        drop.options.addedfile.call(drop, mockFile);
                        drop.createThumbnailFromUrl(mockFile, "/imagenes_articulos/" + respuesta.imagenes[imagen].imagen);
                        drop.files.push(mockFile);
                        mockFile.previewElement.classList.add('dz-success');
                        mockFile.previewElement.classList.add('dz-complete');
                    }
                },
                error: function (error) { },
                dataType: 'json'
            });

            this.on("sendingmultiple", function() {
            });
            this.on("successmultiple", function(files, response) {
            });
            this.on("errormultiple", function(files, response) {
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
