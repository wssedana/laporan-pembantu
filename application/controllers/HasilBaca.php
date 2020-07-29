<?php

use Mpdf\Tag\Input;

defined('BASEPATH') or exit('No direct script access allowed');

class HasilBaca extends CI_Controller
{
    public function __construct()
    {
        date_default_timezone_set('Asia/Makassar');
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('Hasilbaca_model');
        $this->load->model('Mastermanometer_model');
        $this->load->library('libfunction');
        $this->load->helper(array('form', 'url'));
        //is_logged_in();
    }

    public function index()
    {
        $nik = $this->session->userdata('nik');
        $db2 = $this->load->database('datacenter', TRUE);
        $query_str = "SELECT a.nik,b.nama,b.idareakerja,c.areakerja,c.wilayah,b.idjabatan,b.tugas,a.username,a.foto,a.level,a.role_id
                        FROM m_user a
                        LEFT JOIN m_pegawai b ON a.nik=b.nik
                        LEFT JOIN m_area_kerja c ON b.idareakerja=c.idareakerja
                        WHERE a.nik='$nik'";
        $query = $db2->query($query_str);
        $data['user'] = $query->row_array();
        $data['title'] = 'Hasil Baca Manometer';

        //memanggil model,  nama model         aliasnya
        $this->load->model('Hasilbaca_model', 'lsMasterData');

        //memanggil data dari database menggunakan model dengan memanggil aliasnya
        $data['listPeriode'] = $this->lsMasterData->getListPeriode();
        //------------------------------nama table database
        $data['lsMasterData'] = $this->db->get('information_schema.tables')->result_array();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('templates/topbar');
        $this->load->view('hasilBaca/index', $data);
        $this->load->view('templates/footer');
    }

    public function listPeriode()
    {
        $nik = $this->session->userdata('nik');
        $db2 = $this->load->database('datacenter', TRUE);
        $query_str = "SELECT a.nik,b.nama,b.idareakerja,c.areakerja,c.wilayah,b.idjabatan,b.tugas,a.username,a.foto,a.level,a.role_id
                        FROM m_user a
                        LEFT JOIN m_pegawai b ON a.nik=b.nik
                        LEFT JOIN m_area_kerja c ON b.idareakerja=c.idareakerja
                        WHERE a.nik='$nik'";
        $query = $db2->query($query_str);
        $data['user'] = $query->row_array();
        $data['title'] = 'Hasil Baca Manometer';

        //memanggil model,  nama model         aliasnya
        $this->load->model('Hasilbaca_model', 'lsMasterData');

        //memanggil data dari database menggunakan model dengan memanggil aliasnya
        $data['listPeriode'] = $this->lsMasterData->getListPeriode();
        //------------------------------nama table database
        $data['lsMasterData'] = $this->db->get('information_schema.tables')->result_array();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('templates/topbar');
        $this->load->view('hasilBaca/listperiode', $data);
        $this->load->view('templates/footer');
    }

