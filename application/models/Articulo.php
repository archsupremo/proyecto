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
  public function todos($limit, $fecha, $precio, $distancia,
                        $latitud, $longitud, $articulos_viejos) {
      $query = "select * from v_articulos where fecha < ? ";
      $datos = array($fecha);

      if($distancia > 0) {
          $query .= ' and latitud is not null and longitud is not null'.
                    ' and earth_distance(ll_to_earth(?, ?),'.
                    ' ll_to_earth(latitud, longitud)) < ? ';
          array_push($datos, $latitud);
          array_push($datos, $longitud);
          array_push($datos, $distancia);
      }
      if( ! empty($articulos_viejos)) {
          $cadena = "(";
          foreach ($articulos_viejos as $v) {
              $cadena .= $v . ', ';
          }
          $cadena = substr($cadena, 0, -2) . ")";
          $query .= ' and articulo_id not in ' . $cadena;
      }
      if($precio !== '') {
          if(end($articulos_viejos) !== FALSE) {
              $ultimo_articulo = $this->por_id(end($articulos_viejos));
              if($precio === 'desc') {
                  $query .= ' and precio <= \'' . $ultimo_articulo['precio'] . '\'::money';
              } else {
                  $query .= ' and precio >= \'' . $ultimo_articulo['precio'] . '\'::money';
              }
          }
          $query .= " order by precio " . $precio;
      }
      $query .= " limit ?";
      array_push($datos, $limit);

      $res = $this->db->query($query, $datos);
      return ($res->num_rows() > 0) ? $res->result_array() : array();
  }

  public function todos_sin_favorito($usuario_id, $limit, $fecha,
                                     $precio, $distancia, $latitud, $longitud,
                                     $articulos_viejos) {
      $datos = array($fecha);
      $query = 'select *
                from v_articulos
                where fecha < ?';
      if( ! empty($articulos_viejos)) {
          $cadena = "(";
          foreach ($articulos_viejos as $v) {
              $cadena .= $v . ', ';
          }
          $cadena = substr($cadena, 0, -2) . ")";
          $query .= ' and articulo_id not in ' . $cadena;
      }
      if($distancia > 0) {
          $query .= ' and latitud is not null and longitud is not null'.
                    ' and earth_distance(ll_to_earth(?, ?),'.
                    ' ll_to_earth(latitud, longitud)) < ?';
          array_push($datos, $latitud);
          array_push($datos, $longitud);
          array_push($datos, $distancia);
      }
      if($precio !== '' && end($articulos_viejos) !== FALSE) {
          $ultimo_articulo = $this->por_id(end($articulos_viejos));
          if($precio === 'desc') {
              $query .= ' and precio <= \'' . $ultimo_articulo['precio'] . '\'::money';
          } else {
              $query .= ' and precio >= \'' . $ultimo_articulo['precio'] . '\'::money';
          }
      }
      $query .= ' group by id, articulo_id, nombre, descripcion,
                  usuario_id, precio, nick, favorito,
                  etiquetas, fecha, latitud, longitud ';
      $query .= ' having id not in (select articulo_id from favoritos where usuario_id = ?) ';
      array_push($datos, $usuario_id);

      if($precio !== '') {
          $query .= " order by precio " . $precio;
      } else {
          $query .= ' order by fecha desc ';
      }
      $query .= ' limit ? ';
      array_push($datos, $limit);

      $res = $this->db->query($query, $datos);

      return $res->result_array();
  }
  public function articulos_favoritos($usuario_id) {
      $res = $this->db->get_where('v_favoritos',
                                   array('usuario_favorito' => $usuario_id));
      return $res->result_array();
  }

  public function busqueda_articulo($limit, $etiquetas, $nombre, $precio, $distancia,
                                    $latitud, $longitud, $articulos_viejos) {
      $res = array();
      if( ! empty($etiquetas)) {
          foreach ($etiquetas as $v) {
              $this->db->like('lower(etiquetas)', strtolower($v), 'match');
              if( ! empty($articulos_viejos)) {
                  $this->db->where_not_in('articulo_id', $articulos_viejos);
              }

              if($precio !== '') {
                  $this->db->order_by('precio', $precio);
                  $this->db->select('distinct on (articulo_id, precio) *');
              } else {
                  $this->db->select('distinct on (articulo_id) *');
              }
              if($distancia > 0) {
                  $this->db->where('latitud is not null and longitud is not null');
                  $this->db->where('earth_distance(ll_to_earth('.
                                    $latitud.', '.$longitud.
                                    '), ll_to_earth(latitud, longitud)) < ',
                                    $distancia);
              }
              $this->db->limit($limit);
              $res = $this->db->get('v_articulos')->result_array();
          }
      }

      if($nombre !== "") {
          $this->db->like('lower(nombre)', strtolower($nombre), 'match');

          if( ! empty($articulos_viejos)) {
              $this->db->where_not_in('articulo_id', $articulos_viejos);
          }
          if($precio !== '') {
              $this->db->order_by('precio', $precio);
              $this->db->select('distinct on (articulo_id, precio) *');
          } else {
              $this->db->select('distinct on (articulo_id) *');
          }

          if($distancia > 0) {
              $this->db->where('latitud is not null and longitud is not null');
              $this->db->where('earth_distance(ll_to_earth('.
                                $latitud.', '.$longitud.
                                '), ll_to_earth(latitud, longitud)) < ',
                                $distancia);
          }
          $this->db->limit($limit);
          $res = $this->db->get('v_articulos')->result_array();
      }

      if( ! empty($etiquetas) && $nombre !== "" ) {
          foreach ($etiquetas as $v) {
              $this->db->like('lower(etiquetas)', strtolower($v), 'match');
              $this->db->like('lower(nombre)', strtolower($nombre), 'match');
              if( ! empty($articulos_viejos)) {
                  $this->db->where_not_in('articulo_id', $articulos_viejos);
              }
              if($precio !== '') {
                  $this->db->order_by('precio', $precio);
                  $this->db->select('distinct on (articulo_id, precio) *');
              } else {
                  $this->db->select('distinct on (articulo_id) *');
              }
              if($distancia > 0) {
                  $this->db->where('latitud is not null and longitud is not null');
                  $this->db->where('earth_distance(ll_to_earth('.
                                    $latitud.', '.$longitud.
                                    '), ll_to_earth(latitud, longitud)) < ',
                                    $distancia);
              }
              $this->db->limit($limit);
              $res = $this->db->get('v_articulos')->result_array();
          }
      }
      if($nombre === "" && empty($etiquetas)) {
          if($this->Usuario->logueado()):
              $usuario = $this->session->userdata("usuario");
              $res =
                $this->Articulo->todos_sin_favorito($usuario['id'], $limit, 'now()',
                                                    $precio, $distancia,
                                                    $latitud, $longitud,
                                                    $articulos_viejos);
          else:
              $res = $this->Articulo->todos($limit, 'now()',
                                            $precio, $distancia,
                                            $latitud, $longitud,
                                            $articulos_viejos);
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
