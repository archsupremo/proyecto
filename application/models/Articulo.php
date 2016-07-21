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
  public function editar($valores, $articulo_id) {
      return $this->db->where('id', $articulo_id)->update('articulos', $valores);
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
      return $this->db->query("delete from articulos where id::text = ?",
                               array($articulo_id));
  }

  public function retirar_articulo($articulo_id, $usuario_id) {
      return $this->db->insert('articulos_retirados',
                               array(
                                   'articulo_id' => $articulo_id,
                                   'usuario_id' => $usuario_id
                               ));
  }

  // Operaciones de lectura
  public function todos($limit, $fecha, $order, $distancia,
                        $latitud, $longitud, $articulos_viejos) {

      $query = "select *, earth_distance(ll_to_earth(?, ?), ll_to_earth(latitud, longitud))
                as distancia from v_articulos where fecha < ? ";
      $datos = array($latitud, $longitud, $fecha);

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
      if($order !== '') {
          if(end($articulos_viejos) !== FALSE) {
              $ultimo_articulo = $this->por_id(end($articulos_viejos));
              if($order === 'precio_desc') {
                  $query .= ' and precio <= \'' . $ultimo_articulo['precio'] . '\'::money';
              } else if($order === 'precio_asc') {
                  $query .= ' and precio >= \'' . $ultimo_articulo['precio'] . '\'::money';
              }
          }
          if($order === 'precio_desc') {
              $query .= " order by precio desc";
          } else if($order === 'precio_asc') {
              $query .= " order by precio asc";
          } else if($order === 'prox') {
              $query .= " order by distancia asc";
          }
      }
      $query .= " limit ?";
      array_push($datos, $limit);
      echo "zxczxcz";
      $res = $this->db->query($query, $datos);
      echo "asdasd";
      var_dump($res);
      die();

      return ($res->num_rows() > 0) ? $res->result_array() : array();
  }

  public function todos_sin_favorito($usuario_id, $limit, $fecha,
                                     $order, $distancia, $latitud, $longitud,
                                     $articulos_viejos) {

      $query = "select *, earth_distance(ll_to_earth(?, ?), ll_to_earth(latitud, longitud))
                as distancia from v_articulos where fecha < ? ";
      $datos = array($latitud, $longitud, $fecha);
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
      if($order !== '' && end($articulos_viejos) !== FALSE) {
          $ultimo_articulo = $this->por_id(end($articulos_viejos));
          if($order === 'precio_desc') {
              $query .= ' and precio <= \'' . $ultimo_articulo['precio'] . '\'::money';
          } else if($order === 'precio_asc') {
              $query .= ' and precio >= \'' . $ultimo_articulo['precio'] . '\'::money';
          }
      }
      $query .= ' group by id, articulo_id, nombre, descripcion,
                  usuario_id, precio, nick, favorito,
                  etiquetas, fecha, latitud, longitud ';
      $query .= ' having id not in (select articulo_id from favoritos where usuario_id = ?) ';
      array_push($datos, $usuario_id);

      if($order !== '') {
          if($order === 'precio_desc') {
              $query .= " order by precio desc";
          } else if($order === 'precio_asc') {
              $query .= " order by precio asc";
          } else if($order === 'prox') {
              $query .= " order by distancia asc";
          }
      } else {
          $query .= ' order by fecha desc ';
      }
      $query .= ' limit ? ';
      array_push($datos, $limit);

      $res = $this->db->query($query, $datos);

      return $res->result_array();
  }
  public function articulos_favoritos($usuario_id) {
      $res = $this->db->query("select * from v_favoritos where usuario_favorito::text = ?", array($usuario_id));
      return $res->result_array();
  }

  public function busqueda_articulo($limit, $etiquetas, $nombre, $order, $distancia,
                                    $latitud, $longitud, $articulos_viejos) {
      $res = array();

      if( ! empty($etiquetas)) {
          foreach ($etiquetas as $v) {
              $this->db->like('lower(etiquetas)', strtolower($v), 'match');
              if( ! empty($articulos_viejos)) {
                  $this->db->where_not_in('articulo_id', $articulos_viejos);
              }
          }

          if($order !== '') {
              switch ($order) {
                  case 'precio_asc':
                      $this->db->order_by('precio', 'asc');
                      $this->db->select('distinct on (articulo_id, precio) *');
                      break;
                  case 'precio_desc':
                      $this->db->order_by('precio', 'desc');
                      $this->db->select('distinct on (articulo_id, precio) *');
                      break;
                  case 'prox':
                      $this->db->order_by('distancia', 'asc');
                      $this->db->select('distinct on (articulo_id, distancia) *');
                      break;
                  default:
                      break;
              }
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
          $this->db->select('earth_distance(ll_to_earth('.
                            $latitud.', '.$longitud.
                            '), ll_to_earth(latitud, longitud)) as distancia');
          $res = $this->db->get('v_articulos')->result_array();
      }

      if($nombre !== "") {
          $this->db->like('lower(nombre)', strtolower($nombre), 'match');

          if( ! empty($articulos_viejos)) {
              $this->db->where_not_in('articulo_id', $articulos_viejos);
          }
          if($order !== '') {
              switch ($order) {
                  case 'precio_asc':
                      $this->db->order_by('precio', 'asc');
                      $this->db->select('distinct on (articulo_id, precio) *');
                      break;
                  case 'precio_desc':
                      $this->db->order_by('precio', 'desc');
                      $this->db->select('distinct on (articulo_id, precio) *');
                      break;
                  case 'prox':
                      $this->db->order_by('distancia', 'asc');
                      $this->db->select('distinct on (articulo_id, distancia) *');
                      break;
                  default:
                      break;
              }
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
          $this->db->select('earth_distance(ll_to_earth('.
                            $latitud.', '.$longitud.
                            '), ll_to_earth(latitud, longitud)) as distancia');
          $res = $this->db->get('v_articulos')->result_array();
      }

      if( ! empty($etiquetas) && $nombre !== "" ) {
          foreach ($etiquetas as $v) {
              $this->db->like('lower(etiquetas)', strtolower($v), 'match');
              $this->db->like('lower(nombre)', strtolower($nombre), 'match');
              if( ! empty($articulos_viejos)) {
                  $this->db->where_not_in('articulo_id', $articulos_viejos);
              }
          }
          if($order !== '') {
              switch ($order) {
                  case 'precio_asc':
                      $this->db->order_by('precio', 'asc');
                      $this->db->select('distinct on (articulo_id, precio) *');
                      break;
                  case 'precio_desc':
                      $this->db->order_by('precio', 'desc');
                      $this->db->select('distinct on (articulo_id, precio) *');
                      break;
                  case 'prox':
                      $this->db->order_by('distancia', 'asc');
                      $this->db->select('distinct on (articulo_id, distancia) *');
                      break;
                  default:
                      break;
              }
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
          $this->db->select('earth_distance(ll_to_earth('.
                            $latitud.', '.$longitud.
                            '), ll_to_earth(latitud, longitud)) as distancia');
          $res = $this->db->get('v_articulos')->result_array();
      }
      if($nombre === "" && empty($etiquetas)) {
          if($this->Usuario->logueado()):
              $usuario = $this->session->userdata("usuario");
              $res =
                $this->Articulo->todos_sin_favorito($usuario['id'], $limit, 'now()',
                                                    $order, $distancia,
                                                    $latitud, $longitud,
                                                    $articulos_viejos);
          else:
              $res = $this->Articulo->todos($limit, 'now()',
                                            $order, $distancia,
                                            $latitud, $longitud,
                                            $articulos_viejos);
          endif;
      }

      return $res;
  }

  public function por_id_editar($usuario_id, $articulo_id) {
      $res = $this->db->query("select * from v_articulos where articulo_id::text = ? and usuario_id::text = ?",
                              array($articulo_id, $usuario_id));
      return ($res->num_rows() > 0) ? $res->row_array() : FALSE;
  }

  public function por_id($id_articulo) {
      $res = $this->db->query("select * from v_articulos_raw where id::text = ?", array($id_articulo));
      return ($res->num_rows() > 0) ? $res->row_array() : FALSE;
  }

  public function por_id_vista($id_usuario, $id_articulo) {
      $res = $this->db->query("select * from v_articulos where usuario_id::text = ? and articulo_id::text != ?",
                              array($id_usuario, $id_articulo));
      return $res->num_rows() > 0 ? $res->result_array() : array();
  }

  public function existe_favorito($usuario_id, $articulo_id) {
      $res = $this->db->query("select * from favoritos where usuario_id::text = ? and articulo_id::text = ?",
                               array($usuario_id, $articulo_id));

      return ($res->num_rows() > 0) ? TRUE : FALSE;
  }

  public function insertar_favorito($usuario_id, $articulo_id) {
      return $this->db->query("insert into favoritos(usuario_id, articulo_id) values(?, ?)",
                       array($usuario_id, $articulo_id));
  }

  public function borrar_favorito($usuario_id, $articulo_id) {
      return $this->db->query("delete from favoritos where usuario_id::text = ? and articulo_id::text = ?",
                       array($usuario_id, $articulo_id));
  }

  public function es_propietario($usuario_id, $articulo_id) {
      $res = $this->db->query("select * from articulos where id::text = ? and usuario_id::text = ?",
                               array($articulo_id, $usuario_id));
      return ($res->num_rows() > 0) ? TRUE : FALSE;
  }

  public function articulo_vendido($articulo_id) {
      $res = $this->db->query("select * from ventas where articulo_id::text = ?",
                               array($articulo_id));
      return ($res->num_rows() > 0) ? TRUE : FALSE;
  }

  public function articulo_favorito_email($articulo_id) {
      $res = $this->db->query("select * from v_favoritos_email where articulo_id::text = ?",
                               array($articulo_id));
      return ($res->num_rows() > 0) ? $res->result_array() : FALSE;
  }
}