    public function periode($periode)
    {
        $do = $this->input->get('do');
        $nik = $this->session->userdata('nik');
        $idjabatan = $this->session->userdata('idjabatan');
        $wilayah = $this->session->userdata('wilayah');

        $db2 = $this->load->database('datacenter', TRUE);
        $query_str = "SELECT a.nik,b.nama,b.idareakerja,c.areakerja,c.wilayah,b.idjabatan,b.tugas,a.username,a.foto,a.level,a.role_id
                        FROM m_user a
                        LEFT JOIN m_pegawai b ON a.nik=b.nik
                        LEFT JOIN m_area_kerja c ON b.idareakerja=c.idareakerja
                        WHERE a.nik='$nik'";
        $query = $db2->query($query_str);
        $data['user'] = $query->row_array();
        $data['periodebaca'] =  $this->libfunction->format_periode($periode);
        $data['title'] = 'Hasil Baca ' . $this->libfunction->format_periode($periode);

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

        if ($this->input->post('cariTanggal')) {
            $daterange = $this->input->post('daterange');
            $tglStart = $this->input->post('tglStart'); //search form
            $tglEnd = $this->input->post('tglEnd'); //search form
            $this->session->set_userdata('dateRange', $daterange);
            $this->session->set_userdata('tglStart', $tglStart);
            $this->session->set_userdata('tglEnd', $tglEnd);
        } else {
            $daterange = $this->session->userdata('dateRange');
            $tglStart = $this->session->userdata('tglStart');
            $tglEnd = $this->session->userdata('tglEnd');
        }

        //filter data
        $data['wilayah'] = $this->Mastermanometer_model->getWilayah();
        $data['zona'] = $this->Mastermanometer_model->getZona($wilayah);

        //menghitung jumlah data
        $config['total_rows'] = $this->Hasilbaca_model->countReadingAll($periode, $key, $wilayah, $zona, $idjabatan, $nik, $daterange, $tglStart, $tglEnd);
        $data['total_rows'] = $config['total_rows'];
        $data['keyword'] = $key;

        //set Halaman per page
        if ($per_page = $this->input->post('per_page')) {
            if ($per_page == null) {
                $this->session->set_userdata('perPage', 10); //keyword disimpan sebagai session keywordMano dengan data (inputan)
            } else if ($per_page == "all") {
                $this->session->set_userdata('alldata', "yes");
                $this->session->set_userdata('perPage', $config['total_rows']);
            } else {
                $this->session->set_userdata('alldata', "no");
                $this->session->set_userdata('perPage', $per_page); //keyword disimpan sebagai session keywordMano dengan data (inputan)
            }
        } else {
            $per_page = $this->session->userdata('perPage'); //mengambil data session keywordMano dengan data (inputan) untuk dikirim ke model sebagai parameter pencarian.
            if ($per_page == null) {
                $this->session->set_userdata('perPage', 10); //keyword disimpan sebagai session keywordMano dengan data (inputan)
            } else {
                $this->session->set_userdata('perPage', $per_page); //keyword disimpan sebagai session keywordMano dengan data (inputan)
            }
        }

        $per_page = $this->session->userdata('perPage');

        //membuat config halaman/pagination
        $config['base_url'] = base_url('hasilbaca/periode/' . $periode);
        $config['per_page'] = $per_page;


        //initialize
        $this->pagination->initialize($config);
        $start = $this->uri->segment(4);

        if ($start == null) {
            $start = 0;
        }

        $data['bacaan'] = $this->Hasilbaca_model->getReadingAll($periode, $key, $wilayah, $zona, $start, $config['per_page'], $idjabatan, $nik, $daterange, $tglStart, $tglEnd);
        $data['lapbacaan'] = $this->Hasilbaca_model->getReadingReport($periode, $key, $wilayah, $zona, $idjabatan, $nik, $daterange, $tglStart, $tglEnd);
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('templates/topbar');
        $this->load->view('hasilBaca/periode', $data);
        $this->load->view('templates/footer');

        if ($do == "cetak") {
            $mpdf = new \Mpdf\Mpdf();
            $html = $this->load->view('hasilBaca/dataPeriode_pdf', $data, true);
            $mpdf->WriteHTML($html);
            $mpdf->Output();
        }

        if ($do == "cetak2") {
            $mpdf = new \Mpdf\Mpdf();
            $html = $this->load->view('hasilBaca/dataPeriode_pdf2', $data, true);
            $mpdf->WriteHTML($html);
            $mpdf->Output();
        }
    }

