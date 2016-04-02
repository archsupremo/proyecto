<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Portada extends CI_Controller {

  public function __construct() {
    parent::__construct();
  }

  function index() {
      if($this->input->post('buscar') !== NULL) {
          $categoria_id = $this->input->post('categoria');
          $nombre = $this->input->post("nombre");
          $data['articulos'] = $this->Articulo->busqueda_articulo($categoria_id, $nombre);
        //   var_dump($data['articulos']); die();
      }else {
          $data['articulos'] = $this->Articulo->todos();
      }
      $this->template->load('frontend/index', $data);
  }
}
