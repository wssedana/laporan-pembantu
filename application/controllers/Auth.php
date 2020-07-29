<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
    //method default
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
    }

    //login
    public function index()
    {
        $this->form_validation->set_rules('email', 'Email', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'ManoApp User Login';
            $this->load->view('templates/auth_header', $data);
            $this->load->view('auth/login');
            $this->load->view('templates/auth_footer');
        } else {
            //validasinya success, mrnggunakan method login
            //untuk menandai function ini adalah private, sy isi dgn underscore, untuk menendai saja
            $this->_login();
        }
    }

    private function _login()
    {
        $username = $this->input->post('email');
        $password = $this->input->post('password');
        $password2 = md5($password);

        $db2 = $this->load->database('datacenter', TRUE);
        //database kedua
        //$data['user'] = $db2->get_where('m_user', ['username' => $this->session->userdata('username')])->row_array();
        //var_dump($data['user']);

        //sandika galih tutorial : $user = $this->db->get_where('m_user', ['email' => $email])->row_array();
        $query_str = "SELECT * FROM m_user WHERE (username='$username' OR nik='$username')";
        $query = $db2->query($query_str);
        $user = $query->row_array();
        //jika usernya ada
        if ($user) {
            //jika usernya aktif
            if ($user['mod_manometer'] == "Y") {
                //check password, mencocokan password antara form input dengan field database
                if ($password2 == $user['md5pass']) {

                    $db2 = $this->load->database('datacenter', TRUE);
                    $query_str = "SELECT a.nik,b.nama,b.idareakerja,c.areakerja,c.wilayah,b.idjabatan,b.tugas,a.username,a.foto,a.level,a.role_id
                                    FROM m_user a
                                    LEFT JOIN m_pegawai b ON a.nik=b.nik
                                    LEFT JOIN m_area_kerja c ON b.idareakerja=c.idareakerja
                                    WHERE a.nik='$user[nik]'";
                    $query = $db2->query($query_str);
                    $datalogin = $query->row_array();

                    //data user yang akan disimpan pada website
                    $data = [
                        'username' => $datalogin['username'],
                        'nik' => $datalogin['nik'],
                        'idareakerja' => $datalogin['idareakerja'],
                        'idjabatan' => $datalogin['idjabatan'],
                        'wilayah' => $datalogin['wilayah'],
                        'role_id' => $datalogin['role_id']
                    ];

                    $this->session->set_userdata($data);
                    //check role user
                    if ($user['role_id'] == 4) {
                        redirect('user');
                    } else {
                        redirect('admin');
                    }
                } else {
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert" style="text-align: center">Wrong password!</div>');
                    redirect('auth');
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert" style="text-align: center">Email is not activated!</div>');
                redirect('auth');
            }
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert" style="text-align: center">Email or Username is not registered!</div>');
            redirect('auth');
        }
    }

    public function registration()
    {
        //codeigniter.com/user_guide/libraries/form_validation.html?highlight=form%20validation#rule-reference
        $this->form_validation->set_rules('name', 'Name', 'required|trim');
        $this->form_validation->set_rules('username', 'Username', 'required|trim|min_length[5]|is_unique[m_user.username]', [
            'min_length' => 'Minimum 5 character!',
            'is_unique' => 'Username has already!'
        ]);
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[m_user.email]', [
            'is_unique' => 'Email has already registered!'
        ]);
        $this->form_validation->set_rules('password1', 'Password', 'required|trim|min_length[5]|matches[password2]', [
            'matches' => 'Password not match!',
            'min_length' => 'Password too short!'
        ]);
        $this->form_validation->set_rules('password2', 'Password', 'required|trim|matches[password1]');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'ManoApp User Registration';
            $this->load->view('templates/auth_header', $data);
            $this->load->view('auth/registration');
            $this->load->view('templates/auth_footer');
        } else {
            $data = [
                'name' => htmlspecialchars($this->input->post('name', true)),
                'username' => htmlspecialchars($this->input->post('username', true)),
                'email' => htmlspecialchars($this->input->post('email', true)),
                'image' => 'default.jpg',
                'password' => password_hash($this->input->post('password1'), PASSWORD_DEFAULT),
                'role_id' => 4,
                'is_active' => 1,
                'date_created' => time()
            ];

            $this->db->insert('m_user', $data);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert" style="text-align: center">Congratulation! your account han been created. <br/> Please Login</div>');
            redirect('auth');
        }
    }

    public function logout()
    {
        $this->session->unset_userdata('username');
        $this->session->unset_userdata('nik');
        $this->session->unset_userdata('idareakerja');
        $this->session->unset_userdata('idjabatan');
        $this->session->unset_userdata('wilayah');
        $this->session->unset_userdata('role_id');
        $this->session->unset_userdata('kodeWilayah');
        $this->session->unset_userdata('kodeZona');
        $this->session->unset_userdata('keywordMano');

        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert" style="text-align: center">You have been Log Out!</div>');
        redirect('auth');
    }

    public function blocked()
    {
        $this->load->view('auth/blocked');
    }
}
