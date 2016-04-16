<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Articulo extends CI_Model{

  public function __construct() {
    parent::__construct();
  }

  public function todos() {
      $res = $this->db->query("select * from v_articulos_por_vender");
      return ($res->num_rows() > 0) ? $res->result_array() : array();
  }

  public function todos_con_favorito($usuario_id) {
      $res = $this->db->query("select id, nombre, descripcion, categoria_id, precio, nombre_categoria, a.usuario_id, nick, 1::boolean as favorito " .
                              "from v_articulos_por_vender a group by id, nombre, descripcion, categoria_id," .
                              "precio, nombre_categoria, usuario_id, nick " .
                              "having id in (select articulo_id from favoritos where usuario_id = ?)",
                              $usuario_id);
      $res2 = $this->db->query("select id, nombre, descripcion, categoria_id, precio, nombre_categoria, a.usuario_id, nick, 0::boolean as favorito " .
                              "from v_articulos_por_vender a group by id, nombre, descripcion, categoria_id," .
                              "precio, nombre_categoria, usuario_id, nick " .
                              "having not id in (select articulo_id from favoritos where usuario_id = ?)",
                              $usuario_id);

      $res3 = array_merge($res->result_array(), $res2->result_array());
      return (count($res3) > 0) ? $res3 : array();
  }

  public function busqueda_articulo($categoria_id, $nombre) {
      $res = $this->db->like('lower(nombre)', strtolower($nombre), 'match');
      if($categoria_id <= 0) {
          $res = $this->db->get('v_articulos_por_vender');
      } else {
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

  public function existe_favorito($usuario_id, $articulo_id) {
      $res = $this->db->query("select * from favoritos where usuario_id = ? and articulo_id = ?",
                               array($usuario_id, $articulo_id));

      return ($res->num_rows() > 0) ? TRUE : FALSE;
  }

  public function insertar_favorito($usuario_id, $articulo_id) {
      return $this->db->query("insert into favoritos(usuario_id, articulo_id) values(?, ?)",
                       array($usuario_id, $articulo_id));
  }

  public function borrar_favorito($usuario_id, $articulo_id) {
      return $this->db->query("delete from favoritos where usuario_id = ? and articulo_id = ?",
                       array($usuario_id, $articulo_id));
  }
}
