<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Venta extends CI_Model{

  public function __construct() {
    parent::__construct();
    //Codeigniter : Write Less Do More
  }

  public function por_id($id) {
      $res = $this->db->query("select * from v_ventas where venta_id::text = ?", array($id));
      return $res->num_rows() > 0 ? $res->row_array() : FALSE;
  }

  public function es_comprador($comprador_id, $venta_id) {
      $res = $this->db->query("select * from v_ventas_comprador where comprador_id::text = ? ".
                              "and venta_id::text = ?",
                              array($comprador_id, $venta_id));
      return $res->num_rows() > 0 ? TRUE : FALSE;
  }

  public function es_vendedor($vendedor_id, $venta_id) {
      $res = $this->db->query("select * from v_ventas_vendedor where vendedor_id::text = ? ".
                              "and venta_id::text = ?",
                              array($vendedor_id, $venta_id));
      return $res->num_rows() > 0 ? TRUE : FALSE;
  }

  public function borrar_compra($venta_id, $valores) {
      $query = "update ventas set comprador_id = null where id::text = ?";
      return $this->db->query($query, array($venta_id));
  }
}
