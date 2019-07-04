<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->library('Ciqrcode');
		$this->load->database();
	}


	public function index()
	{
		$data['title'] = 'Log-in';
		$this->load->view('templates/auth_header', $data);
		$this->load->view('auth/login');
		$this->load->view('templates/auth_footer');
	}


	public function login()
	{
		// $this->load->view('templates/auth_header', $data);
		// $this->load->view('auth/login', $data);
		// $this->load->view('templates/auth_footer');
		// $data['title'] = 'Log-in';
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
		$this->form_validation->set_rules('password', 'Password', 'trim|required');
		// $this->load->library('user_agent');
		// $data['browser'] = $this->agent->browser();
		// $data['browser_version'] = $this->agent->version();
		// $data['os'] = $this->agent->platform();
		// $data['ip_address'] = $this->input->ip_address();
		if ($this->form_validation->run() == false) {
			$this->index();
			redirect('auth');
		} else {
			// validasi sukses
			$email = $this->input->post('email');
			$password = $this->input->post('password');
			$this->load->model('User_model');
			$user = $this->User_model->getUserL($email);
			//jika user ada 
			if ($user) {
				// ada user
				if ($user['is_active'] == 1) {
					// cek password
					if (password_verify($password, $user['password'])) {
						$data = [
							'email' => $user['email'],
						];
						$this->session->set_userdata($data);
						redirect('user');
					} else {
						$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Your Password incorrect!</div>');
						redirect('auth');
					}
				} else {
					$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Your email has not been Activated!</div>');
					redirect('auth');
				}
			} else {
				$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Your email not registered!</div>');
				redirect('auth');
			}
		}
	}

	public function QRcode($kodenya)
	{
		//render qr code dengan format gambar png
		QRcode::png(
			$kodenya,
			$outfile = false,
			$level = QR_ECLEVEL_H,
			$size = 3,
			$margin = 1
		);
	}


	function validate_captcha()
	{
		$captcha = $this->input->post('g-recaptcha-response');
		$response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6LfLSKsUAAAAAGWr_sAx6BOmzQ_ivuK9JKC-ze3G&response=" . $captcha . "&remoteip=" . $_SERVER['REMOTE_ADDR']);
		if ($response . 'success' == false) {
			return FALSE;
		} else {
			return TRUE;
		}
	}
	public function mail($tujuanemail, $isiemail, $subjectemail)
	{
		$this->load->library('email');
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

		$this->email->to($tujuanemail);
		$this->email->from('rifkytugas@gmail.com', 'Admin');
		$this->email->subject($subjectemail);
		$this->email->message($isiemail);

		//Send email
		$this->email->send();
	}
	public function setrandom($emailencode)
	{
		$isi = "1234567890abcdefghijklmnopqrstuvwxyz";
		$kode = substr(str_shuffle($isi), 0, 10);
		$result = $kode . $emailencode;
		return $result;
	}

	public function registration()

	{
		$this->load->model('User_model');
		$this->form_validation->set_rules('name', 'Name', 'required|trim');
		$this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[user.email]', [
			'is_unique' => 'This email has already taken !'
		]);
		$this->form_validation->set_rules('password1', 'Password', 'required|trim|min_length[3]|matches[password2]', [
			'matches' => 'Password not match!',
			'min_length' => 'Password to Short !'
		]);
		$this->form_validation->set_rules('password2', 'Password', 'required|trim|matches[password1]');
		$this->form_validation->set_rules('g-recaptcha-response', 'recaptcha validation', 'required|callback_validate_captcha');
		if ($this->form_validation->run() == false) {
			$data['title'] = 'User Registration';
			$this->load->view('templates/auth_header', $data);
			$this->load->view('auth/registration');
			$this->load->view('templates/auth_footer');
		} else {
			$emailori = htmlspecialchars($this->input->post('email', true));
			$emailaja = $this->input->post('email', true);
			$emailbase = base64_encode($emailori);
			$kodekonfir = $this->setrandom($emailbase);
			$data = [
				'name' => htmlspecialchars($this->input->post('name', true)),
				'email' => $emailori,
				'image' => 'default.jpg',
				'password' => password_hash($this->input->post('password1'), PASSWORD_DEFAULT),
				'is_active' => 0,
				'kode_konfir' => $kodekonfir

			];

			$this->User_model->addUser($data);
			$this->mail(
				$emailaja,
				'Please Confirmation Your Email To Log - in<br>' .
					'<!DOCTYPE html
					PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
				  <html xmlns="http://www.w3.org/1999/xhtml">
				  
				  <head>
					<meta name="viewport" content="width=device-width, initial-scale=1.0" />
					<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
					<title>Verify your email address</title>
					<style type="text/css" rel="stylesheet" media="all">
					  /* Base ------------------------------ */
					  *:not(br):not(tr):not(html) {
						-webkit-box-sizing: border-box;
						box-sizing: border-box;
					  }
				  
					  body {
						width: 100% !important;
						height: 100%;
						margin: 0;
						line-height: 1.4;
						background-color: #F5F7F9;
						color: #839197;
						-webkit-text-size-adjust: none;
					  }
				  
					  a {
						color: #414EF9;
					  }
				  
					  /* Layout ------------------------------ */
					  .email-wrapper {
						width: 100%;
						margin: 0;
						padding: 0;
						background-color: #F5F7F9;
					  }
				  
					  .email-content {
						width: 100%;
						margin: 0;
						padding: 0;
					  }
				  
					  /* Masthead ----------------------- */
					  .email-masthead {
						padding: 25px 0;
						text-align: center;
					  }
				  
					  .email-masthead_logo {
						max-width: 400px;
						border: 0;
					  }
				  
					  .email-masthead_name {
						font-size: 16px;
						font-weight: bold;
						color: black;
						text-decoration: none;
						text-shadow: 0 1px 0 white;
					  }
				  
					  /* Body ------------------------------ */
					  .email-body {
						width: 100%;
						margin: 0;
						padding: 0;
						border-top: 1px solid #E7EAEC;
						border-bottom: 1px solid #E7EAEC;
						background-color: #FFFFFF;
					  }
				  
					  .email-body_inner {
						width: 570px;
						margin: 0 auto;
						padding: 0;
					  }
				  
					  .email-footer {
						width: 570px;
						margin: 0 auto;
						padding: 0;
						text-align: center;
					  }
				  
					  .email-footer p {
						color: #839197;
					  }
				  
					  .body-action {
						width: 100%;
						margin: 30px auto;
						padding: 0;
						text-align: center;
					  }
				  
					  .body-sub {
						margin-top: 25px;
						padding-top: 25px;
						border-top: 1px solid #E7EAEC;
					  }
				  
					  .content-cell {
						padding: 35px;
					  }
				  
					  .align-right {
						text-align: right;
					  }
				  
					  /* Type ------------------------------ */
					  h1 {
						margin-top: 0;
						color: #292E31;
						font-size: 19px;
						font-weight: bold;
						text-align: left;
					  }
				  
					  h2 {
						margin-top: 0;
						color: #292E31;
						font-size: 16px;
						font-weight: bold;
						text-align: left;
					  }
				  
					  h3 {
						margin-top: 0;
						color: #292E31;
						font-size: 14px;
						font-weight: bold;
						text-align: left;
					  }
				  
					  p {
						margin-top: 0;
						color: #839197;
						font-size: 16px;
						line-height: 1.5em;
						text-align: left;
					  }
				  
					  p.sub {
						font-size: 12px;
					  }
				  
					  p.center {
						text-align: center;
					  }
				  
					  /* Buttons ------------------------------ */
					  .button {
						display: inline-block;
						width: 200px;
						background-color: #414EF9;
						border-radius: 3px;
						color: #ffffff;
						font-size: 15px;
						line-height: 45px;
						text-align: center;
						text-decoration: none;
						-webkit-text-size-adjust: none;
						mso-hide: all;
					  }
				  
					  .button--green {
						background-color: #28DB67;
						color:#ffffff;
					  }
				  
					  .button--red {
						background-color: #FF3665;
						color:#ffffff;
					  }
				  
					  .button--blue {
						background-color: #414EF9;
						color:#ffffff;
					  }
				  
					  /*Media Queries ------------------------------ */
					  @media only screen and (max-width: 600px) {
				  
						.email-body_inner,
						.email-footer {
						  width: 100% !important;
						}
					  }
				  
					  @media only screen and (max-width: 500px) {
						.button {
						  width: 100% !important;
						}
					  }
					</style>
				  </head>
				  
				  <body>
					<table class="email-wrapper" width="100%" cellpadding="0" cellspacing="0">
					  <tr>
						<td align="center">
						  <table class="email-content" width="100%" cellpadding="0" cellspacing="0">
							<!-- Logo -->
							<tr>
							  <td class="email-masthead">
								<a class="email-masthead_name">Email Verification</a>
							  </td>
							</tr>
							<!-- Email Body -->
							<tr>
							  <td class="email-body" width="100%">
								<table class="email-body_inner" align="center" width="570" cellpadding="0" cellspacing="0">
								  <!-- Body content -->
								  <tr>
									<td class="content-cell">
									  <h1>Please Verify your email address</h1>
									  <p>Thanks for signing up. We' . 're excited to have you as an early user.</p>
									  <!-- Action -->
									  <table class="body-action" align="center" width="100%" cellpadding="0" cellspacing="0">
										<tr>
										  <td align="center">
											<div>
											  <!--[if mso]><v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="{{action_url}}" style="height:45px;v-text-anchor:middle;width:200px;" arcsize="7%" stroke="f" fill="t">
											  <v:fill type="tile" color="#414EF9" />
											  <w:anchorlock/>
											  <center style="color:#ffffff;font-family:sans-serif;font-size:15px;">Verify Email</center>
											</v:roundrect><![endif]-->
											  ' . '<a href=' . base_url() . "aktivasi/emailconfirm/" . $kodekonfir . '
												
											  class="button button--green">
												pencet aku bang
											  </a>
				  
											</div>
										  </td>
										</tr>
									  </table>
									  <p>Thanks,<br>Tugas Kelompok</p>
									  <!-- Sub copy -->
									</td>
								  </tr>
								</table>
							  </td>
							</tr>
							<tr>
							  <td>
								<table class="email-footer" align="center" width="570" cellpadding="0" cellspacing="0">
								  <tr>
									<td class="content-cell">
									  <p class="sub center">
										&copy 2019 All Right Reserved
									  </p>
									</td>
								  </tr>
								</table>
							  </td>
							</tr>
						  </table>
						</td>
					  </tr>
					</table>
				  </body>
				  
				  </html>',
				'Verifikasi Email'
			);
			$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Your Account Has been created! Please Verification this On your email');
			redirect('auth');
		}
	}

	public function logout()
	{
		$this->session->unset_userdata('email');
		$this->session->unset_userdata('role_id');

		$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Succes Log out!</div>');
		redirect('auth');
	}
}
