<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Usuario extends CI_Model {
    public function __construct()
    {
        parent::__construct();

    }

    public function por_id($id)
    {
        $res = $this->db->get_where('usuarios', array('id' => $id));
        return $res->num_rows() > 0 ? $res->row_array() : FALSE;
    }

    public function logueado()
    {
        return $this->session->has_userdata('usuario');
    }
}
