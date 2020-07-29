<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		// Mengatasi Error Confirm Form Resubmission dimulai
		header('Cache-Control: no-cache, must-revalidate, max-age=0');
		header('Cache-Control: post-check=0, pre-check=0', false);
		header('Pragma: no-cache');
		// Mengatasi Error Confirm Form Resubmission selesai
		$this->load->model('Dashboard_model');
		$this->load->helper('url');
		$this->load->library('form_validation');
	}

	public function index()
	{
		/* 
		if ($this->session->userdata('role_id') == 1) {
			$this->getPerKecamatan();
			$this->getPerZona();
		} else if ($this->session->userdata('role_id') == 2) {
			$this->getPerKecamatan();
			$this->getPerZona();
		} else if ($this->session->userdata('role_id') == 3) {
			$this->getPerZona2();
		} else {
			$this->getPerZona2();
		} 
		*/
		$this->getPerKecamatan();
	}

	public function getPerKecamatan($start = null) //tambah perperiode
	{
		if ($this->input->post('submit')) {
			$data['periode'] = 'periode' . $this->input->post('periode');
			$this->session->set_userdata('periode', $data['periode']); //set periode kedalam session periode
		} else {
			if ($this->session->userdata('periode') == null) {
				$data['periode'] = 'periode' . date('Ym');
			} else {
				$data['periode'] = $this->session->userdata('periode'); // set $periode dengan session periode
			}
		}

		$config['base_url'] = base_url('Dashboard/getPerKecamatan');
		$config['per_page'] = 4;
		$config['total_rows'] = $this->Dashboard_model->countAllKecamatan($data['periode']); // kurang periode
		$config['uri_segment'] = 3;
		$data['total_rows'] = $config['total_rows'];
		//initialize
		$this->pagination->initialize($config);

		if ($start == null) {
			$start = 0;
		}
		$data['total_kecamatan'] = $config['total_rows'];

		$data['perKecamatan'] = $this->Dashboard_model->getPresurePerKecamatan($start, $config['per_page'], $data['periode']); //kurang periode
		$data['title'] = 'ManoDash';
		$this->load->view('templates/header');
		$this->load->view('templates/sidebar');
		$this->load->view('templates/topbar');
		$this->load->view('Dashboard/index', $data);
		$this->load->view('templates/footer');
	}

	public function getPerZona($idKecamatan, $periode, $start = null) //  untuk kasi dan direksi
	{
		$config['base_url'] = base_url('Dashboard/getPerZona/' . $idKecamatan . '/' . $periode . '/');
		$config['per_page'] = 4;
		$config['total_rows'] = $this->Dashboard_model->countAllZona($idKecamatan, $periode); //kurang periode
		$config['uri_segment'] = 5;
		$data['total_rows'] = $config['total_rows'];
		//initialize
		$this->pagination->initialize($config);

		if ($start == null) {
			$start = 0;
		}
		$data['total_zona'] = $config['total_rows'];

		$data['perZona'] = $this->Dashboard_model->getPresurePerZona($idKecamatan, $start, $config['per_page'], $periode); //kurang periode
		$data['perZonaNoLimit'] = $this->Dashboard_model->getPresurePerZonaNoLimit($idKecamatan, $start, $config['per_page'], $periode); //kurang periode
		$data['title'] = 'ManoDash';
		$data['idKecamatan'] = $idKecamatan;
		$this->load->view('templates/header');
		$this->load->view('templates/sidebar');
		$this->load->view('templates/topbar');
		$this->load->view('Dashboard/perZona', $data);
		$this->load->view('templates/footer');
	}

	public function getPerZona2($idKecamatan, $start = null) // tambah perperiode, untuk kacab dan pembaca
	{
		$config['base_url'] = base_url('Dashboard/getPerZona/' . $idKecamatan . '/');
		$config['per_page'] = 4;
		$config['total_rows'] = $this->Dashboard_model->countAllZona($idKecamatan); //kurang periode
		$config['uri_segment'] = 4;
		$data['total_rows'] = $config['total_rows'];
		//initialize
		$this->pagination->initialize($config);

		if ($start == null) {
			$start = 0;
		}
		$data['total_zona'] = $config['total_rows'];

		$data['perZona'] = $this->Dashboard_model->getPresurePerZona($idKecamatan, $start, $config['per_page']); //kurang periode
		$data['perZonaNoLimit'] = $this->Dashboard_model->getPresurePerZonaNoLimit($idKecamatan, $start, $config['per_page']); //kurang periode
		$data['title'] = 'ManoDash';
		$data['idKecamatan'] = $idKecamatan;
		$this->load->view('templates/header');
		$this->load->view('templates/sidebar');
		$this->load->view('templates/topbar');
		$this->load->view('Dashboard/perZona', $data);
		$this->load->view('templates/footer');
	}
}
