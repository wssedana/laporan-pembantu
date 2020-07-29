<?php
defined('BASEPATH') or exit('No direct script access allowed');

class posting extends CI_Controller
{
    public function __construct()
    {
        date_default_timezone_set('Asia/Makassar');
        parent::__construct();
        //nama function helpers yg kita buat, nama ini bisa disesuaikan dgn keinginan.
        is_logged_in();
        $this->load->model('posting_model');
        $this->load->library('libfunction');
    }

    public function manometer()
    {
        $nik = $this->session->userdata('nik');
        //title pages
        $data['title'] = 'Posting Manometer';

        $db2 = $this->load->database('datacenter', TRUE);
        $query_str = "SELECT a.nik,b.nama,b.idareakerja,c.areakerja,c.wilayah,b.idjabatan,b.tugas,a.username,a.foto,a.level,a.role_id
                        FROM m_user a
                        LEFT JOIN m_pegawai b ON a.nik=b.nik
                        LEFT JOIN m_area_kerja c ON b.idareakerja=c.idareakerja
                        WHERE a.nik='$nik'";
        $query = $db2->query($query_str);
        $data['user'] = $query->row_array();

        $data['list_manometer_posting'] = $this->posting_model->getListManometerPosting();

        //memanggil views halaman admin -> $data: untuk mengirim data session user dan data title ke halaman index user
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('posting/manometer', $data);
        $this->load->view('templates/footer');
    }

    public function prosesPostingMano()
    {
        $periodePostingMano = "manometerposting" . $this->input->post('periodePostingMano');
        $this->posting_model->postingMano($periodePostingMano);
    }

    public function sr()
    {
        $nik = $this->session->userdata('nik');
        //title pages
        $data['title'] = 'Posting Manometer';

        $db2 = $this->load->database('datacenter', TRUE);
        $query_str = "SELECT a.nik,b.nama,b.idareakerja,c.areakerja,c.wilayah,b.idjabatan,b.tugas,a.username,a.foto,a.level,a.role_id
                        FROM m_user a
                        LEFT JOIN m_pegawai b ON a.nik=b.nik
                        LEFT JOIN m_area_kerja c ON b.idareakerja=c.idareakerja
                        WHERE a.nik='$nik'";
        $query = $db2->query($query_str);
        $data['user'] = $query->row_array();

        $data['list_sr_posting'] = $this->posting_model->getListSrPosting();

        //memanggil views halaman admin -> $data: untuk mengirim data session user dan data title ke halaman index user
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('posting/SR', $data);
        $this->load->view('templates/footer');
    }

    public function prosesPostingSR()
    {
        $periodePostingSR = "SRposting" . $this->input->post('periodePostingSR');
        $this->posting_model->postingSR($periodePostingSR);
    }
}
