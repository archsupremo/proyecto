<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Info extends CI_Controller{

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
  }

  function index() {
      redirect('/frontend/portada');
  }

  public function uso_sitio() {
      $this->template->load('/info/uso_sitio');
  }

  public function politica_datos() {
      $this->template->load('/info/politica_datos');
  }

  public function cookies() {
      $this->template->load('/info/cookies');
  }

  public function reglas_convivencia() {
      $this->template->load('/info/reglas_convivencia');
  }
}