    public function harian($periode)
    {
        $do = $this->input->get('do');
        $nik = $this->session->userdata('nik');
        $idjabatan = $this->session->userdata('idjabatan');
        $wilayah = $this->session->userdata('wilayah');
        $daterange = $this->input->post('daterange');

        $db2 = $this->load->database('datacenter', TRUE);
        $query_str = "SELECT a.nik,b.nama,b.idareakerja,c.areakerja,c.wilayah,b.idjabatan,b.tugas,a.username,a.foto,a.level,a.role_id
                        FROM m_user a
                        LEFT JOIN m_pegawai b ON a.nik=b.nik
                        LEFT JOIN m_area_kerja c ON b.idareakerja=c.idareakerja
                        WHERE a.nik='$nik'";
        $query = $db2->query($query_str);
        $data['user'] = $query->row_array();
        $data['periodebaca'] =  $this->libfunction->format_periode($periode);
        $data['title'] = 'Hasil Baca ' . $this->libfunction->format_periode($periode);

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

        //filter data
        $data['wilayah'] = $this->Mastermanometer_model->getWilayah();
        $data['zona'] = $this->Mastermanometer_model->getZona($wilayah);

        //menghitung jumlah data
        $config['total_rows'] = $this->Hasilbaca_model->countReadingAll($periode, $key, $wilayah, $zona, $idjabatan, $nik);
        $data['total_rows'] = $config['total_rows'];

        //set Halaman per page
        if ($per_page = $this->input->post('per_page')) {
            if ($per_page == null) {
                $this->session->set_userdata('perPage', 10); //keyword disimpan sebagai session keywordMano dengan data (inputan)
            } else if ($per_page == "all") {
                $this->session->set_userdata('alldata', "yes");
                $this->session->set_userdata('perPage', $config['total_rows']);
            } else {
                $this->session->set_userdata('alldata', "no");
                $this->session->set_userdata('perPage', $per_page); //keyword disimpan sebagai session keywordMano dengan data (inputan)
            }
        } else {
            $per_page = $this->session->userdata('perPage'); //mengambil data session keywordMano dengan data (inputan) untuk dikirim ke model sebagai parameter pencarian.
            if ($per_page == null) {
                $this->session->set_userdata('perPage', 10); //keyword disimpan sebagai session keywordMano dengan data (inputan)
            } else {
                $this->session->set_userdata('perPage', $per_page); //keyword disimpan sebagai session keywordMano dengan data (inputan)
            }
        }

        if ($daterange == "yes") {
            $tglStart = $this->input->post('tglStart'); //search form
            $tglEnd = $this->input->post('tglEnd'); //search form
            $this->session->set_userdata('tglStart', $tglStart);
            $this->session->set_userdata('tglEnd', $tglEnd);
        }

        $per_page = $this->session->userdata('perPage');

        //membuat config halaman/pagination
        $config['base_url'] = base_url('hasilbaca/periode/' . $periode);
        $config['per_page'] = $per_page;


        //initialize
        $this->pagination->initialize($config);
        $start = $this->uri->segment(4);

        if ($start == null) {
            $start = 0;
        }

        $data['bacaan'] = $this->Hasilbaca_model->getReadingAll($periode, $key, $wilayah, $zona, $start, $config['per_page'], $idjabatan, $nik);
        $data['lapbacaan'] = $this->Hasilbaca_model->getReadingReport($periode, $key, $wilayah, $zona, $idjabatan, $nik, $daterange, $tglStart, $tglEnd);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('templates/topbar');
        $this->load->view('hasilBaca/harian', $data);
        $this->load->view('templates/footer');

        if ($do == "cetak") {
            $mpdf = new \Mpdf\Mpdf();
            $html = $this->load->view('hasilBaca/dataPeriode_pdf', $data, true);
            $mpdf->WriteHTML($html);
            $mpdf->Output();
        }
    }

    public function detailBaca()
    {
        $id_periode = $this->input->post('id_periode');
        $periode = $this->input->post('periode');
        $data = $this->Hasilbaca_model->get_detailBaca($id_periode, $periode);
        echo json_encode($data);
    }

    public function refresh($periode, $no = null)
    {
        $this->session->unset_userdata('keywordMano');
        $this->session->unset_userdata('kodeWilayah');
        $this->session->unset_userdata('kodeZona');
        $this->session->unset_userdata('tglStart');
        $this->session->unset_userdata('tglEnd');
        $this->session->unset_userdata('dateRange');
        redirect('hasilbaca/periode/' . $periode . "/" . $no);
    }

    public function verifyAll()
    {
        $id = $this->input->post('id');
        $periode = $this->input->post('periode');

        if (count($id) == 0) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert"> Pilih minimal satu data! </div>');
            redirect('hasilbaca/periode/' . $periode);
        } else {
            $this->Hasilbaca_model->verifyAll($id, $periode);
            $this->session->set_flashdata('message', '<div class="alert alert-success col-lg-3 ml-5 mt-1 text-center" role="alert"> Verifikasi berhasil </div>');
        }
    }

    public function konfirmasi()
    {
        $id = $this->input->post('id');
        $periode = $this->input->post('periode');
        $txt = $this->input->post('txt');

        if (count($id) == 0) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert"> Pilih minimal satu data! </div>');
            redirect('hasilbaca/periode/' . $periode);
        } else {
            $this->Hasilbaca_model->konfirmasi($id, $periode, $txt);
            $this->session->set_flashdata('message', '<div class="alert alert-success col-lg-3 ml-5 mt-1 text-center" role="alert"> Verifikasi berhasil </div>');
        }
    }
}
