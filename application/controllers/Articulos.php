<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Articulos extends CI_Controller {

  public function __construct() {
    parent::__construct();
    //Codeigniter : Write Less Do More
  }

  public function index() {

  }

  public function buscar($id_articulo = NULL) {
      if($id_articulo === NULL || $this->Articulo->por_id($id_articulo) === FALSE) {
          $mensajes[] = array('error' =>
              "Parametros incorrectos para visualizar el articulo en vista detallada.");
          $this->flashdata->load($mensajes);

          redirect('/frontend/portada/');
      }

      $data['articulo'] = $this->Articulo->por_id($id_articulo);
      $id_usuario = $data['articulo']['usuario_id'];

      $data['usuario'] = $this->Usuario->por_id($id_usuario);
      $data['articulos_usuarios'] = $this->Articulo->por_id_vista($id_usuario, $id_articulo);

      $this->template->load("/articulos/buscar", $data);
  }
}
