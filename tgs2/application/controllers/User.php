<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
	}


	public function index()
	{
		$this->load->model('User_model');
		$data['title'] = 'Welcome';
		$data['user'] = $this->User_model->getUserD();

		$this->load->library('user_agent');
		$data['browser'] = $this->agent->browser();
		$data['browser_version'] = $this->agent->version();
		$data['os'] = $this->agent->platform();
		$data['ip_address'] = $this->input->ip_address();
		$this->load->view('user/index', $data);
	}
	public function insert_table_mhs()
	{
		$this->load->model('Mhs_model');
		$this->load->model('User_model');
		$this->form_validation->set_rules('nim', 'NIM', 'required|trim|min_length[10]', ['min_length' => 'NIM To Short !']);
		$this->form_validation->set_rules('nama', 'Name', 'required|trim|min_length[3]', ['min_length' => 'Name to Short!']);
		$this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email', ['valid_email' => "This isn't An Email!"]);
		$this->form_validation->set_rules('jurusan', 'Jurusan', 'required|trim|min_length[5]', ['min_length' => 'Cant Find jurusan !']);
		if ($this->form_validation->run() == false) {
			$data['title'] = "Input Data";
			$data['user'] = $this->User_model->getUserD();
			$this->load->view('user/inserttablemhs', $data);
		} else {
			$data = [
				'NIM' => htmlspecialchars($this->input->post('nim', true)),
				'nama' => htmlspecialchars($this->input->post('nama', true)),
				'email' => htmlspecialchars($this->input->post('email', true)),
				'jurusan' => htmlspecialchars($this->input->post('jurusan', true))
			];
			$this->Mhs_model->add($data);
			$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Your Data Has Been Added! </div>');
			redirect('user/table');
		}
	}

	public function table()
	{
		$this->load->model('Mhs_model');
		$this->load->model('User_model');
		$data['title'] = 'Welcome';
		$data['user'] = $this->User_model->getUserD();
		$data['tm'] = $this->Mhs_model->getAllData();
		$this->load->view('user/table', $data);
	}

	public function delete($id)
	{
		$this->load->model('Mhs_model');
		$data['hapus'] = $this->Mhs_model->delete($id);
		return $this->load->view('user/table', $data);
	}
	public function edit($id)
	{
		$this->load->model('Mhs_model');
		$this->form_validation->set_rules('nim', 'NIM', 'required|trim|min_length[10]', ['min_length' => 'NIM To Short !']);
		$this->form_validation->set_rules('nama', 'Name', 'required|trim|min_length[3]', ['min_length' => 'Name to Short!']);
		$this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email', ['valid_email' => "This isn't An Email!"]);
		$this->form_validation->set_rules('jurusan', 'Jurusan', 'required|trim|min_length[5]', ['min_length' => 'Cant Find jurusan !']);
		if ($this->form_validation->run() == false) {
			$this->load->model('Mhs_model');
			$this->load->model('User_model');
			$data['title'] = "Input Data";
			$data['user'] = $this->User_model->getUserD();
			$data['mahasiswa'] = $this->Mhs_model->edit($id);
			$this->load->view('user/edit', $data);
		} else {
			$arr = [
				'NIM' => htmlspecialchars($this->input->post('nim', true)),
				'nama' => htmlspecialchars($this->input->post('nama', true)),
				'email' => htmlspecialchars($this->input->post('email', true)),
				'jurusan' => htmlspecialchars($this->input->post('jurusan', true))
			];
			$a = [
				'id' => $id
			];
			$this->Mhs_model->update($arr, $a);
			$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data Has Been Update! </div>');
			redirect('user/table');
		}
	}
}
