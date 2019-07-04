<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Aktivasi extends CI_Controller
{
    public function emailconfirm()
    {
        $this->load->model('User_model');
        $kodeemail = $this->uri->segment(3);
        $blmemail = substr($kodeemail, 10);
        $jadiemail = base64_decode($blmemail);
        $pengguna = $this->User_model->getuserA($jadiemail, $kodeemail);

        // bawah mesti diubah
        if ($pengguna) {
            if ($kodeemail == $pengguna['kode_konfir']) {
                $this->User_model->update_user_is_active(1, $jadiemail);
                $this->User_model->update_user_kode_konfir(0, $jadiemail);
                if ($pengguna['kode_konfir'] == 0) {

                    $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Verify Success');
                    redirect(base_url('auth'));
                    // echo 'berasil semua';
                } else {
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Your Code Confirmation is invalid !');
                    redirect(base_url('auth'));
                    // echo 'Your Code Confirmation is invalid !';
                }
            } else {
                // echo 'salah<br>';
                // echo 'belom email = ' . $kodeemail . '<br>';
                // echo $pengguna['kode_konfir'] . '<br>';
                // echo $blmemail;
                // echo base64_decode($blmemail);
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Wrong URL !');
                redirect(base_url('auth'));
                // echo 'Wrong URL !';
            }
        } else {
            redirect('auth/registration');
            // echo 'gakebaca';
        }
    }
}
