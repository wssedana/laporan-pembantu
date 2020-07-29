<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MasterManometer extends CI_Controller
{
    public function __construct()
    {
        date_default_timezone_set('Asia/Makassar');
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('Mastermanometer_model');
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
        //filter data
        $data['zona'] = $this->Mastermanometer_model->getZona($wilayah);

        //membuat config halaman/pagination
        $config['base_url'] = base_url('masterManometer/index');
        $config['per_page'] = 10;
        //menghitung jumlah data
        $config['total_rows'] = $this->Mastermanometer_model->countManometerAll($key, $wilayah, $zona);
        $data['total_rows'] = $config['total_rows'];

        //initialize
        $this->pagination->initialize($config);
        $start = $this->uri->segment(3);

        if ($start == null) {
            $start = 0;
        }

        $data['manometer'] = $this->Mastermanometer_model->getManometerAll($key, $wilayah, $zona, $start, $config['per_page']);
        $data['title'] = 'Master Manometer';

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('templates/topbar');
        $this->load->view('masterManometer/index', $data);
        $this->load->view('templates/footer');
    }

    public function tambah()
    {
        $data['title'] = 'Tambah Manometer';

        $wilayah = $this->session->userdata('kodeWilayah');
        $zona = $this->session->userdata('kodeZona');

        $nik = $this->session->userdata('nik');
        $db2 = $this->load->database('datacenter', TRUE);
        $query_str = "SELECT a.nik,b.nama,b.idareakerja,c.areakerja,c.wilayah,b.idjabatan,b.tugas,a.username,a.foto,a.level,a.role_id
                        FROM m_user a
                        LEFT JOIN m_pegawai b ON a.nik=b.nik
                        LEFT JOIN m_area_kerja c ON b.idareakerja=c.idareakerja
                        WHERE a.nik='$nik'";
        $query = $db2->query($query_str);
        $data['user'] = $query->row_array();

        //form validation
        $this->form_validation->set_rules('nama_manometer', 'Nama Manometer', 'required|trim|min_length[5]', [
            'required' => '*Nama Manometer tidak boleh kosong',
            'min_length' => '*Nama Manometer minimal 5 karakter'
        ]);
        $this->form_validation->set_rules('kodeZona', 'Kode Manometer', 'required|trim|numeric|xss_clean', [
            'required' => '*Kode tidak boleh kosong',
            'numeric' => '*Kode hanya angka'
        ]);
        $this->form_validation->set_rules('diameter', 'Diameter Pipa', 'required|trim|numeric|xss_clean', [
            'required' => '*Diameter tidak boleh kosong',
            'numeric' => '*Hanya angka'
        ]);
        $this->form_validation->set_rules('manoWilayah', 'Kecamatan', 'required|trim', [
            'required' => '*Kecamatan tidak boleh kosong'
        ]);
        $this->form_validation->set_rules('manoZona', 'Zona', 'required|trim', [
            'required' => '*Zona tidak boleh kosong'
        ]);
        $this->form_validation->set_rules('latitude', 'Latitude', 'required|trim|min_length[5]', [
            'required' => '*Latitude tidak boleh kosong',
            'min_length' => '*Panjang latitude tidak sesuai'
        ]);
        $this->form_validation->set_rules('longitude', 'Longitude', 'required|trim|min_length[5]', [
            'required' => '*Longitude tidak boleh kosong',
            'min_length' => '*Panjang longitude tidak sesuai'
        ]);
        $this->form_validation->set_rules('akurasi', 'Akurasi', 'required|trim|numeric|xss_clean', [
            'required' => '*Tidak boleh kosong',
            'numeric' => '*Hanya andka'
        ]);
        $this->form_validation->set_rules('operator', 'Petugas Baca', 'required|trim', [
            'required' => '*Petugas baca tidak boleh kosong'
        ]);
        $this->form_validation->set_rules('nikOperator', 'Petugas Baca', 'required', [
            'required' => '*Pilih petugas baca'
        ]);
        $this->form_validation->set_rules('id_zona', 'ID Zona', 'required', [
            'required' => '*tidak boleh kosong'
        ]);

        if ($this->form_validation->run() == false) {
            $data['wilayah']            = $this->Mastermanometer_model->getWilayah();
            $data['areakerja']          = $this->Mastermanometer_model->getAreaKerja($wilayah);
            $data['zona']               = $this->Mastermanometer_model->getZona($wilayah);
            $data['IDzona']             = $this->Mastermanometer_model->getIDZona($zona);
            $data['pembaca']            = $this->Mastermanometer_model->getPembaca($wilayah);
            $data['pembacaZona']        = $this->Mastermanometer_model->getPembacaZona($wilayah, $zona);
            $data['total_manometer']    = $this->Mastermanometer_model->countManometerAll();
            $data['total_manozona']     = $this->Mastermanometer_model->countManometerAll($key = null, $wilayah, $zona);

            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar');
            $this->load->view('templates/topbar');
            $this->load->view('masterManometer/tambah', $data);
            $this->load->view('templates/footer');
        } else {
            $id_manometer   = $this->input->post('id_manometer');
            $id_zona        = $this->input->post('id_zona');
            $id_dma         = "0";
            $id_kecamatan   = $this->input->post('id_kecamatan');
            $kode_manometer = $this->input->post('kodeZona');
            $manometer      = $this->input->post('nama_manometer');
            $diameter       = $this->input->post('diameter');
            $create_date    = date("Y-m-d H:i:s");
            $flag_active    = $this->input->post('flag_active');
            $latitude       = $this->input->post('latitude');
            $longitude      = $this->input->post('longitude');
            $foto           = $_FILES['foto'];
            $nik            = $this->input->post('nikOperator');
            $operator       = $this->input->post('operator');
            $accuracy       = $this->input->post('akurasi');
            $kode_gis       = $this->input->post('gis');

            if ($foto['name'] == "") {
                $data = array(
                    'id_manometer'   => $id_manometer,
                    'id_zona'        => $id_zona,
                    'id_dma'         => $id_dma,
                    'id_kecamatan'   => $id_kecamatan,
                    'kode_manometer' => $kode_manometer,
                    'manometer'      => $manometer,
                    'diameter'       => $diameter,
                    'create_date'    => $create_date,
                    'change_date'    => $create_date,
                    'flag_active'    => $flag_active,
                    'latitude'       => $latitude,
                    'longitude'      => $longitude,
                    'foto'           => 'none.jpg',
                    'nik'            => $nik,
                    'operator'       => $operator,
                    'accuracy'       => $accuracy,
                    'kode_gis'       => $kode_gis
                );

                $this->Mastermanometer_model->insertData($data);
                $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Tambah data berhasil</div>');
                redirect('masterManometer');
            } else {
                $config['upload_path']          = '../manoWS/fotomano/';
                $config['allowed_types']        = 'gif|jpg|png';
                $config['max_size']             = 2000;
                $config['max_width']            = 1920;
                $config['max_height']           = 1080;

                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('foto')) {
                    $error = array('error' => $this->upload->display_errors());
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Gagal insert data, pastikan ukuran dan format foto benar !</div>');
                    redirect('masterManometer');
                } else {
                    $foto    = $this->upload->data('file_name');
                }
                $data               = array(
                    'id_manometer'   => $id_manometer,
                    'id_zona'        => $id_zona,
                    'id_dma'         => $id_dma,
                    'id_kecamatan'   => $id_kecamatan,
                    'kode_manometer' => $kode_manometer,
                    'manometer'      => $manometer,
                    'diameter'       => $diameter,
                    'create_date'    => $create_date,
                    'change_date'    => $create_date,
                    'flag_active'    => $flag_active,
                    'latitude'       => $latitude,
                    'longitude'      => $longitude,
                    'foto'           => $foto,
                    'nik'            => $nik,
                    'operator'       => $operator,
                    'accuracy'       => $accuracy,
                    'kode_gis'       => $kode_gis
                );

                $this->Mastermanometer_model->insertData($data);
                $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Tambah data berhasil</div>');
                redirect('masterManometer');
            }
        }
    }

    public function detail($id)
    {
        $data['title'] = 'Detail Manometer';

        $wilayah = $this->session->userdata('wilayah');
        $nik = $this->session->userdata('nik');
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

        $data['manometer']  = $this->Mastermanometer_model->getById($id);
        $data['pembaca']    = $this->Mastermanometer_model->getPembaca($wilayah);
        //memanggil data dari database menggunakan model dengan memanggil aliasnya
        $data['listPeriode'] = $this->lsMasterData->getListPeriode();
        $data['tahunPeriode'] = $this->lsMasterData->getTahunPeriode();
        //------------------------------nama table database
        $data['lsMasterData'] = $this->db->get('information_schema.tables')->result_array();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('templates/topbar');
        $this->load->view('masterManometer/detail', $data);
        $this->load->view('templates/footer');
    }

    public function update()
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

        $res = preg_replace("/[^0-9.]/", "", $this->input->post('diameter'));
        $id_manometer   = $this->input->post('id_manometer');
        $kode_manometer = $this->input->post('kode');
        $manometer      = $this->input->post('nama');
        $diameter       = $res;
        $flag_active    = $this->input->post('flag_active');
        $latitude       = $this->input->post('latitude');
        $longitude      = $this->input->post('longitude');
        $nik            = $this->input->post('nik_operator');
        $operator       = $this->input->post('operator');
        $accuracy       = $this->input->post('akurasi');
        $kode_gis       = $this->input->post('gis');
        $foto           = $_FILES['foto'];
        $change_date    = date('Y-m-d H:i:s');

        if ($foto['name'] == "") {
            $data               = array(
                'kode_manometer'    => $kode_manometer,
                'manometer'         => $manometer,
                'diameter'          => $diameter,
                'change_date'       => $change_date,
                'flag_active'       => $flag_active,
                'latitude'          => $latitude,
                'longitude'         => $longitude,
                'nik'               => $nik,
                'operator'          => $operator,
                'accuracy'          => $accuracy,
                'kode_gis'          => $kode_gis
            );
            $this->Mastermanometer_model->updateData($id_manometer, $data);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Update berhasil</div>');
            redirect('masterManometer/detail/' . $id_manometer);
        } else {
            $config['upload_path']          = '../manoWS/fotomano/';
            $config['allowed_types']        = 'gif|jpg|png';
            $config['max_size']             = 2000;
            $config['max_width']            = 1920;
            $config['max_height']           = 1080;

            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            if (!$this->upload->do_upload('foto')) {
                $error = array('error' => $this->upload->display_errors());
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Gagal insert data, pastikan ukuran dan format foto benar !</div>');
                redirect('masterManometer/detail/' . $id_manometer);
            } else {
                $foto    = $this->upload->data('file_name');
                $data           = array(
                    'kode_manometer'    => $kode_manometer,
                    'manometer'         => $manometer,
                    'diameter'          => $diameter,
                    'change_date'       => $change_date,
                    'flag_active'       => $flag_active,
                    'latitude'          => $latitude,
                    'longitude'         => $longitude,
                    'nik'               => $nik,
                    'operator'          => $operator,
                    'accuracy'          => $accuracy,
                    'kode_gis'          => $kode_gis,
                    'foto'              => $foto

                );
                $this->Mastermanometer_model->updateData($id_manometer, $data);
                $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Update berhasil</div>');
                redirect('masterManometer/detail/' . $id_manometer);
            }
        }
    }

    public function delete($id)
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

        $manometer = $this->Mastermanometer_model->getById($id);
        $this->Mastermanometer_model->deleteById($id);
        $this->session->set_flashdata('message', '<div class="alert alert-warning" role="alert">Data <b>' . $manometer['manometer'] . '</b> berhasil di hapus</div>');
        redirect('masterManometer');
    }

    public function refresh()
    {
        $this->session->unset_userdata('keywordMano');
        $this->session->unset_userdata('kodeWilayah');
        $this->session->unset_userdata('kodeZona');
        redirect('masterManometer');
    }

    public function get_filterZona()
    {
        $wilayah = $this->input->post('wilayah');
        $data = $this->Mastermanometer_model->get_filterZona($wilayah);
        echo json_encode($data);
    }

    public function get_kodeZona()
    {
        $wilayah = $this->input->post('wilayah');
        $zona = $this->input->post('zona');

        $data['total_manozona'] = $this->Mastermanometer_model->countManometerAll($key = null, $wilayah, $zona);
        echo json_encode($data);
    }

    public function get_pembacaZona()
    {
        $wilayah = $this->input->post('wilayah');
        $zona = $this->input->post('zona');
        $data['pembacaZona']    = $this->Mastermanometer_model->getPembacaZona($wilayah, $zona);
        echo json_encode($data);
    }

    public function get_filterPembaca()
    {
        $wilayah = $this->input->post('wilayah');
        $data = $this->Mastermanometer_model->get_filterPembaca($wilayah);
        echo json_encode($data);
    }

    public function get_IDZona()
    {
        $zona = $this->input->post('zona');
        $data = $this->Mastermanometer_model->getIDZona($zona);
        echo json_encode($data);
    }

    public function get_IDWilayah()
    {
        $wilayah = $this->input->post('wilayah');
        $data = $this->Mastermanometer_model->getAreaKerja($wilayah);
        echo json_encode($data);
    }

    public function getNikOperator()
    {
        $nama = $this->input->post('nama');
        $data = $this->Mastermanometer_model->getNikOperator($nama);
        echo json_encode($data);
    }

    public function getManometerById()
    {
        $id = $this->input->post('id');
        $data = $this->Mastermanometer_model->getById($id);
        echo json_encode($data);
    }
}
