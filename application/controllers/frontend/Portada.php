<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Portada extends CI_Controller {

  public function __construct() {
    parent::__construct();
  }

  function index() {
      $usuario = $this->session->userdata("usuario");
      if($this->input->post('buscar') !== NULL) {
          $categoria_id = $this->input->post('categoria');
          $nombre = $this->input->post("nombre");
          $data['articulos'] = $this->Articulo->busqueda_articulo($categoria_id, $nombre, $usuario['id']);
        //   var_dump($data['articulos']); die();
      }else {
          if($this->Usuario->logueado()):
              $data['articulos'] = $this->Articulo->todos_sin_favorito($usuario['id']);
          else:
              $data['articulos'] = $this->Articulo->todos();
          endif;
      }
      $this->template->load('/frontend/index', $data);
  }
}
