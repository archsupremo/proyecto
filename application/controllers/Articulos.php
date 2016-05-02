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
      if (!empty($_FILES)) {
          $tempFile = $_FILES['file']['tmp_name'];
          $targetPath = $_SERVER['DOCUMENT_ROOT'] . '/imagenes_articulos/';
          $targetFile = $targetPath . "asd.jpg";
          move_uploaded_file($tempFile, $targetFile);
      }
      if (!$this->Usuario->logueado()) {
          $mensajes[] = array('error' =>
                  "No puedes insertar articulos si no estas logueado.");
          $this->flashdata->load($mensajes);
          redirect('/frontend/portada/');
      }

      if ($this->input->post('subir') !== NULL) {
          $articulo = $this->input->post();
          unset($articulo['subir']);

          $articulo['usuario_id'] = $this->session->userdata('usuario')['id'];
          $this->Articulo->insertar($articulo);
          $mensajes[] = array('info' =>
                  "Articulo insertado correctamente :)");
          $this->flashdata->load($mensajes);

          $articulo_insertado = $this->Articulo->ultimo_articulo();
          $articulo_id = $articulo_insertado['id'];

          if (!empty($_FILES)) {
              $tempFile = $_FILES['file']['tmp_name'];
      	      $targetPath = $_SERVER['DOCUMENT_ROOT'] . '/imagenes_articulos/';
      	      $targetFile = $targetPath . $articulo_id . ".jpg";
      	      move_uploaded_file($tempFile, $targetFile);
          }
        //   $data['error'] = array();
          //
        //   $config['upload_path'] = 'imagenes_articulos/';
        //   $config['allowed_types'] = 'jpeg|jpg|jpe';
        //   $config['overwrite'] = TRUE;
        //   $config['max_width'] = '5000';
        //   $config['max_height'] = '5000';
        //   $config['max_size'] = '500';
        //   $config['file_name'] = $articulo_id . '.jpg';
          //
        //   $this->load->library('upload', $config);
          //
        //   if ( ! $this->upload->do_upload('foto')) {
        //     $data['error'] = $this->upload->display_errors();
        //   }
        //   else {
        //     $data = array('upload_data' => $this->upload->data());
        //   }
          redirect('/frontend/portada');
      }

      $categorias_raw = $this->Articulo->categorias();
      $categorias = array();
      foreach ($categorias_raw as $categoria) {
          $categorias[$categoria['id']] = $categoria['nombre'];
      }
      $data['categorias'] = $categorias;
      $this->template->load('/articulos/subir', $data);
  }

  public function upload() {
    if (!empty($_FILES)) {
        $tempFile = $_FILES['file']['tmp_name'];
	    $targetPath = $_SERVER['DOCUMENT_ROOT'] . '/imagenes_articulos/';

        $articulo_insertado = $this->Articulo->ultimo_articulo();
        $articulo_id = $articulo_insertado['id'];

	    $targetFile = $targetPath . $articulo_id . ".jpg";
	    move_uploaded_file($tempFile, $targetFile);
    }
  }
}
