<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Portada extends CI_Controller {

  public function __construct() {
    parent::__construct();
  }

  function index() {
      $data = $this->Articulo->todos();
      $this->template->load('frontend/index', $data);
  }
}
