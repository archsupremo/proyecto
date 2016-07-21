<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Info extends CI_Controller{

  public function __construct() {
    parent::__construct();
    //Codeigniter : Write Less Do More
  }

  function index() {
      redirect('/frontend/portada');
  }

  public function uso_sitio() {
      $this->breadcrumbcomponent->add('Home', base_url());
      $this->breadcrumbcomponent->add('Info', base_url() . 'info/uso_sitio/');
      $this->breadcrumbcomponent->add('Información sobre el uso deñ sitio web', base_url());
      $this->template->load('/info/uso_sitio');
  }

  public function politica_datos() {
      $this->breadcrumbcomponent->add('Home', base_url());
      $this->breadcrumbcomponent->add('Info', base_url() . 'info/politica_datos/');
      $this->breadcrumbcomponent->add('Politica de datos', base_url());

    //   $this->template->load('/usuarios/login');
      $this->template->load('/info/politica_datos');
  }

  public function cookies() {
      $this->breadcrumbcomponent->add('Home', base_url());
      $this->breadcrumbcomponent->add('Info', base_url() . 'info/cookies/');
      $this->breadcrumbcomponent->add('Cookies', base_url());
      $this->template->load('/info/cookies');
  }

  public function reglas_convivencia() {
      $this->breadcrumbcomponent->add('Home', base_url());
      $this->breadcrumbcomponent->add('Info', base_url() . 'info/reglas_convivencia/');
      $this->breadcrumbcomponent->add('Reglas de convivencia', base_url());
      $this->template->load('/info/reglas_convivencia');
  }
}
