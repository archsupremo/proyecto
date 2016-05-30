<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Articulo extends CI_Model{

  public function __construct() {
    parent::__construct();
  }
  // Operaciones chungas
  public function insertar($articulo) {
      $res = $this->db->query("insert into articulos(nombre, ".
                              "descripcion, usuario_id, fecha, precio) ".
                              "values(?, ?, ?, current_timestamp, ?) ".
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
  public function todos($min, $max, $fecha) {
      $res = $this->db->query("select * from v_articulos where fecha < ? offset ? limit ?",
                              array($fecha, $min, $max));
      return ($res->num_rows() > 0) ? $res->result_array() : array();
  }

  public function todos_sin_favorito($usuario_id, $min, $max, $fecha) {
      $res = $this->db->query('select *
                                from v_articulos
                                where fecha < ?
                                group by id, articulo_id, nombre, descripcion,
                                         usuario_id, precio, nick, favorito,
                                         etiquetas, fecha, latitud, longitud
                                having id not in (select articulo_id from favoritos where usuario_id = ?)
                                order by fecha desc
                                offset ? limit ?',
                                array($fecha, $usuario_id, $min, $max));

    return $res->result_array();
  }
  public function articulos_favoritos($usuario_id) {
      $res = $this->db->get_where('v_favoritos',
                                   array('usuario_favorito' => $usuario_id));
      return $res->result_array();
  }

  public function busqueda_articulo($etiquetas, $nombre) {
      $res = array();
      if( ! empty($etiquetas) && $etiquetas[0] !== "") {
          foreach ($etiquetas as $v) {
              $this->db->like('lower(etiquetas)', strtolower($v), 'match');
              $this->db->select('distinct on (articulo_id) *');
              $res1 = $this->db->get('v_etiquetas_articulos')->result_array();

              foreach ($res1 as $value) {
                  $res[$value['articulo_id']] = $value;
              }
          }
      }

      if($nombre !== "") {
          $this->db->like('lower(nombre)', strtolower($nombre), 'match');
          $res2 = $this->db->get('v_articulos')->result_array();

          foreach ($res2 as $value) {
              $res[$value['articulo_id']] = $value;
          }
      }
      if($nombre === "" && $etiquetas[0] === "") {
          if($this->Usuario->logueado()):
              $usuario = $this->session->userdata("usuario");
              $res = $this->Articulo->todos_sin_favorito($usuario['id'], 0, 10, 'now()');
          else:
              $res = $this->Articulo->todos(0, 10, 'now()');
          endif;
      }


      return $res;
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
