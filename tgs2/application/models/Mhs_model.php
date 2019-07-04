<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mhs_model extends CI_Model
{
    public function getAllData()
    {
        $query = $this->db->get('mahasiswa');
        return $query->result_array();
    }
    public function add($data)
    {
        return $this->db->insert('mahasiswa', $data);
    }
    public function delete($id)
    {
        $select = $this->db->where('id', $id);
        $this->db->delete('mahasiswa');
        $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Your Data Has Been Delete! </div>');
        redirect('user/table');
    }
    public function edit($id)
    {
        $this->db->where('id', $id);
        return $this->db->get('mahasiswa')->result_array();
    }
    public function update($a, $b)
    {
        return $this->db->update('mahasiswa', $a, $b);
    }
}
