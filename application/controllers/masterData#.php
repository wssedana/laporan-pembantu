<?php
defined('BASEPATH') or exit('No direct script access allowed');

class masterData extends CI_Controller
{
    public function __construct()
    {
        date_default_timezone_set('Asia/Makassar');
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('masterData_model');
        $this->load->model('Mastermanometer_model');
        $this->load->library('libfunction');
        $this->load->helper(array('form', 'url'));
        //is_logged_in();
    }

    public function zona()
    {
        $nik = $this->session->userdata('nik');
        $idjabatan = $this->session->userdata('idjabatan');

        $db2 = $this->load->database('datacenter', TRUE);
        $query_str = "SELECT a.nik,b.nama,b.idareakerja,c.areakerja,c.wilayah,b.idjabatan,b.tugas,a.username,a.foto,a.level,a.role_id
                        FROM m_user a
                        LEFT JOIN m_pegawai b ON a.nik=b.nik
                        LEFT JOIN m_area_kerja c ON b.idareakerja=c.idareakerja
                        WHERE a.nik='$nik'";
        $data['user'] = $db2->query($query_str)->row_array();
        $data['title'] = "Master Zona";

        //pencarian
        if ($this->input->post('cari')) {
            $key = $this->input->post('keyword'); //search form
            $this->session->set_userdata('keywordMano', $key); //keyword disimpan sebagai session keywordMano dengan data (inputan)
        } else {
            $key = $this->session->userdata('keywordMano'); //mengambil data session keywordMano dengan data (inputan) untuk dikirim ke model sebagai parameter pencarian.
        };

        //filter
        if ($this->input->post('filter')) {
            $wilayah = $this->input->post('manoWilayah'); //search form
            $zona = $this->input->post('manoZona'); //search form

            $this->session->set_userdata('kodeWilayah', $wilayah);
            $this->session->set_userdata('kodeZona', $zona);
        } else {
            $kecamatan = $this->session->userdata('wilayah');
            if ($kecamatan != "KANTOR PUSAT") {
                $wilayah = $this->session->userdata('wilayah');
                $zona = $this->session->userdata('kodeZona');

                $this->session->set_userdata('kodeWilayah', $kecamatan);
            } else {
                $wilayah = $this->session->userdata('kodeWilayah');
                $zona = $this->session->userdata('kodeZona');
            }
        };

        $config['total_rows'] = $this->masterData_model->countZona($key, $wilayah, $zona);
        $config['base_url'] = base_url('masterData/zona/');
        $config['per_page'] = 10;

        //initialize
        $this->pagination->initialize($config);
        $start = $this->uri->segment(3);

        if ($start == null) {
            $start = 0;
        }

        $data['zona'] = $this->masterData_model->zona($key, $wilayah, $zona, $start, $config['per_page']);
        $data['total_rows'] = $config['total_rows'];
        $data['wilayah'] = $this->masterData_model->getWilayah();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('templates/topbar');
        $this->load->view('masterData/masterZona', $data);
        $this->load->view('templates/footer');
    }

    public function addZona()
    {
        $wilayah = $this->input->post('wilayah');
        $query = "SELECT MAX(urutan) as urutan FROM m_zona
        WHERE id_kecamatan = $wilayah";
        $urutan = $this->db->query($query)->row_array();
        $urutanBenar = $urutan['urutan'] + 1;

        $data = array(
            'id_kecamatan' => $wilayah,
            'zona' => $this->input->post('zona'),
            'create_date' => date('Y-m-d h:i:s'),
            'flag_active' => $this->input->post('flagActive'),
            'urutan' => $urutanBenar
        );

        $result = $this->db->insert('m_zona', $data);

        if ($result == true) {
            echo "success";
        } else {
            echo "gagal";
        }
    }

    public function dma()
    {
        $nik = $this->session->userdata('nik');
        $idjabatan = $this->session->userdata('idjabatan');

        $db2 = $this->load->database('datacenter', TRUE);
        $query_str = "SELECT a.nik,b.nama,b.idareakerja,c.areakerja,c.wilayah,b.idjabatan,b.tugas,a.username,a.foto,a.level,a.role_id
                        FROM m_user a
                        LEFT JOIN m_pegawai b ON a.nik=b.nik
                        LEFT JOIN m_area_kerja c ON b.idareakerja=c.idareakerja
                        WHERE a.nik='$nik'";
        $data['user'] = $db2->query($query_str)->row_array();
        $data['title'] = "Master Zona";

        //pencarian
        if ($this->input->post('cari')) {
            $key = $this->input->post('keyword'); //search form
            $this->session->set_userdata('keywordMano', $key); //keyword disimpan sebagai session keywordMano dengan data (inputan)
        } else {
            $key = $this->session->userdata('keywordMano'); //mengambil data session keywordMano dengan data (inputan) untuk dikirim ke model sebagai parameter pencarian.
        };

        //filter
        if ($this->input->post('filter')) {
            $wilayah = $this->input->post('manoWilayah'); //search form
            $zona = $this->input->post('manoZona'); //search form

            $this->session->set_userdata('kodeWilayah', $wilayah);
            $this->session->set_userdata('kodeZona', $zona);
        } else {
            $kecamatan = $this->session->userdata('wilayah');
            if ($kecamatan != "KANTOR PUSAT") {
                $wilayah = $this->session->userdata('wilayah');
                $zona = $this->session->userdata('kodeZona');

                $this->session->set_userdata('kodeWilayah', $kecamatan);
            } else {
                $wilayah = $this->session->userdata('kodeWilayah');
                $zona = $this->session->userdata('kodeZona');
            }
        };

        $config['total_rows'] = $this->masterData_model->countZona($key, $wilayah, $zona);
        $config['base_url'] = base_url('masterData/zona/');
        $config['per_page'] = 10;

        //initialize
        $this->pagination->initialize($config);
        $start = $this->uri->segment(3);

        if ($start == null) {
            $start = 0;
        }

        $data['zona'] = $this->masterData_model->zona($key, $wilayah, $zona, $start, $config['per_page']);
        $data['total_rows'] = $config['total_rows'];
        $data['wilayah'] = $this->masterData_model->getWilayah();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('templates/topbar');
        $this->load->view('masterData/masterDma', $data);
        $this->load->view('templates/footer');
    }
}
