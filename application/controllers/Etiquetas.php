<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Etiquetas extends CI_Controller{

    public function __construct() {
        parent::__construct();
        //Codeigniter : Write Less Do More
    }

    public function buscar($palabras = NULL) {
        if($palabras === NULL) {
            $palabras = "";
        }
        if(isset($_GET['term']) && $_GET['term'] !== NULL) {
            $palabras = trim($_GET['term']);
        }
        $raw = $this->Etiqueta->buscar(strtolower($palabras));
        $etiquetas = array();
        foreach ($raw as $v) {
            array_push($etiquetas, $v['nombre']);
        }

        echo json_encode(
            $etiquetas
        );
    }
}
