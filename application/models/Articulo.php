<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Articulo extends CI_Model{

  public function __construct() {
    parent::__construct();
  }

  public function todos() {
      $res = $this->db->query("select * from v_articulos");
      return $res->result_array();
  }

  public function busqueda_articulo($categoria_id, $nombre) {
      $res = $this->db->like('lower(nombre)', strtolower($nombre), 'match');
      if($categoria_id <= 0) {
          $res = $this->db->get('v_articulos');
      }else {
          $res = $this->db->get_where('v_articulos', array('categoria_id' => $categoria_id));
      }
      return $res->result_array();
  }

  public function categorias() {
      $res = $this->db->get('categorias');
      return $res->result_array();
  }
}
