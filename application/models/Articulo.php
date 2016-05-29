<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Articulo extends CI_Model{

  public function __construct() {
    parent::__construct();
  }
  // Operaciones chungas
  public function insertar($articulo) {
      $res = $this->db->query("insert into articulos(nombre, ".
                              "descripcion, usuario_id, precio) ".
                              "values(?, ?, ?, ?) ".
                              "returning id",
                              array(
                                  $articulo['nombre'],
                                  $articulo['descripcion'],
                                  $articulo['usuario_id'],
                                  $articulo['precio'],
                              ));
      return $res->row_array();
  }
  public function vender($venta) {
      $res = $this->db->query("insert into ventas(vendedor_id, comprador_id, ".
                              "articulo_id, fecha_venta) values(?, ?, ?, current_date) ".
                              "returning id",
                              array(
                                  $venta['vendedor_id'],
                                  $venta['comprador_id'],
                                  $venta['articulo_id'],
                              ));
      return $res->row_array();
  }

  public function borrar($articulo_id) {
      return $this->db->query("delete from articulos where id = ?",
                               array($articulo_id));
  }

  // Operaciones de lectura
  public function todos() {
      $res = $this->db->query("select * from v_articulos");
      return ($res->num_rows() > 0) ? $res->result_array() : array();
  }

  public function todos_sin_favorito($usuario_id) {
    //   $res = $this->db->get_where('v_favoritos',
    //                                array('usuario_favorito' => $usuario_id));
      $res = $this->db->query('select *
                                from v_articulos
                                group by id, articulo_id, nombre, descripcion,
                                         usuario_id, precio, nick, favorito,
                                         etiquetas
                                having id not in (select articulo_id from favoritos where usuario_id = ?)',
                                array($usuario_id));

    //   $res3 = array_merge($res->result_array(), $res2->result_array());
    //   return (count($res3) > 0) ? $res3 : array();
    return $res->result_array();
  }
  public function articulos_favoritos($usuario_id) {
      $res = $this->db->get_where('v_favoritos',
                                   array('usuario_favorito' => $usuario_id));
      return $res->result_array();
  }

  public function busqueda_articulo($categoria_id, $nombre, $usuario_id) {
      if($categoria_id <= 0) {
          $res = $this->todos_sin_favorito($usuario_id);
      } else {
          $res = $this->db->like('lower(nombre)', strtolower($nombre), 'match');
          $res1 = $this->db->get_where('v_favoritos',
                                        array(
                                            'usuario_favorito' => $usuario_id,
                                            'categoria_id' => $categoria_id,
                                            )
                                        );
          $res2 = $this->db->query('select *
                                    from v_articulos
                                    where categoria_id = ?
                                    group by id, articulo_id, nombre, descripcion, usuario_id, categoria_id, precio,
                                             nick, nombre_categoria, favorito
                                    having id not in (select articulo_id from favoritos where usuario_id = ?)',
                                    array($categoria_id, $usuario_id));

          $res = array_merge($res1->result_array(), $res2->result_array());
      }
      return (count($res) > 0) ? $res : array();
  }

  public function categorias() {
      $res = $this->db->get('categorias');
      return $res->result_array();
  }

  public function por_id($id_articulo) {
      $res = $this->db->query("select * from v_articulos_raw where id::text = ?", array($id_articulo));
      return ($res->num_rows() > 0) ? $res->row_array() : FALSE;
  }

  public function por_id_vista($id_usuario, $id_articulo) {
      $res = $this->db->query("select * from v_articulos where usuario_id = ? and id != ?",
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

  public function es_propietario($usuario_id, $articulo_id) {
      $res = $this->db->query("select * from articulos where id = ? and usuario_id = ?",
                               array($articulo_id, $usuario_id));
      return ($res->num_rows() > 0) ? TRUE : FALSE;
  }

  public function articulo_vendido($articulo_id) {
      $res = $this->db->query("select * from ventas where articulo_id = ?",
                               array($articulo_id));
      return ($res->num_rows() > 0) ? TRUE : FALSE;
  }
}
