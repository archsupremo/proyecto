<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Articulos extends CI_Controller {

  public function __construct() {
    parent::__construct();
    //Codeigniter : Write Less Do More
  }

  public function index() {

  }

  public function buscar($id_articulo = NULL) {
      if($id_articulo === NULL || $this->Articulo->por_id($id_articulo) === FALSE) {
          $mensajes[] = array('error' =>
              "Parametros incorrectos para visualizar el articulo en vista detallada.");
          $this->flashdata->load($mensajes);

          redirect('/frontend/portada/');
      }

      $data['articulo'] = $this->Articulo->por_id($id_articulo);
      $id_usuario = $data['articulo']['usuario_id'];

      $data['usuario'] = $this->Usuario->por_id($id_usuario);
      $data['articulos_usuarios'] = $this->Articulo->por_id_vista($id_usuario, $id_articulo);

      $this->template->load("/articulos/buscar", $data);
  }

  public function favoritos($articulo_id) {
      if($articulo_id === NULL || $this->Articulo->por_id($articulo_id) === FALSE) {
          $mensajes[] = array('error' =>
              "Parametros incorrectos para añadir a favoritos el articulo.");
          $this->flashdata->load($mensajes);

          redirect('/frontend/portada/');
      }
      $usuario = $this->session->userdata('usuario');

      if($this->Articulo->existe_favorito($usuario['id'], $articulo_id)) {
          $this->Articulo->borrar_favorito($usuario['id'], $articulo_id);
      } else {
          $this->Articulo->insertar_favorito($usuario['id'], $articulo_id);
      }
  }

  public function subir() {
      if (!$this->Usuario->logueado()) {
          $mensajes[] = array('error' =>
                  "No puedes insertar articulos si no estas logueado.");
          $this->flashdata->load($mensajes);
          redirect('/frontend/portada/');
      }

      if ($this->input->post('subir') !== NULL) {
          $articulo = $this->input->post();
          $articulo['precio'] = (double) $articulo['precio'];
          unset($articulo['subir']);

          $articulo['usuario_id'] = $this->session->userdata('usuario')['id'];
          $articulo_insertado = $this->Articulo->insertar($articulo);
          $articulo_id = $articulo_insertado['id'];

          $sesion = $this->session->userdata('usuario');
          $sesion['ultimo_articulo'] = $articulo_id;
          $this->session->set_userdata('usuario', $sesion);

        //   $mensajes[] = array('info' =>
        //           "Articulo insertado correctamente :)");
        //   $this->flashdata->load($mensajes);
          redirect('/articulos/subir_imagenes');
      }

      $categorias_raw = $this->Articulo->categorias();
      $categorias = array();
      foreach ($categorias_raw as $categoria) {
          $categorias[$categoria['id']] = $categoria['nombre'];
      }
      $data['categorias'] = $categorias;
      $this->template->load('/articulos/subir', $data);
  }

  public function subir_imagenes() {
      if (!$this->Usuario->logueado()) {
          $mensajes[] = array('error' =>
                  "No puedes insertar articulos si no estas logueado.");
          $this->flashdata->load($mensajes);
          redirect('/frontend/portada/');
      }

      $data['error'] = array();

      $config['upload_path'] = 'imagenes_articulos/';
      $config['allowed_types'] = 'jpeg|jpg|jpe';
      $config['overwrite'] = TRUE;
      $config['max_width'] = '5000';
      $config['max_height'] = '5000';
      $config['max_size'] = '500';
      $articulo_id = $this->session->userdata('usuario')['ultimo_articulo'];
      for ($i = 1; $i <= 4; $i++) {
          if(!is_file($_SERVER["DOCUMENT_ROOT"] .
                      '/imagenes_articulos/' .
                      $articulo_id . '_' . $i .
                      '.jpg')):
            $config['file_name'] = $articulo_id . '_' . $i . '.jpg';
            $this->load->library('upload', $config);

            if ( ! $this->upload->do_upload('foto')) {
              $data['error'] = $this->upload->display_errors();
            }
            else {
              $data = array('upload_data' => $this->upload->data());
            }
            break;
          endif;
      }

      $this->template->load('/articulos/subir_imagenes');
  }

  public function borrar_imagen($numero = NULL) {
      if($numero !== NULL && $this->Usuario->logueado()) {
          $articulo_id = $this->session->userdata('usuario')['ultimo_articulo'];
          unlink($_SERVER["DOCUMENT_ROOT"] .
                      '/imagenes_articulos/' .
                      $articulo_id . '_' . $numero .
                      '.jpg');
      }
  }

  public function vender($articulo_id = NULL) {
      if (!$this->Usuario->logueado()) {
          $mensajes[] = array('error' =>
                  "No puedes vender articulos si no estas logueado.");
          $this->flashdata->load($mensajes);
          redirect('/frontend/portada/');
      }
      $usuario_id = $this->session->userdata('usuario')['id'];
      if(!$this->Articulo->es_propietario($usuario_id, $articulo_id)) {
          redirect('/frontend/portada/');
      }
      else if($this->Articulo->articulo_vendido($articulo_id)) {
          redirect('/frontend/portada/');
      }

      if ($this->input->post('vender') !== NULL) {
          $reglas = array(
              array(
                  'field' => 'nick_comprador',
                  'label' => 'Nick comprador',
                  'rules' => array(
                      'trim', 'required',
                      array('existe_nick', array($this->Usuario, 'existe_nick')),
                      array('existe_nick_registrado', array($this->Usuario, 'existe_nick_registrado'))
                  ),
                  'errors' => array(
                      'existe_nick' => 'El nick debe ser de un usuario valido.',
                      'existe_nick_registrado' => 'El nick debe ser de un usuario valido.'
                  ),
              ),
          );

          $venta = $this->input->post();
          unset($venta['venta']);
          $valores = array(
              'vendedor_id' => $usuario_id,
              'comprador_id' => 0,
              'articulo_id' => $articulo_id,
          );

          if($venta['nick_comprador'] !== NULL) {
              $this->form_validation->set_rules($reglas);
              if ($this->form_validation->run() === TRUE) {
                  $comprador = $this->Usuario->por_nick($venta['nick_comprador']);
                  $valores['comprador_id'] = $comprador['id'];
              }
          }

          $this->Articulo->vender($valores);
          redirect('/frontend/portada/');
      }


      $data['articulo'] = $this->Articulo->por_id($articulo_id);
      $usuario_id = $data['articulo']['usuario_id'];
      $data['usuario'] = $this->Usuario->por_id($usuario_id);

      $data['opciones_venta'] = array(
          0 => "Venta normal",
          1 => "Venta con valoracion",
          2 => "Venta sin usuario",
      );

      $this->template->load('/articulos/vender', $data);
  }
}
