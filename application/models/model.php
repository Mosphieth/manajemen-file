<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model extends CI_Model
{
    public function getSubmenu()
    {
        $query = "SELECT `user_sub_menu`.*, `user_menu`.`menu`
        FROM `user_sub_menu` JOIN `user_menu`
        ON `user_sub_menu`.`menu_id` = `user_menu`.`id`
        ";

        return $this->db->query($query)->result_array();
    }

    public function getAllFile()
    {
        $result = $this->db->get('file')->result_array();

        return $result;
    }

    public function getAllFileandUserName()
    {
        $query = "SELECT `file`.*, `user`.`nama` FROM `file` JOIN `user` ON `file`.`user_id` = `user`.`id` ";

        $result = $this->db->query($query)->result_array();

        return $result;
    }

    public function getFilebyId($id)
    {
        $result = $this->db->get_where('file', ['user_id' => $id])->result_array();

        return $result;
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('user_menu');
    }

    public function deletefile($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('file');
    }

    public function uploadfile($name)
    {
        $config['upload_path']  = './assets/file/';
        $config['allowed_types']  = 'pdf';
        $config['max_size'] = 1044070;
        $config['file_name'] = $name;

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('userfile')) {
            $error = $this->upload->display_errors();
            // menampilkan pesan error
            print_r($error);
        } else {
            $result = $this->upload->data();
            print_r($result);
        }
    }

    public function acceptFile($id)
    {
        $this->db->set('status', 'Accepted');
        $this->db->where('id', $id);
        $this->db->update('file');
    }
    public function refuseFile($id)
    {
        $this->db->set('status', 'Refused');
        $this->db->where('id', $id);
        $this->db->update('file');
    }
}
