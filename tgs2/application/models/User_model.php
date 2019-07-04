<?php
defined('BASEPATH') or exit('No direct script access allowed');
class User_model extends CI_Model
{
    public function getAllUser()
    {
        $query = $this->db->get('user');
        return $query->result_array();
    }
    public function getUserL($email)
    {
        return $this->db->get_where('user', ['email' => $email])->row_array();
    }
    public function getUserA($email, $decode)
    {
        return $this->db->get_where('user', array('email' => $email, 'kode_konfir' => $decode))->row_array();
    }
    public function addUser($a)
    {
        return $this->db->insert('user', $a);
    }
    public function getUserD()
    {
        return $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
    }
    public function update_user_is_active($value, $email)
    {
        return $this->db->query("UPDATE user SET is_active = '$value' WHERE email = '$email' ");
    }
    public function update_user_kode_konfir($value, $email)
    {
        return $this->db->query("UPDATE user SET kode_konfir = '$value' WHERE email = '$email' ");
    }
}
