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
}
