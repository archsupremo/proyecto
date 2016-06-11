<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuarios extends CI_Controller{

  public function __construct() {
    parent::__construct();
  }

  function index() {
      redirect('/frontend/portada/');
  }

  public function listado_usuarios() {
      $usuario = $this->session->userdata('usuario');
      if (!$this->Usuario->logueado() || !$usuario['admin']) {
          redirect('/frontend/portada/');
      }

      $data['usuarios'] = $this->Usuario->todos();

      $this->template->load('/backend/listado_usuarios', $data);
  }

  public function banear_usuario($usuario_id = NULL) {
      $usuario = $this->session->userdata('usuario');
      $exito = FALSE;
      if($this->Usuario->logueado() && $usuario['admin']) {
          if(!$this->Usuario->usuario_baneado($usuario_id)) {
              $insert = $this->Usuario->banear_usuario($usuario_id);
              $exito = $insert;
          }
      }
      echo json_encode(array(
          'exito' => $exito
      ));
  }

  public function banear_ip($usuario_id = NULL) {
      $usuario = $this->session->userdata('usuario');
      $exito = FALSE;

      if($this->Usuario->logueado() && $usuario['admin']) {
          $ip = $this->Usuario->get_ip_usuario($usuario_id);
          if($ip !== NULL) {
              if(!$this->Usuario->ip_baneada($ip['ip'])) {
                  $insert = $this->Usuario->banear_ip($ip['ip']);
                  $exito = $insert;
              }
          }
      }
      echo json_encode(array(
          'exito' => $exito
      ));
  }
}
