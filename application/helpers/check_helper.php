<?php
function is_logged_in()
{
    $check = get_instance();
    if (!$check->session->userdata('email')) {
        redirect('auth');
    } else {
        $role_id = $check->session->userdata('role_id');
        $menu = $check->uri->segment(1);
        $queryMenu = $check->db->get_where('user_menu', ['menu' => $menu])->row_array();
        $menu_id = $queryMenu['id'];

        $userAccess = $check->db->get_where('user_access_menu', [
            'role_id' => $role_id,
            'menu_id' => $menu_id
        ]);

        if ($userAccess->num_rows() < 1) {
            redirect('auth/block');
        }
    }
    function check_access($role_id, $menu_id)
    {
        $ch = get_instance();

        $result = $ch->db->get_where('user_access_menu', [
            'role_id' => $role_id,
            'menu_id' => $menu_id
        ]);

        if ($result->num_rows() > 0) {
            return "checked='checked'";
        }
    }
}
