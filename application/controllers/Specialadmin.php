<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Specialadmin extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        //nama function helpers yg kita buat, nama ini bisa disesuaikan dgn keinginan.
        is_logged_in();
    }

    public function index()
    {
        $nik = $this->session->userdata('nik');
        //title pages
        $data['title'] = 'Dashboard';

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
        //------------------------------nama table database
        $data['lsMasterData'] = $this->db->get('information_schema.tables')->result_array();

        //memanggil views halaman admin -> $data: untuk mengirim data session user dan data title ke halaman index user
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('specialadmin/index', $data);
        $this->load->view('templates/footer');
    }

    public function role()
    {
        //title pages
        $data['title'] = 'User Role Access';
        $nik = $this->session->userdata('nik');
        $db2 = $this->load->database('datacenter', TRUE);
        $query_str = "SELECT a.nik,b.nama,b.idareakerja,c.areakerja,c.wilayah,b.idjabatan,b.tugas,a.username,a.foto,a.level,a.role_id
                        FROM m_user a
                        LEFT JOIN m_pegawai b ON a.nik=b.nik
                        LEFT JOIN m_area_kerja c ON b.idareakerja=c.idareakerja
                        WHERE a.nik='$nik'";
        $query = $db2->query($query_str);
        $data['user'] = $query->row_array();

        $data['role'] = $this->db->get('user_role')->result_array();

        $this->form_validation->set_rules('role', 'Role', 'required|min_length[4]');

        if ($this->form_validation->run() == false) {
            //memanggil views halaman admin -> $data: untuk mengirim data session user dan data title ke halaman index user
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('specialadmin/role', $data);
            $this->load->view('templates/footer');
        } else {
            //data tanpa array
            $this->db->insert('user_role', ['role' => $this->input->post('role')]);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert" style="text-align: center">New Role added!</div>');
            redirect('admin/role');
        }
    }

    public function roleAccess($role_id)
    {
        //title pages
        $data['title'] = 'Role Access';
        $nik = $this->session->userdata('nik');
        $db2 = $this->load->database('datacenter', TRUE);
        $query_str = "SELECT a.nik,b.nama,b.idareakerja,c.areakerja,c.wilayah,b.idjabatan,b.tugas,a.username,a.foto,a.level,a.role_id
                        FROM m_user a
                        LEFT JOIN m_pegawai b ON a.nik=b.nik
                        LEFT JOIN m_area_kerja c ON b.idareakerja=c.idareakerja
                        WHERE a.nik='$nik'";
        $query = $db2->query($query_str);
        $data['user'] = $query->row_array();

        //Menampilkan 1 row
        $data['role'] = $this->db->get_where('user_role', ['id' => $role_id])->row_array();

        //tidak menampilkan Specialadmin, Superadmin,Admin
        // $this->db->where('id !=', 3);
        // $this->db->where('id !=', 2);
        $this->db->where('id !=', 1);
        //mengambil data menu
        $data['menu'] = $this->db->get('user_menu')->result_array();

        //memanggil views halaman admin -> $data: untuk mengirim data session user dan data title ke halaman index user
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('specialadmin/roleaccess', $data);
        $this->load->view('templates/footer');
    }

    public function changeAccess()
    {
        $menu_id = $this->input->post('menuId');
        $role_id = $this->input->post('roleId');

        $data = [
            'role_id' => $role_id,
            'menu_id' => $menu_id
        ];

        $result = $this->db->get_where('user_access_menu', $data);

        if ($result->num_rows() < 1) {
            $this->db->insert('user_access_menu', $data);
        } else {
            $this->db->delete('user_access_menu', $data);
        }

        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert" style="text-align: center">Access change</div>');
    }
}
//end of Admin
