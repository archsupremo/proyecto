<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Portada extends CI_Controller {

  public function __construct() {
    parent::__construct();
  }

  public function index() {
      $limit = 10;
      $distancia = 0;
      $latitud = 40.4168;
      $longitud = -3.7038;
      $precio = '';

      if($this->input->get('nombre') !== NULL && $this->input->get('tags') !== NULL) {
          if($this->input->get('order_distancia') !== NULL) {
              $distancia = $this->input->get('order_distancia');

              if( ! is_numeric($distancia) || $distancia < 0) {
                  $distancia = 0;
              } else if ($this->input->get('latitud') !== NULL &&
                         $this->input->get('longitud') !== NULL) {

                  $latitud = $this->input->get('latitud');
                  $longitud = $this->input->get('longitud');

                  if( ! is_numeric($latitud)) {
                     $latitud = 40.4168;
                  }

                  if( ! is_numeric($longitud)) {
                     $longitud = -3.7038;
                  }
              }
          }

          if($this->input->get('order_precio') !== NULL) {
              $precio = $this->input->get('order_precio');

              switch ($precio) {
                  case 'asc':
                      break;
                  case 'desc':
                      break;
                  default:
                        $precio = '';
                      break;
              }
          }

          $etiquetas = preg_split('/,/', $this->input->get('tags'));
          if($etiquetas[0] === '') {
              $etiquetas = array();
          }
          $nombre = $this->input->get("nombre");
          $data['articulos'] =
               $this->Articulo->busqueda_articulo($limit, $etiquetas,
                                                  $nombre,
                                                  $precio,
                                                  $distancia,
                                                  $latitud,
                                                  $longitud,
                                                  array());
      } else {
          if($this->Usuario->logueado()):
              $usuario = $this->session->userdata("usuario");
              $data['articulos'] =
                  $this->Articulo->todos_sin_favorito($usuario['id'], $limit,
                                                      "now()", $precio,
                                                      $distancia, $latitud,
                                                      $longitud, array());
          else:
              $data['articulos'] = $this->Articulo->todos($limit, "now()",
                                                          $precio, $distancia,
                                                          $latitud, $longitud,
                                                          array());
          endif;
      }
      $this->template->load('/frontend/index', $data);
  }
}
