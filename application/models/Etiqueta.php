<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Etiqueta extends CI_Model{

  public function __construct() {
    parent::__construct();
  }

  public function buscar($palabras) {
      $this->db->distinct();
      $this->db->like('lower(nombre)', strtolower($palabras), 'match');
      $res = $this->db->select('nombre')->get('etiquetas');
      return $res->result_array();
  }
}
