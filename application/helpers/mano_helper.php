<?php
//tambahkan pada autoload.php line 92: helpers.

function is_logged_in()
{
    //memanggil fungsi CI ke dalam function ini, agar $this bisa dipanggil
    $ci = get_instance();
    //mengecek session 
    if (!$ci->session->userdata('username')) {
        redirect('auth');
    } else {
        //jika sudah login, kita check sessionnya.untuk menentukan hak aksesnya.
        $role_id = $ci->session->userdata('role_id');
        //CI Documentation: segment.
        $menu = $ci->uri->segment(1);

        //query menu 
        $queryMenu = $ci->db->get_where('user_menu', ['url' => $menu])->row_array();
        $menu_id = $queryMenu['id'];

        //query user access menu
        $userAccess = $ci->db->get_where('user_access_menu', [
            'role_id' => $role_id,
            'menu_id' => $menu_id
        ]);

        //check data: jumlah barisnya lebih kecil dari 1
        if ($userAccess->num_rows() < 1) {
            redirect('auth/blocked');
        }
    }
}

function check_access($role_id, $menu_id)
{
    $ci = get_instance();

    //contoh menggunakan tidak dalam satu query
    $ci->db->where('role_id', $role_id);
    $ci->db->where('menu_id', $menu_id);
    $result = $ci->db->get('user_access_menu');

    //contoh dengan satu query, logika query sama dengan di atas
    //$ci->db->get_where('user_access_menu', ['role_id' => $role_id, 'menu_id' => $menu_id]);

    if ($result->num_rows() > 0) {
        return "checked='checked'";
    }
}
