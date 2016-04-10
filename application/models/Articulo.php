<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Articulo extends CI_Model{

  public function __construct() {
    parent::__construct();
  }

  public function todos() {
      $res = $this->db->query("select * from v_articulos_por_vender");
      return $res->result_array();
  }

  public function busqueda_articulo($categoria_id, $nombre) {
      $res = $this->db->like('lower(nombre)', strtolower($nombre), 'match');
      if($categoria_id <= 0) {
          $res = $this->db->get('v_articulos_por_vender');
      }else {
          $res = $this->db->get_where('v_articulos_por_vender', array('categoria_id' => $categoria_id));
      }
      return $res->result_array();
  }

  public function categorias() {
      $res = $this->db->get('categorias');
      return $res->result_array();
  }

  public function por_id($id_articulo) {
      $res = $this->db->query("select * from v_articulos where id::text = ?", array($id_articulo));
      return ($res->num_rows() > 0) ? $res->row_array() : FALSE;
  }

  public function por_id_vista($id_usuario, $id_articulo) {
      $res = $this->db->query("select * from v_articulos_por_vender where usuario_id = ? and id != ?",
                              array($id_usuario, $id_articulo));
      return $res->num_rows() > 0 ? $res->result_array() : array();
  }
}
