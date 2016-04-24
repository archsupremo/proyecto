<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function mensajes() {
    $CI =& get_instance();
    $mensajes = $CI->session->flashdata('mensajes');

    $out = "";

    if($mensajes !== NULL) {
        foreach ($mensajes as $mensaje) {
            foreach ($mensaje as $clave => $valor) break;
            $clase = ($clave === 'error') ? 'alert-box alert radius' : 'alert-box success radius';

            $out .= '<div class="row centered text-center">';
                $out .= '<div data-alert class="large-4 large-offset-4 ' . $clase . ' flashdata">';
                    $out .= $valor;
                    $out .= "<a href=\"#\" class=\"close\">&times;</a>";
                $out .= '</div>';
            $out .= '</div>';
        }
    }

    return $out;
}


//// Funciones de categorias y busqueda del template.php
function busqueda() {
    $CI =& get_instance();

    return $CI->input->post('nombre');
}

function busqueda_select() {
    $CI =& get_instance();

    $categorias_raw = $CI->Articulo->categorias();
    $categorias = array(0 => 'Todo');
    foreach ($categorias_raw as $categoria) {
        $categorias[$categoria['id']] = $categoria['nombre'];
    }
    return $categorias;
}

function selected($categoria_id) {
    $CI =& get_instance();

    if ($CI->input->post('categoria') === $categoria_id) {
        return 'selected="on"';
    }
}
