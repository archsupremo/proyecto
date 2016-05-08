<?php template_set('title', 'Editar Perfil de Usuario') ?>

<div class="row">
    <div class="large-6 columns menu-login">
        <?php if ( ! empty(error_array())): ?>
          <div data-alert class="alert-box alert radius alerta">
            <?= validation_errors() ?>
            <a href="#" class="close">&times;</a>
          </div>
        <?php endif ?>
        <?= form_open_multipart('/usuarios/editar_perfil/' . $usuario['id']) ?>
          <div class="">
            <?= form_label('Nick:', 'nick') ?>
            <?= form_input('nick', set_value('nick', $usuario['nick'], FALSE),
                           'id="nick" class="input_validation"') ?>
            <!-- <small class="error">Invalid entry</small> -->
          </div>
          <div class="">
            <?= form_label('Email:', 'email') ?>
            <?= form_input(array(
                          'type' => 'email',
                          'name' => 'email',
                          'id' => 'email',
                          'value' => set_value('email', $usuario['email'], FALSE),
                          'class' => ''
            )) ?>
            <!-- <small class="error">Invalid entry</small> -->
          </div>
          <div class="">
            <?= form_label('Contraseña Antigua:', 'password_old') ?>
            <?= form_password('password_old', '',
                              'id="password_old" class=""') ?>
          </div>
          <div class="">
            <?= form_label('Contraseña:', 'password') ?>
            <?= form_password('password', '',
                              'id="password" class=""') ?>
          </div>
          <div class="">
            <?= form_label('Confirmar Contraseña:', 'password_confirm') ?>
            <?= form_password('password_confirm', '',
                              'id="password_confirm" class=""') ?>
          </div>
          <div class="">
              <?= form_hidden('latitud', '') ?>
              <?= form_hidden('longitud', '') ?>
              <?= form_checkbox('geolocalizacion', "", TRUE); ?>
              <?= form_label('Desea usted dar su ubicación', 'geolocalizacion') ?>
          </div>
          <div class="">
              <?= form_checkbox('confirm_venta_favorito', "", FALSE); ?>
              <?= form_label('Desea usted que se envie un mensaje de correo<br> '.
                             'electronico cuando un articulo favorito se venda',
                             'confirm_venta_favorito') ?>
          </div>
          <?= form_submit('editar', 'Editar', 'class="success button small radius"') ?>
          <?= anchor('/usuarios/login', 'Volver', 'class="alert button small radius" role="button"') ?>
        <?= form_close() ?>
    </div>
    <div class="large-4 columns menu-login dropzone needsclick" id="dropzone">
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

<script type="text/javascript">
    Dropzone.options.dropzone = {
        addRemoveLinks: true,
        paramName: "foto",
        maxFilesize: 0.5, // MB, maximo de archivos
        maxThumbnailFilesize: 1,// MB,  Cuando el peso del archivo excede este límite, no se generará la imagen en miniatura
        maxFiles: 1,
        method: "post",
        acceptedFiles: ".jpeg,.jpg,.jpe,.JPEG,.JPG,.JPE",// Archivos permitidos
        url: '/usuarios/subir_imagen',
        accept: function(file, done) {
            done();
        },
        init: function() {
            var drop = this;
            drop.on("maxfilesexceeded", function(file){
                drop.removeFile(file);
                alert("Solo se permiten una imagen por perfil.");
            });
            drop.on("addedfile", function (file) {
                if(drop.files.length > 1) {
                    drop.removeFile(drop.files[0]);
                }
            });
            drop.on("removedfile", function (file) {
                $.ajax({
                    url: "<?= base_url() ?>usuarios/borrar_imagen/" + <?= $usuario['id'] ?>,
                    type: 'GET',
                    async: true,
                    success: function() {
                    },
                    error: function (error) {
                    },
                });
            });
            $.ajax({
                url: "<?= base_url() ?>usuarios/obtener_imagen/" + <?= $usuario['id'] ?>,
                type: 'GET',
                async: true,
                success: function(respuesta) {
                    if(respuesta.imagen.name != undefined) {
                        $.each(respuesta, function(key, value){
                            var mockFile = { name: value.name, size: value.size };
                            drop.options.addedfile.call(drop, mockFile);
                            drop.createThumbnailFromUrl(mockFile, "/imagenes_usuarios/" + value.imagen);
                            drop.files.push(mockFile);
                            mockFile.previewElement.classList.add('dz-success');
                            mockFile.previewElement.classList.add('dz-complete');
                        });
                    }
                },
                error: function (error) {
                },
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

<script type="text/javascript">
    var usuario_id = <?= logueado() ? dar_usuario()['id'] : 'undefined' ?>;
    $('#nick').change(function () {
        $.ajax({
            url: "<?= base_url() ?>usuarios/usuarios_nick/" +
                 $(this).val() + "/" + usuario_id,
            type: 'GET',
            async: true,
            success: respuesta,
            error: error,
            dataType: "json"
        });
    });
    $('#email').focusout(function () {
        $.ajax({
            url: "<?= base_url() ?>usuarios/usuarios_email/" +
                 encodeURIComponent($(this).val()) + "/" + usuario_id,
            type: 'GET',
            async: true,
            success: respuesta_email,
            error: error,
            dataType: "json"
        });
    });

    function respuesta(respuesta) {
        $("#nick").siblings().remove(".error");
        if(respuesta.nick_ocupado == true) {
            var small = '<small class="error">';
            small += 'Nick inválido, por favor, escoja otro.';
            small += '<br>Sugerencias:<br>';
            for (var sugerencia in respuesta.sugerencias_nick) {
                var sugerencia = respuesta.sugerencias_nick[sugerencia];
                small += '<br>'+sugerencia+'</br>';
            }
            small += '</small>';
            $("#nick").after(small);
        }
    }
    function respuesta_email(respuesta) {
        $("#email").siblings().remove(".error");
        if(respuesta.email_ocupado == true) {
            var small = '<small class="error">';
            small += 'Email inválido, por favor, escoja otro.';
            small += '</small>';
            $("#email").after(small);
        }
    }
    function error(error) {
        alert("Ha ocurrido el error => " + error.statusText);
    }
</script>
<script type="text/javascript">
    navigator.geolocation.getCurrentPosition(function (posicion) {
        var latitud = posicion.coords.latitude;
        var longitud = posicion.coords.longitude;

        $("input[name=latitud]").first().val(latitud);
        $("input[name=longitud]").first().val(longitud);

        $("input[name=geolocalizacion]").click(function () {
            if($(this).is(':checked')) {
                $("input[name=latitud]").first().val(latitud);
                $("input[name=longitud]").first().val(longitud);
            } else {
                $("input[name=latitud]").first().val("");
                $("input[name=longitud]").first().val("");
            }
        });
    }, function(error) {
        switch(error.code) {
            case error.PERMISSION_DENIED: alert("El usuario no permite compartir datos de geolocalizacion");
            break;

            case error.POSITION_UNAVAILABLE: alert("Imposible detectar la posicio actual");
            break;

            case error.TIMEOUT: alert("La posicion debe recuperar el tiempo de espera");
            break;

            default: alert("Error desconocido");
            break;
        }
    });
</script>
