<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Portada extends CI_Controller {

  public function __construct() {
    parent::__construct();
  }

  public function index($kyw = NULL, $e = NULL) {
      if($this->input->post('buscar') !== NULL) {
          $etiquetas = preg_split('/,/', $this->input->post('tags'));
          //$etiquetas = $this->input->post('tags');
          $nombre = $this->input->post("nombre");
          $data['articulos'] = $this->Articulo->busqueda_articulo($etiquetas, $nombre);

      } else {
          if($this->Usuario->logueado()):
              $usuario = $this->session->userdata("usuario");
              $data['articulos'] =
                  $this->Articulo->todos_sin_favorito($usuario['id'], 0, 10, "now()");
          else:
              $data['articulos'] = $this->Articulo->todos(0, 10, "now()");
          endif;
      }
      $this->template->load('/frontend/index', $data);
  }
}
