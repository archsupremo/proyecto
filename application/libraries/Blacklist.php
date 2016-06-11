<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Blacklist {
    public function __construct() {
        $CI =& get_instance();
        $res = $CI->db->get_where('ips_baneadas', array('ip' => $this->get_real_ip()));
        if($res->num_rows() > 0) {
            $CI->load->view('baned');
        }
    }

    public function get_real_ip() {
        if (!empty($_SERVER['HTTP_CLIENT_IP']))
            return $_SERVER['HTTP_CLIENT_IP'];
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        return $_SERVER['REMOTE_ADDR'];
    }
}
