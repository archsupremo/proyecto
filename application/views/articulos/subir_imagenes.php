<?php template_set('title', 'Subir Articulo') ?>

<div class="row">
    <div class="large-12 large-centered columns menu-login">
        <?= form_open_multipart('/articulos/subir_imagenes', 'class="articulos_subir"') ?>
          <div class="dropzone needsclick" id="dropzone">
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
          <?= form_submit('subir', 'Subir', 'class="success button small radius" id="subir"') ?>
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
        url: '/articulos/subir_imagenes',
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
                    url: "<?= base_url() ?>articulos/borrar_imagen/<?= $articulo_id ?>/" + file.num,
                    type: 'GET',
                    async: true,
                    success: function() { },
                    error: function (error) { },
                }).done(function () {
                    archivos.unshift(file.num);
                });
            });
            $.ajax({
                url: "<?= base_url() ?>articulos/obtener_imagen/<?= $articulo_id ?>",
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
