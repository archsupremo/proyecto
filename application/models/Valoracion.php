<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Valoracion extends CI_Model{

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
  }

  public function insertar_valoracion_vendedor($valoracion) {
      return $this->db->insert('valoraciones_vendedor', $valoracion);
  }

  public function insertar_valoracion_comprador($valoracion) {
      return $this->db->insert('valoraciones_comprador', $valoracion);
  }
}
