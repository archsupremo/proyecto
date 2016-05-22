<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Venta extends CI_Model{

  public function __construct() {
    parent::__construct();
    //Codeigniter : Write Less Do More
  }

  public function por_id($id) {
      $res = $this->db->get_where('v_ventas', array('venta_id' => $id));
      return $res->num_rows() > 0 ? $res->row_array() : FALSE;
  }

  public function es_comprador($comprador_id, $venta_id) {
      $res = $this->db->query("select * from v_ventas_comprador where comprador_id = ? ".
                              "and venta_id = ?",
                              array($comprador_id, $venta_id));
      return $res->num_rows() > 0 ? TRUE : FALSE;
  }

  public function es_vendedor($vendedor_id, $venta_id) {
      $res = $this->db->query("select * from v_ventas_vendedor where vendedor_id = ? ".
                              "and venta_id = ?",
                              array($vendedor_id, $venta_id));
      return $res->num_rows() > 0 ? TRUE : FALSE;
  }

  public function borrar_compra($venta_id, $valores) {
      $res = $this->db->where('id', $venta_id)->update('ventas', $valores);
  }
}
