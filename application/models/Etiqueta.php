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

  public function insertar($nombre) {
      $res = $this->db->query("insert into etiquetas(nombre) values(?)".
                              "returning id",
                              array($nombre));
      return $res->row_array();
  }

  public function existe_etiqueta($nombre) {
      $res = $this->db->query("select * from etiquetas where lower(nombre) = ?",
                              array(
                                  strtolower($nombre)
                              ));
      return ($res->num_rows() > 0) ? $res->row_array() : FALSE;
  }

  public function insertar_etiqueta_articulo($valores) {
      return $this->db->insert("etiquetas_articulos", $valores);
  }
}
