<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function usuario_logueado() {
    $CI =& get_instance();

    $out = "";

    if ($CI->Usuario->logueado()):
        $usuario = $CI->session->userdata('usuario');
        $out .= '<li>'.
                 anchor('/usuarios/editar_perfil/' . $usuario['id'], 'Editar información personal',
                        'id="editar_perfil" class=""');
                '</li>';
        $out .= '<li>'.
                    anchor('/articulos/subir/', 'Subir articulo',
                           'id="subir_articulo" class=""');
                 '</li>';
        $out .= '<li>'.
                    anchor('/usuarios/perfil/' . $usuario['id'], 'Ver perfil',
                           'id="perfil" class=""');
                 '</li>';
        $out .= '<li>'.
                 anchor('/usuarios/logout/', 'Logout',
                        'id="logout" class=""');
                '</li>';
    endif;

    return $out;
}

function login() {
    $CI =& get_instance();

    $out = "";

    if (!$CI->Usuario->logueado()):
        $out .= '<li>'.
                 anchor('/usuarios/login/', 'Iniciar sesión',
                        'id="logout" class=""');
                '</li>';
    endif;

    return $out;
}

function registro() {
    $CI =& get_instance();

    $out = "";

    if (!$CI->Usuario->logueado()):
        $out .= '<li>'.
                 anchor('/usuarios/registrar/', 'Registro',
                        'id="logout" class=""');
                '</li>';
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
