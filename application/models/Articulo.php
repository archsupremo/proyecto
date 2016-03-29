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

}
