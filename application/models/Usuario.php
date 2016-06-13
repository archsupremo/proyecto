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
    public function insertar_pm($valores) {
        return $this->db->insert('pm', $valores);
    }

    public function update_pm($valores, $id) {
        return $this->db->where('id', $id)->update('pm', $valores);
    }

    public function banear_usuario($usuario_id) {
        return $this->db->insert('usuarios_baneados', array('usuario_id' => $usuario_id));
    }

    public function banear_ip($ip) {
        return $this->db->insert('ips_baneadas', array('ip' => $ip));
    }

    // Operaciones de Lectura
    public function todos() {
        return $this->db->get('v_usuarios')->result_array();
    }

    public function ip_baneada($ip) {
        $res = $this->db->get_where('ips_baneadas', array('ip' => $ip));
        return $res->num_rows() > 0 ? TRUE : FALSE;
    }

    public function get_ip_usuario($usuario_id) {
        $this->db->select('ip');
        $res = $this->db->get_where('v_usuarios', array('id' => $usuario_id));
        return $res->num_rows() > 0 ? $res->row_array() : FALSE;
    }

    public function usuario_baneado_nick($nick) {
        $res = $this->db->get_where('v_usuarios', array('nick' => strtolower($nick), 'baneado' => TRUE));
        return $res->num_rows() > 0 ? FALSE : TRUE;
    }

    public function usuario_baneado($usuario_id) {
        $res = $this->db->get_where('usuarios_baneados', array('usuario_id' => $usuario_id));
        return $res->num_rows() > 0 ? TRUE : FALSE;
    }

    public function por_id($id) {
        $res = $this->db->get_where('v_usuarios', array('id' => $id));
        return $res->num_rows() > 0 ? $res->row_array() : FALSE;
    }

    public function por_id_admin($id) {
        $res = $this->db->get_where('usuarios_admin', array('id' => $id));
        return $res->num_rows() > 0 ? $res->row_array() : FALSE;
    }

    public function por_id_vista($id) {
        $res = $this->db->get_where('v_articulos', array('usuario_id' => $id));
        return $res->num_rows() > 0 ? $res->result_array() : array();
    }

    public function logueado() {
        if($this->session->has_userdata('usuario')) {
            $usuario = $this->session->userdata('usuario');
            if($this->usuario_baneado($usuario['id']) && !$usuario['admin']) {
                return FALSE;
            }
        }
        return $this->session->has_userdata('usuario');
    }

    public function por_nick($nick) {
        $res = $this->db->get_where('usuarios', array('nick' => strtolower($nick)));

        if($res->num_rows() === 0) {
            $res = $this->db->get_where('usuarios_admin', array('nick' => strtolower($nick)));
        }
        return $res->num_rows() > 0 ? $res->row_array() : FALSE;
    }

    public function es_admin($nick) {
        $res = $this->db->get_where('usuarios_admin', array('nick' => strtolower($nick)));
        return $res->num_rows() > 0 ? TRUE : FALSE;
    }

    public function por_password_old($password_old, $usuario_id) {
        $res = $this->db->query("select * from usuarios where password = ? and id = ?",
                                array($password_old, $usuario_id));
        return $res->num_rows() > 0 ? $res->row_array() : FALSE;
    }

    public function existe_nick_id($nick, $usuario_id) {
        $res = $this->db->query("select * from usuarios where nick = ? and id != ?",
                                array(strtolower($nick), $usuario_id));
        return $res->num_rows() > 0 ? $res->row_array() : FALSE;
    }

    public function por_nick_registrado($nick) {
        if($this->es_admin($nick)) {
            return TRUE;
        }
        $res = $this->db->get_where('v_usuarios_validados', array('nick' => $nick));
        return $res->num_rows() > 0 ? $res->row_array() : FALSE;
    }

    public function existe_nick($nick) {
        return $this->por_nick(strtolower($nick)) !== FALSE;
    }

    public function existe_nick_registrado($nick) {
        return $this->por_nick_registrado(strtolower($nick)) !== FALSE;
    }

    public function por_email($email) {
        $res = $this->db->get_where('usuarios', array('email' => $email));
        return $res->num_rows() > 0 ? $res->row_array() : FALSE;
    }

    public function existe_email_id($email, $usuario_id) {
        $res = $this->db->query("select * from usuarios where email = ? and id != ?",
                                array($email, $usuario_id));
        return $res->num_rows() > 0 ? $res->row_array() : FALSE;
    }

    public function existe_email($email) {
        return $this->por_email($email) !== FALSE;
    }

    public function actualizar_password($id, $nueva_password) {
        return $this->db->query("update usuarios set password = ? where id::text = ?",
                          array($nueva_password, $id));
    }

    public function ventas_usuario($id_usuario) {
        $res = $this->db->query("select * from v_ventas_vendedor where vendedor_id = ?", array($id_usuario));
        return ($res->num_rows() > 0) ? $res->result_array() : array();
    }

    public function compras_usuario($usuario_id) {
        $res = $this->db->get_where('v_ventas_comprador',
                                    array(
                                        'comprador_id' => $usuario_id,
                                       )
                                    );
        return $res->num_rows() > 0 ? $res->result_array() : array();
    }

    public function usuarios_cercanos($latitud, $longitud, $distancia) {
        $res = $this->db->query("select *, earth_distance(ll_to_earth(?, ?),".
                                " ll_to_earth(latitud, longitud)) as distancia".
                                " from v_usuarios_localizacion".
                                " where earth_distance(ll_to_earth(?, ?),".
                                " ll_to_earth(latitud, longitud)) < ?",
                                 array($latitud, $longitud, $latitud, $longitud, $distancia));
        // $res = $this->db->query("select * from v_usuarios_localizacion",
        //                         array());
        return $res->result_array();
    }

    public function pm_no_vistos($usuario_id) {
        $res = $this->db->query("select * from v_usuarios_pm_no_vistos where receptor_id = ?",
                                array($usuario_id));
        return $res->result_array();
    }

    public function pm_vistos($usuario_id) {
        $res = $this->db->query("select * from v_usuarios_pm_vistos where receptor_id = ?",
                                array($usuario_id));
        return $res->result_array();
    }

    public function usuarios_nick($nick, $usuario_id) {
        $res = $this->db->query("select * from usuarios where nick = ? and id != ?",
                                array(strtolower($nick), $usuario_id));
        return $res->num_rows() > 0 ? TRUE : FALSE;
    }

    public function usuarios_nick_total($nick) {
        $res = $this->db->query("select * from usuarios where nick = ?",
                                array(strtolower($nick)));
        return $res->num_rows() > 0 ? TRUE : FALSE;
    }

    public function usuarios_email($email, $usuario_id) {
        $res = $this->db->query("select * from usuarios where email = ? and id != ?",
                                array($email, $usuario_id));
        return $res->num_rows() > 0 ? TRUE : FALSE;
    }

    public function usuarios_email_total($email) {
        $res = $this->db->query("select * from usuarios where email = ?",
                                array($email));
        return $res->num_rows() > 0 ? TRUE : FALSE;
    }

    public function get_pm($pm_id) {
        $res = $this->db->get_where('pm', array('id' => $pm_id));
        return $res->num_rows() > 0 ? $res->row_array() : FALSE;
    }

    public function valoraciones_a_comprador($usuario_id) {
        $res = $this->db->get_where('v_ventas_vendedor',
                                    array(
                                        'comprador_id' => $usuario_id,
                                       )
                                    );
        return $res->num_rows() > 0 ? $res->result_array() : array();
    }

    public function valoraciones_a_vendedor($usuario_id) {
        $res = $this->db->get_where('v_ventas_comprador',
                                    array(
                                        'vendedor_id' => $usuario_id,
                                       )
                                    );
        return $res->num_rows() > 0 ? $res->result_array() : array();
    }
}
