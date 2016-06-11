<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function usuario_logueado() {
    $CI =& get_instance();

    $out = "";

    if ($CI->Usuario->logueado()):
        $usuario = $CI->session->userdata('usuario');
        if($usuario['admin'] === FALSE) {
            $out .= '<li>'.
                        anchor('/pdf/', 'Generar pdf con información del usuario',
                               'id="pdf" class=""');
                    '</li>';
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
        } else {
            $out .= '<li>'.
                        anchor('/backend/usuarios/listado_usuarios/', 'Ver tabla con listado de usuarios',
                               'id="listado_usuarios" class=""');
                    '</li>';
            // $out .= '<li>'.
            //          anchor('/usuarios/editar_perfil/' . $usuario['id'], 'Editar información personal',
            //                 'id="editar_perfil" class=""');
            //         '</li>';
            // $out .= '<li>'.
            //             anchor('/articulos/subir/', 'Subir articulo',
            //                    'id="subir_articulo" class=""');
            //          '</li>';
            // $out .= '<li>'.
            //             anchor('/usuarios/perfil/' . $usuario['id'], 'Ver perfil',
            //                    'id="perfil" class=""');
            //          '</li>';
            $out .= '<li>'.
                     anchor('/usuarios/logout/', 'Logout',
                            'id="logout" class=""');
                    '</li>';
        }
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
        if($usuario['admin'] === TRUE) {
            $usuario = $CI->Usuario->por_id_admin($usuario['id']);
        } else {
            $usuario = $CI->Usuario->por_id($usuario['id']);
        }
        if ($usuario !== FALSE) {
            return $usuario['nick'];
        }
    }
}

function dar_usuario() {
    $CI =& get_instance();
    return $CI->session->userdata('usuario');
}

function es_admin() {
    $CI =& get_instance();
    if(logueado()) {
        return $CI->session->userdata('usuario')['admin'];
    }
    return FALSE;
}
