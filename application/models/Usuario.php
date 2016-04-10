<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Usuario extends CI_Model {
    public function __construct() {
        parent::__construct();

    }
    // Operaciones Chungas
    public function insertar($valores) {
        return $this->db->insert('usuarios', $valores);
    }
    public function editar($valores, $id) {
        return $this->db->where('id', $id)->update('usuarios', $valores);
    }

    // Operaciones de Lectura
    public function por_id($id) {
        $res = $this->db->get_where('usuarios', array('id' => $id));
        return $res->num_rows() > 0 ? $res->row_array() : FALSE;
    }

    public function por_id_vista($id) {
        $res = $this->db->get_where('v_articulos_por_vender', array('usuario_id' => $id));
        return $res->num_rows() > 0 ? $res->result_array() : FALSE;
    }

    public function logueado() {
        return $this->session->has_userdata('usuario');
    }

    public function por_nick($nick) {
        $res = $this->db->get_where('usuarios', array('nick' => $nick));
        return $res->num_rows() > 0 ? $res->row_array() : FALSE;
    }

    public function por_nick_registrado($nick) {
        $res = $this->db->get_where('v_usuarios_validados', array('nick' => $nick));
        return $res->num_rows() > 0 ? $res->row_array() : FALSE;
    }

    public function existe_nick($nick) {
        return $this->por_nick($nick) !== FALSE;
    }

    public function existe_nick_registrado($nick) {
        return $this->por_nick_registrado($nick) !== FALSE;
    }

    public function por_email($email) {
        $res = $this->db->get_where('usuarios', array('email' => $email));
        return $res->num_rows() > 0 ? $res->row_array() : FALSE;
    }

    public function existe_email($email) {
        return $this->por_email($email) !== FALSE;
    }

    public function es_admin() {
        $usuario = $this->session->userdata("usuario");
        return $usuario['rol_id'] === '1';
    }

    public function actualizar_password($id, $nueva_password) {
        return $this->db->query("update usuarios set password = ? where id::text = ?",
                          array($nueva_password, $id));
    }

    public function ventas_usuario($id_usuario) {
        $res = $this->db->query("select * from v_ventas where vendedor_id = ?", array($id_usuario));
        return ($res->num_rows() > 0) ? $res->result_array() : array();
    }
}
