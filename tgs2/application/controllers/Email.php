<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Email extends CI_Controller
{

    public function index()
    {
        $this->load->library('email');
        $htmlContent = "AKUSUKAMMK";
        $config = array(
            'protocol'  => 'smtp',
            'smtp_host' => 'ssl://smtp.gmail.com',
            'smtp_port' => 465,
            'smtp_user' => 'rifkytugas@gmail.com',
            'smtp_pass' => 'palang17',
            'mailtype'  => 'html',
            'charset'   => 'utf-8'
        );


        $this->email->initialize($config);
        $this->email->set_mailtype("html");
        $this->email->set_newline("\r\n");

        //Email content
        $htmlContent = '<h1>Sending email via SMTP server</h1>';
        $htmlContent .= '<p>This email has sent via SMTP server from CodeIgniter application.</p>';

        $this->email->to('rifkywinata100@gmail.com');
        $this->email->from('rifkytugas@gmail.com', 'MyWebsite');
        $this->email->subject('How to send email via SMTP server in CodeIgniter');
        $this->email->message($htmlContent);

        //Send email
        $this->email->send();
    }
}
