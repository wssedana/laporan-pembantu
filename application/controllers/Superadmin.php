<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Superadmin extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        //nama function helpers yg kita buat, nama ini bisa disesuaikan dgn keinginan.
        is_logged_in();
        header('Cache-Control: no-cache, must-revalidate, max-age=0');
        header('Cache-Control: post-check=0, pre-check=0', false);
        header('Pragma: no-cache');
        // Mengatasi Error Confirm Form Resubmission selesai
        $this->load->library('libfunction');
        $this->load->model('Dashboard_model');
        $this->load->helper('url');
        $this->load->library('form_validation');
    }

    public function index($start = null)
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

        $nik = $this->session->userdata('nik');
        //title pages
        $data['title'] = 'Dashboard';
        $data['periodeBaca'] =  $this->libfunction->format_periode($data['periode']);

        $db2 = $this->load->database('datacenter', TRUE);
        $query_str = "SELECT a.nik,b.nama,b.idareakerja,c.areakerja,c.wilayah,b.idjabatan,b.tugas,a.username,a.foto,a.level,a.role_id
                        FROM m_user a
                        LEFT JOIN m_pegawai b ON a.nik=b.nik
                        LEFT JOIN m_area_kerja c ON b.idareakerja=c.idareakerja
                        WHERE a.nik='$nik'";
        $query = $db2->query($query_str);
        $data['user'] = $query->row_array();

        //memanggil model,  nama model         aliasnya
        $this->load->model('Mastermanometer_model', 'lsMasterData');

        //memanggil data dari database menggunakan model dengan memanggil aliasnya
        $data['listPeriode'] = $this->lsMasterData->getListPeriode();
        $data['perKecamatan'] = $this->Dashboard_model->getPresurePerKecamatan($start, $config['per_page'], $data['periode']); //kurang periode

        //------------------------------nama table database
        $data['lsMasterData'] = $this->db->get('information_schema.tables')->result_array();

        //memanggil views halaman admin -> $data: untuk mengirim data session user dan data title ke halaman index user
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('superadmin/index', $data);
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
        $nik = $this->session->userdata('nik');
        //title pages
        $data['title'] = 'Dashboard';
        $data['periodeBaca'] =  $this->libfunction->format_periode($periode);
        $db2 = $this->load->database('datacenter', TRUE);
        $query_str = "SELECT a.nik,b.nama,b.idareakerja,c.areakerja,c.wilayah,b.idjabatan,b.tugas,a.username,a.foto,a.level,a.role_id
                        FROM m_user a
                        LEFT JOIN m_pegawai b ON a.nik=b.nik
                        LEFT JOIN m_area_kerja c ON b.idareakerja=c.idareakerja
                        WHERE a.nik='$nik'";
        $query = $db2->query($query_str);
        $data['user'] = $query->row_array();

        //memanggil model,  nama model         aliasnya
        $this->load->model('Mastermanometer_model', 'lsMasterData');

        //memanggil data dari database menggunakan model dengan memanggil aliasnya
        $data['listPeriode'] = $this->lsMasterData->getListPeriode();

        $data['total_zona'] = $config['total_rows'];

        $data['perZona'] = $this->Dashboard_model->getPresurePerZona($idKecamatan, $start, $config['per_page'], $periode);
        $data['perZonaNoLimit'] = $this->Dashboard_model->getPresurePerZonaNoLimit($idKecamatan, $periode);
        $data['title'] = 'ManoDash';
        $data['idKecamatan'] = $idKecamatan;

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('superadmin/perZona', $data);
        $this->load->view('templates/footer');
    }
}
//end of Admin
