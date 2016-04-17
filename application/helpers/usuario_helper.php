<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function login() {
    $CI =& get_instance();

    $out = "";

    if ($CI->Usuario->logueado()):
        $usuario = $CI->session->userdata('usuario');
        $out .= form_open('usuarios/logout', 'class="form-inline"');
            $out .= '<div class="form-group">';
                $out .= form_submit('logout', 'Logout',
                                    'id="logout" class="button small round right" role="button"');
                $out .= anchor('/usuarios/perfil/' . $usuario['id'], 'Ver perfil',
                            'id="perfil" class="button small round right" role="button"');
                $out .= anchor('/usuarios/editar_perfil/' . $usuario['id'], 'Editar perfil',
                            'id="editar_perfil" class="button small round right" role="button"');
            $out .= '</div>';
        $out .= form_close();
    else:
        $out .= '<div class="large-3 columns">';
            $out .= anchor('/usuarios/login/', 'Iniciar sesión',
                            'class="button small round right" role="button"');
        $out .= '</div>';
    endif;

    return $out;
}

function registro() {
    $CI =& get_instance();

    $out = "";

    if (!$CI->Usuario->logueado()):
        $out .= '<div class="large-1 columns">';
            $out .= anchor('/usuarios/registrar/', 'Registro',
                            'class="button small round right" role="button"');
        $out .= '</div>';
    endif;

    return $out;
}

function usuario_id() {
        $CI =& get_instance();
        return $CI->session->userdata('usuario')['id'];
}

function logueado() {
    $CI =& get_instance();
    return $CI->Usuario->logueado();
}

function nick() {
    $CI =& get_instance();
    if($CI->Usuario->logueado()) {
        $usuario = $CI->session->userdata("usuario");
        $usuario =  $CI->Usuario->por_id($usuario['id']);
        if ($usuario !== FALSE) {
            return $usuario['nick'];
        }
    }
}

function dar_usuario() {
    $CI =& get_instance();

    return $CI->session->userdata('usuario');
}
