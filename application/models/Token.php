<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Token extends CI_Model
{
    public function por_usuario_id($usuario_id)
    {
        $res = $this->db->get_where('tokens', array('usuario_id' => $usuario_id));
        return $res->num_rows() > 0 ? $res->row_array() : FALSE;
    }
    public function generar($usuario_id)
    {
        if ($this->Usuario->por_id($usuario_id) !== FALSE)
        {
            $nuevo_token = md5(rand());
            $token = $this->por_usuario_id($usuario_id);
            if ($token === FALSE)
            {
                $this->db->insert('tokens', array('usuario_id' => $usuario_id,
                                                  'token' => $nuevo_token));
            }
            else
            {
                $this->db->where('usuario_id', $usuario_id)->
                    update('tokens', array('token' => $nuevo_token));
            }
            return $nuevo_token;
        }
        return FALSE;
    }

    public function borrar($usuario_id)
    {
        if ($this->Usuario->por_id($usuario_id) !== FALSE)
        {
            $this->db->delete('tokens', array('usuario_id' => $usuario_id));
            return TRUE;
        }
        return FALSE;
    }

    public function comprobar($usuario_id, $token_comprobar)
    {
        if ($this->Usuario->por_id($usuario_id) !== FALSE)
        {
            $token = $this->por_usuario_id($usuario_id);
            if ($token === FALSE)
            {
                return FALSE;
            }
            else
            {
                return $token_comprobar === $token['token'];
            }
        }
        return FALSE;
    }
}
